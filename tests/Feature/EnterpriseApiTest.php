<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Role;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

final class EnterpriseApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_and_receive_sanctum_token(): void
    {
        $admin = User::query()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->roles()->attach(Role::query()->create(['name' => 'admin']));

        $this->postJson('/api/auth/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ])->assertOk()
            ->assertJsonStructure(['token_type', 'access_token', 'user' => ['roles']]);
    }

    public function test_authenticated_user_can_place_complex_order(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $customer = Customer::query()->create([
            'code' => 'CUS-001',
            'name' => 'Acme',
            'email' => 'acme@example.com',
            'status' => 'active',
        ]);

        $service = Service::query()->create([
            'code' => 'SVC-001',
            'name' => 'Analytics Dashboard',
            'category' => 'Analytics',
            'type' => 'implementation',
            'base_price' => 1500,
            'is_active' => true,
            'delivery_sla_hours' => 72,
        ]);

        $this->postJson('/api/orders', [
            'customer_id' => $customer->id,
            'currency' => 'USD',
            'services' => [
                ['id' => $service->id, 'quantity' => 2],
            ],
        ])->assertCreated()
            ->assertJsonPath('customer.id', $customer->id)
            ->assertJsonPath('services.0.id', $service->id)
            ->assertJsonPath('total_amount', '3000.00');
    }

    public function test_admin_can_generate_mock_ai_insight(): void
    {
        $admin = User::factory()->create();
        $adminRole = Role::query()->create(['name' => 'admin']);
        $admin->roles()->attach($adminRole);

        Sanctum::actingAs($admin);

        $this->postJson('/api/admin/ai/insights', [
            'query' => "Analyze last month's low-performing services",
            'provider' => 'mock',
        ])->assertOk()
            ->assertJsonStructure(['id', 'query', 'context', 'insight', 'latency_ms']);
    }
}
