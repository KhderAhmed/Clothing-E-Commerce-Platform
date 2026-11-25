<?php

namespace App\Services;

use App\Models\Product;

class CartService
{
    /**
     * إضافة منتج للسلة — يدعم البنية القديمة (integer) تلقائياً
     */
    public function add(int $productId, int $quantity = 1): bool
    {
        $product = Product::find($productId);
        if (!$product) {
            return false;
        }

        $cart = session('cart', []);

        // ✨ إصلاح البنية القديمة (integer من ClothingAssistant القديم)
        if (isset($cart[$productId]) && !is_array($cart[$productId])) {
            unset($cart[$productId]);
        }

        if (isset($cart[$productId]) && is_array($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'name'           => $product->name,
                'quantity'       => $quantity,
                'price'          => $product->final_price ?? $product->price,
                'original_price' => $product->price,
                'discount'       => $product->discount ?? 0,
                'image'          => $product->image ?? null,
            ];
        }

        session(['cart' => $cart]);
        return true;
    }

    /**
     * إرجاع السلة — منظّفة من أي بنية قديمة
     */
    public function all(): array
    {
        $cart = session('cart', []);
        $cleaned = [];

        foreach ($cart as $id => $item) {
            if (is_array($item) && isset($item['price'], $item['quantity'])) {
                $cleaned[$id] = $item;
            }
        }

        // إذا في بيانات قديمة، نظّف الجلسة
        if (count($cleaned) !== count($cart)) {
            session(['cart' => $cleaned]);
        }

        return $cleaned;
    }

    public function count(): int
    {
        return collect($this->all())->sum('quantity');
    }

    public function subtotal(): float
    {
        return collect($this->all())->sum(
            fn($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 0)
        );
    }

    public function increment(int $productId): void
    {
        $cart = $this->all();
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
            session(['cart' => $cart]);
        }
    }

    public function decrement(int $productId): void
    {
        $cart = $this->all();
        if (isset($cart[$productId])) {
            if ($cart[$productId]['quantity'] > 1) {
                $cart[$productId]['quantity']--;
            } else {
                unset($cart[$productId]);
            }
            session(['cart' => $cart]);
        }
    }

    public function remove(int $productId): void
    {
        $cart = $this->all();
        unset($cart[$productId]);
        session(['cart' => $cart]);
    }

    public function clear(): void
    {
        session()->forget('cart');
    }
}