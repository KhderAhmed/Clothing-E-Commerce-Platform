<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; 
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderPlacedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $orderId;
    public $customerName;
    public $total;

    public function __construct(Order $order)
    {
        $this->orderId = $order->id;
        $this->customerName = $order->customer_name;
        $this->total = $order->total;
    }

    // اسم القناة التي سيستمع إليها الآدمن
    public function broadcastOn(): array
    {
        return [
            new Channel('admin-orders')
        ];
    }

    // الاسم الحركي للحدث داخل الـ JavaScript
    public function broadcastAs(): string
    {
        return 'OrderPlaced';
    }
}