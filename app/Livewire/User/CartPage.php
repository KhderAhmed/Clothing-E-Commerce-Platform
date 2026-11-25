<?php

namespace App\Livewire\User;

use App\Services\CartService;
use Livewire\Component;

class CartPage extends Component
{
    public array   $cart     = [];
    public float   $subtotal = 0;
    public float   $tax      = 0;
    public float   $total    = 0;

    public function mount(CartService $cart): void
    {
        $this->loadCart($cart);
    }

    public function loadCart(CartService $cart): void
    {
        // ✨ CartService ينظّف تلقائياً أي بنية قديمة
        $this->cart = $cart->all();

        $this->subtotal = collect($this->cart)->sum(
            fn($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 0)
        );

        $this->tax   = $this->subtotal * 0.05;
        $this->total = $this->subtotal + $this->tax;
    }

    public function incrementQuantity(int $productId, CartService $cart): void
    {
        $cart->increment($productId);
        $this->loadCart($cart);
        $this->dispatch('cartUpdated', totalCount: $cart->count());
    }

    public function decrementQuantity(int $productId, CartService $cart): void
    {
        $cart->decrement($productId);
        $this->loadCart($cart);
        $this->dispatch('cartUpdated', totalCount: $cart->count());
    }

    public function removeItem(int $productId, CartService $cart): void
    {
        $productName = $this->cart[$productId]['name'] ?? 'المنتج';

        $cart->remove($productId);
        $this->loadCart($cart);

        $this->dispatch('cartUpdated', totalCount: $cart->count());
        $this->dispatch('showNotification',
            message: "🗑️ تم إزالة {$productName} من السلة",
            type: 'warning'
        );
    }

    public function clearCart(CartService $cart): void
    {
        $cart->clear();
        $this->loadCart($cart);

        $this->dispatch('cartUpdated', totalCount: 0);
        $this->dispatch('showNotification',
            message: '🗑️ تم تفريغ السلة بالكامل',
            type: 'error'
        );
    }

    public function render()
    {
        return view('livewire.user.cart-page')->layout('layouts.user');
    }
}