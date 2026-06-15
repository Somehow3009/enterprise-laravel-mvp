<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\DB;

final class AnalyticsService
{
    /**
     * @return array<string, mixed>
     */
    public function dashboard(): array
    {
        $monthExpression = DB::connection()->getDriverName() === 'pgsql'
            ? "DATE_TRUNC('month', placed_at)::date"
            : "date(placed_at, 'start of month')";

        return [
            'monthly_sales' => DB::table('orders')
                ->selectRaw("{$monthExpression} AS month, SUM(total_amount) AS revenue, COUNT(*) AS orders_count")
                ->whereNotNull('placed_at')
                ->where('status', '<>', 'cancelled')
                ->groupByRaw($monthExpression)
                ->orderBy('month')
                ->limit(12)
                ->get(),
            'order_distribution_by_service_type' => DB::table('order_service')
                ->join('services', 'services.id', '=', 'order_service.service_id')
                ->join('orders', 'orders.id', '=', 'order_service.order_id')
                ->selectRaw('services.type, COUNT(DISTINCT orders.id) AS orders_count, SUM(order_service.line_total) AS revenue')
                ->where('orders.status', '<>', 'cancelled')
                ->groupBy('services.type')
                ->orderByDesc('orders_count')
                ->get(),
            'top_service_categories' => DB::table('order_service')
                ->join('services', 'services.id', '=', 'order_service.service_id')
                ->join('orders', 'orders.id', '=', 'order_service.order_id')
                ->selectRaw('services.category, SUM(order_service.line_total) AS revenue, SUM(order_service.quantity) AS units_sold')
                ->where('orders.status', '<>', 'cancelled')
                ->groupBy('services.category')
                ->orderByDesc('revenue')
                ->limit(10)
                ->get(),
        ];
    }
}
