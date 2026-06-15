<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AIInsight;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

final class AIService
{
    /**
     * @return array<string, mixed>
     */
    public function generateBusinessInsight(string $query, ?int $userId = null, string $provider = 'mock'): array
    {
        $startedAt = microtime(true);
        $context = $this->retrieveRelevantBusinessContext($query);
        $prompt = $this->buildPrompt($query, $context);

        try {
            $insight = $provider === 'mock'
                ? $this->mockCompletion($query, $context)
                : $this->completeWithProvider($provider, $prompt);

            $latencyMs = (int) round((microtime(true) - $startedAt) * 1000);

            $record = AIInsight::query()->create([
                'user_id' => $userId,
                'query_hash' => hash('sha256', $query),
                'query' => $query,
                'retrieved_context' => $context,
                'insight' => $insight,
                'provider' => $provider,
                'latency_ms' => $latencyMs,
                'metadata' => ['embedding_model' => 'mock-hash-vector'],
            ]);

            Log::info('AI insight generated', [
                'ai_insight_id' => $record->id,
                'provider' => $provider,
                'latency_ms' => $latencyMs,
            ]);

            return [
                'id' => $record->id,
                'query' => $query,
                'context' => $context,
                'insight' => $insight,
                'latency_ms' => $latencyMs,
            ];
        } catch (Throwable $exception) {
            Log::error('AI insight generation failed', [
                'provider' => $provider,
                'message' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }

    /**
     * Mocked vector retrieval. Replace this with pgvector/Pinecone by storing embeddings per service/order note.
     *
     * @return array<int, array<string, mixed>>
     */
    public function retrieveRelevantBusinessContext(string $query): array
    {
        $embedding = $this->mockEmbedding($query);
        $intentScore = array_sum($embedding);

        return [
            [
                'source' => 'analytics.low_performance_services',
                'score' => round(($intentScore % 100) / 100, 2),
                'content' => 'Services with declining month-over-month revenue should be reviewed for SLA breaches, pricing fit, and customer churn signals.',
            ],
            [
                'source' => 'orders.delayed_workflow',
                'score' => 0.87,
                'content' => 'Delayed pending/processing orders are highest risk when due_at has passed and customer last_activity_at is stale.',
            ],
            [
                'source' => 'playbook.retention',
                'score' => 0.81,
                'content' => 'Recommended actions: prioritize recovery queue, contact impacted customers, and offer targeted service credits for high-value accounts.',
            ],
        ];
    }

    public function summarizeDelayedOrder(string $orderNumber, string $customerName, string $delayReason): string
    {
        return $this->mockCompletion(
            "Summarize delayed order {$orderNumber}",
            [[
                'source' => 'order.delay',
                'content' => "Customer {$customerName}. Reason: {$delayReason}.",
            ]]
        );
    }

    /**
     * @return array<int, int>
     */
    private function mockEmbedding(string $text): array
    {
        return array_map(
            static fn (string $chunk): int => hexdec($chunk) % 100,
            str_split(substr(hash('sha256', Str::lower($text)), 0, 32), 2)
        );
    }

    /**
     * @param array<int, array<string, mixed>> $context
     */
    private function buildPrompt(string $query, array $context): string
    {
        $contextText = collect($context)
            ->map(fn (array $item): string => "- {$item['source']}: {$item['content']}")
            ->implode("\n");

        return <<<PROMPT
You are a senior business operations analyst. Use the context to answer with concise, actionable recommendations.

Admin query:
{$query}

Retrieved context:
{$contextText}
PROMPT;
    }

    /**
     * @param array<int, array<string, mixed>> $context
     */
    private function mockCompletion(string $query, array $context): string
    {
        $sources = collect($context)->pluck('source')->implode(', ');

        return "AI Insight for '{$query}': Review low-performing service cohorts, correlate revenue decline with delayed orders, and create a recovery queue for accounts affected by SLA breaches. Retrieved sources: {$sources}.";
    }

    private function completeWithProvider(string $provider, string $prompt): string
    {
        return match ($provider) {
            'openai' => $this->completeWithOpenAI($prompt),
            'gemini' => $this->completeWithGemini($prompt),
            default => throw new RuntimeException("Unsupported AI provider [{$provider}]."),
        };
    }

    private function completeWithOpenAI(string $prompt): string
    {
        $response = $this->authorizedClient((string) config('services.openai.key'))
            ->post((string) config('services.openai.base_url').'/chat/completions', [
                'model' => config('services.openai.model', 'gpt-4o-mini'),
                'messages' => [
                    ['role' => 'system', 'content' => 'Return JSON-free business recommendations.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.2,
            ])
            ->throw()
            ->json();

        return (string) data_get($response, 'choices.0.message.content');
    }

    private function completeWithGemini(string $prompt): string
    {
        $apiKey = (string) config('services.gemini.key');
        $model = (string) config('services.gemini.model', 'gemini-1.5-flash');

        $response = Http::timeout(20)
            ->retry(2, 200)
            ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                'contents' => [
                    ['parts' => [['text' => $prompt]]],
                ],
            ])
            ->throw()
            ->json();

        return (string) data_get($response, 'candidates.0.content.parts.0.text');
    }

    private function authorizedClient(string $token): PendingRequest
    {
        return Http::timeout(20)
            ->retry(2, 200)
            ->acceptJson()
            ->withToken($token);
    }
}

