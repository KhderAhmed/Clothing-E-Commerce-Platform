<?php

namespace App\Events;

use App\Models\Product;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $product;
    public $action;
    public $name;

    public function __construct($product, $action, $name = null)
    {
        $this->product = $product;
        $this->action = $action;

        if ($product) {
            $this->name = $product->name;
        } else {
            $this->name = $name;
        }
    }

    public function broadcastOn()
    {
        return new Channel('products');
    }

    public function broadcastAs()
    {
        return 'ProductUpdatedEvent';
    }

    public function broadcastWith()
    {
        $data = [
            'action' => $this->action,
            'name' => $this->name,
            'timestamp' => now()->toIso8601String(),
        ];

        if (
            $this->product &&
            in_array($this->action, ['created', 'updated', 'published'])
        ) {
            $this->product->loadMissing([
                'variants.color',
                'variants.size',
            ]);

            $data['product'] = [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => $this->product->price,
                'final_price' => $this->product->final_price,
                'discount' => $this->product->discount,
                'is_discounted' => $this->product->is_discounted,
                'discount_percentage' => $this->product->discount_percentage,

                'variants' => $this->product->variants->map(function ($variant) {
                    return [
                        'id' => $variant->id,
                        'color_id' => $variant->color_id,
                        'color' => $variant->color?->name,
                        'size_id' => $variant->size_id,
                        'size' => $variant->size?->name,
                        'sku' => $variant->sku,
                        'price' => $variant->price,
                        'stock' => $variant->stock,
                    ];
                })->values()->toArray(),
            ];
        }

        return $data;
    }
}

