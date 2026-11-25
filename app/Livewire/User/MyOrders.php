<?php

namespace App\Livewire\User;

use App\Models\Order;
use Livewire\Component;

class MyOrders extends Component
{
    public function render()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with('items.product')
            ->latest()
            ->get();

        return view('livewire.user.my-orders', [
            'orders' => $orders,
        ])->layout('layouts.user');
    }
}
