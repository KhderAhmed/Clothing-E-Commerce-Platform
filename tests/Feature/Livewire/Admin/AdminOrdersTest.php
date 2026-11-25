<?php

namespace Tests\Feature\Livewire\Admin;

use App\Enums\OrderStatus;
use App\Livewire\Admin\AdminOrders;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminOrdersTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_paginated_orders_and_filter_by_status()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        Order::factory()->count(15)->create(['status' => OrderStatus::PENDING->value]);
        Order::factory()->create(['status' => OrderStatus::DELIVERED->value]); // تم التصحيح إلى DELIVERED

        Livewire::actingAs($admin)
            ->test(AdminOrders::class)
            ->assertSet('statusFilter', 'all')
            ->assertViewHas('orders', function ($orders) {
                return $orders->count() === 10;
            })
            ->set('statusFilter', 'pending')
            ->assertSet('statusFilter', 'pending');
    }

    public function test_admin_can_update_order_status_successfully()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $order = Order::factory()->create(['status' => OrderStatus::PENDING->value]);

        Livewire::actingAs($admin)
            ->test(AdminOrders::class)
            ->call('updateStatus', $order->id, OrderStatus::PROCESSING->value)
            ->assertDispatched('showNotification');

        $updatedStatus = $order->fresh()->status;
        $statusValue = $updatedStatus instanceof OrderStatus ? $updatedStatus->value : $updatedStatus;

        $this->assertEquals(OrderStatus::PROCESSING->value, $statusValue);
    }

    public function test_non_admin_cannot_update_order_status_and_gets_403_forbidden()
    {
        // تأكد من أن 'user' أو القيمة المستخدمة متوافقة مع الـ Check Constraint لجدول users
        $customer = User::factory()->create(['role' => 'user']); 
        $order = Order::factory()->create(['status' => OrderStatus::PENDING->value]);

        Livewire::actingAs($customer)
            ->test(AdminOrders::class)
            ->call('updateStatus', $order->id, OrderStatus::PROCESSING->value)
            ->assertForbidden();
    }
}