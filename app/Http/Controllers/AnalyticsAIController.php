<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\AIInsightRequest;
use App\Services\AIService;
use App\Services\AnalyticsService;
use Illuminate\Http\JsonResponse;

final class AnalyticsAIController extends Controller
{
    public function dashboard(AnalyticsService $analytics): JsonResponse
    {
        return response()->json($analytics->dashboard());
    }

    public function insight(AIInsightRequest $request, AIService $ai): JsonResponse
    {
        $data = $request->validated();

        return response()->json($ai->generateBusinessInsight(
            query: (string) $data['query'],
            userId: $request->user()?->id,
            provider: (string) ($data['provider'] ?? 'mock')
        ));
    }
}
