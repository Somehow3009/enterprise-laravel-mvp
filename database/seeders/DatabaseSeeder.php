<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Role;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::query()->firstOrCreate(
            ['name' => 'admin'],
            ['description' => 'Can access analytics and AI insight endpoints.']
        );

        $operatorRole = Role::query()->firstOrCreate(
            ['name' => 'operator'],
            ['description' => 'Can manage customers and orders.']
        );

        /** @var User $admin */
        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Demo Admin',
                'password' => Hash::make('password'),
            ]
        );
        $admin->roles()->syncWithoutDetaching([$adminRole->id, $operatorRole->id]);

        /** @var Customer $customer */
        $customer = Customer::query()->updateOrCreate(
            ['email' => 'acme@example.com'],
            [
                'code' => 'CUS-ACME',
                'name' => 'Acme Corporation',
                'phone' => '+1-555-0101',
                'company_name' => 'Acme Corporation',
                'status' => 'active',
                'metadata' => ['segment' => 'enterprise', 'region' => 'US'],
                'last_activity_at' => now(),
            ]
        );

        $services = collect([
            [
                'code' => 'SVC-CRM-OPS',
                'name' => 'CRM Operations Support',
                'category' => 'Operations',
                'type' => 'managed_service',
                'base_price' => 1200,
                'delivery_sla_hours' => 48,
            ],
            [
                'code' => 'SVC-DATA-DASH',
                'name' => 'Data Analytics Dashboard',
                'category' => 'Analytics',
                'type' => 'implementation',
                'base_price' => 3500,
                'delivery_sla_hours' => 96,
            ],
            [
                'code' => 'SVC-AI-INSIGHT',
                'name' => 'AI Business Insight Agent',
                'category' => 'AI',
                'type' => 'subscription',
                'base_price' => 2200,
                'delivery_sla_hours' => 72,
            ],
        ])->map(fn (array $data): Service => Service::query()->updateOrCreate(
            ['code' => $data['code']],
            $data + ['is_active' => true]
        ));

        if (Order::query()->count() === 0) {
            /** @var Order $order */
            $order = Order::query()->create([
                'order_number' => 'ORD-DEMO-0001',
                'customer_id' => $customer->id,
                'status' => Order::STATUS_PROCESSING,
                'subtotal_amount' => 5700,
                'discount_amount' => 300,
                'total_amount' => 5400,
                'currency' => 'USD',
                'placed_at' => now()->subDays(10),
                'due_at' => now()->subDay(),
                'metadata' => ['source' => 'demo_seed'],
            ]);

            $order->services()->attach([
                $services[1]->id => [
                    'quantity' => 1,
                    'unit_price' => 3500,
                    'line_total' => 3500,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                $services[2]->id => [
                    'quantity' => 1,
                    'unit_price' => 2200,
                    'line_total' => 2200,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);

            $customer->activityLogs()->create([
                'user_id' => $admin->id,
                'event' => 'demo_order_created',
                'properties' => ['order_number' => $order->order_number],
            ]);
        }
    }
}
