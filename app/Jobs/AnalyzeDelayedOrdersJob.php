<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Order;
use App\Notifications\DelayedOrderNotification;
use App\Services\AIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

final class AnalyzeDelayedOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly int $limit = 50)
    {
    }

    public function handle(AIService $aiService): void
    {
        Order::query()
            ->with(['customer', 'services'])
            ->delayed()
            ->oldest('due_at')
            ->limit($this->limit)
            ->get()
            ->each(function (Order $order) use ($aiService): void {
                $summary = $aiService->summarizeDelayedOrder(
                    $order->order_number,
                    (string) $order->customer?->name,
                    'Order exceeded configured service SLA.'
                );

                Notification::route('mail', config('ops.delayed_order_alert_email'))
                    ->notify(new DelayedOrderNotification($order, $summary));

                Log::warning('Delayed order analyzed by AI agent', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                ]);
            });
    }
}
