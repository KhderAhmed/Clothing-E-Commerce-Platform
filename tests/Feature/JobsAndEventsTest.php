<?php

namespace Tests\Feature;

use App\Events\OrderPlacedEvent;
use App\Events\ProductUpdatedEvent;
use App\Events\ProfileUpdatedEvent;
use App\Jobs\SendStyleReminder;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class JobsAndEventsTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_style_reminder_job_can_be_dispatched_and_handled()
    {
        Queue::fake();

        $user = User::factory()->create();

        SendStyleReminder::dispatch($user->id, [
            ['name' => 'قميص صيفي', 'price' => 120]
        ]);

        Queue::assertPushed(SendStyleReminder::class);
    }

    public function test_order_placed_event_broadcasts_on_correct_channel()
    {
        Event::fake();

        $order = Order::factory()->create([
            'customer_name' => 'خضر أحمد',
            'total' => 250.00,
        ]);

        event(new OrderPlacedEvent($order));

        Event::assertDispatched(OrderPlacedEvent::class, function ($event) use ($order) {
            return $event->orderId === $order->id &&
                   $event->customerName === 'خضر أحمد' &&
                   $event->total == 250.00;
        });
    }

    public function test_product_updated_event_prepares_correct_broadcast_data()
    {
        $product = Product::factory()->create([
            'name' => 'جاكيت جلد',
            'price' => 400.00,
            'discount' => 10,
        ]);

        $event = new ProductUpdatedEvent($product, 'updated');

        $broadcastData = $event->broadcastWith();

        $this->assertEquals('updated', $broadcastData['action']);
        $this->assertEquals('جاكيت جلد', $broadcastData['name']);
        $this->assertArrayHasKey('product', $broadcastData);
        $this->assertEquals(400.00, $broadcastData['product']['price']);
    }

    public function test_profile_updated_event_constructs_properly()
    {
        $event = new ProfileUpdatedEvent(1, 'خضر');

        $this->assertEquals(1, $event->userId);
        $this->assertEquals('خضر', $event->name);
        $this->assertEquals('user-updates', $event->broadcastOn()[0]->name);
        $this->assertEquals('ProfileUpdatedEvent', $event->broadcastAs());
    }
}