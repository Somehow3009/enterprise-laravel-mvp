<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use InvalidArgumentException;

final class OrderService
{
    /**
     * @param array<string, mixed> $payload
     */
    public function placeOrder(array $payload): Order
    {
        return DB::transaction(function () use ($payload): Order {
            /** @var Customer $customer */
            $customer = Customer::query()
                ->whereKey($payload['customer_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $servicePayloads = collect($payload['services']);
            $serviceIds = $servicePayloads->pluck('id')->map(fn (mixed $id): int => (int) $id)->all();

            $services = Service::query()
                ->whereIn('id', $serviceIds)
                ->where('is_active', true)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($services->count() !== count(array_unique($serviceIds))) {
                throw new InvalidArgumentException('One or more services are inactive or unavailable.');
            }

            $subtotal = 0.0;
            $maxSlaHours = 0;
            $pivotRows = [];

            foreach ($servicePayloads as $item) {
                /** @var Service $service */
                $service = $services->get((int) $item['id']);
                $quantity = (int) $item['quantity'];
                $unitPrice = isset($item['unit_price']) ? (float) $item['unit_price'] : (float) $service->base_price;
                $lineTotal = $unitPrice * $quantity;
                $subtotal += $lineTotal;
                $maxSlaHours = max($maxSlaHours, (int) $service->delivery_sla_hours);

                $pivotRows[$service->id] = [
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                    'metadata' => isset($item['metadata']) ? json_encode($item['metadata']) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $discount = (float) ($payload['discount_amount'] ?? 0);
            $total = max(0, $subtotal - $discount);

            /** @var Order $order */
            $order = Order::query()->create([
                'order_number' => $this->generateOrderNumber(),
                'customer_id' => $customer->id,
                'status' => Order::STATUS_PENDING,
                'subtotal_amount' => $subtotal,
                'discount_amount' => $discount,
                'total_amount' => $total,
                'currency' => strtoupper((string) ($payload['currency'] ?? 'USD')),
                'placed_at' => now(),
                'due_at' => now()->addHours($maxSlaHours),
                'metadata' => $payload['metadata'] ?? null,
            ]);

            $order->services()->attach($pivotRows);

            $customer->activityLogs()->create([
                'event' => 'order_placed',
                'properties' => ['order_id' => $order->id, 'total_amount' => $total],
            ]);
            $customer->forceFill(['last_activity_at' => now()])->save();

            Log::info('Order placed', [
                'order_id' => $order->id,
                'customer_id' => $customer->id,
                'total_amount' => $total,
            ]);

            return $order->load(['customer', 'services']);
        }, 3);
    }

    /**
     * @param array<string, mixed> $filters
     * @return CursorPaginator<int, Order>
     */
    public function search(array $filters): CursorPaginator
    {
        /** @var Builder<Order> $query */
        $query = Order::query()->with(['customer', 'services']);

        $query->when($filters['status'] ?? null, fn (Builder $query, string $status): Builder => $query->where('status', $status))
            ->when($filters['customer_id'] ?? null, fn (Builder $query, int|string $customerId): Builder => $query->where('customer_id', $customerId))
            ->when($filters['service_type'] ?? null, function (Builder $query, string $type): Builder {
                return $query->whereHas('services', fn (Builder $serviceQuery): Builder => $serviceQuery->where('type', $type));
            })
            ->when($filters['date_from'] ?? null, fn (Builder $query, string $date): Builder => $query->whereDate('placed_at', '>=', $date))
            ->when($filters['date_to'] ?? null, fn (Builder $query, string $date): Builder => $query->whereDate('placed_at', '<=', $date));

        $sort = (string) ($filters['sort'] ?? '-created_at');
        $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';
        $column = ltrim($sort, '-');

        return $query->orderBy($column, $direction)
            ->orderBy('id', $direction)
            ->cursorPaginate((int) ($filters['per_page'] ?? 25));
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function update(Order $order, array $payload): Order
    {
        if (isset($payload['status'])) {
            $payload = array_merge($payload, match ($payload['status']) {
                Order::STATUS_COMPLETED => ['completed_at' => now()],
                Order::STATUS_CANCELLED => ['cancelled_at' => now()],
                default => [],
            });
        }

        $order->fill($payload)->save();

        Log::info('Order updated', ['order_id' => $order->id, 'status' => $order->status]);

        return $order->load(['customer', 'services']);
    }

    private function generateOrderNumber(): string
    {
        return 'ORD-'.now()->format('YmdHis').'-'.Str::upper(Str::random(6));
    }
}

