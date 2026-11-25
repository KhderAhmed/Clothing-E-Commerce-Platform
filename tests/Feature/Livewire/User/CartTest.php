<?php

namespace Tests\Feature\Livewire\User;

use App\Livewire\User\CartCounter;
use App\Livewire\User\CartPage;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_displays_initial_cart_count_from_session_and_updates_in_real_time()
    {
        session(['cart' => [
            1 => ['name' => 'قميص', 'price' => 100, 'quantity' => 2],
            2 => ['name' => 'بنطال', 'price' => 150, 'quantity' => 1],
        ]]);

        Livewire::test(CartCounter::class)
            ->assertSet('cartCount', 3)
            ->dispatch('cartUpdated', totalCount: 5)
            ->assertSet('cartCount', 5);
    }

    public function test_calculates_subtotal_tax_and_total_accurately_on_cart_page()
    {
        $product = Product::factory()->create(['name' => 'سترة شتوية', 'price' => 200]);

        session(['cart' => [
            $product->id => [
                'name' => $product->name,
                'price' => 200,
                'quantity' => 2,
            ]
        ]]);

        Livewire::test(CartPage::class)
            ->assertSet('subtotal', 400.0)
            ->assertSet('tax', 20.0) // 5%
            ->assertSet('total', 420.0);
    }

    public function test_can_increment_decrement_remove_item_and_clear_cart()
    {
        $product = Product::factory()->create(['name' => 'حذاء رياضي', 'price' => 100]);

        session(['cart' => [
            $product->id => [
                'name' => $product->name,
                'price' => 100,
                'quantity' => 1,
            ]
        ]]);

        $component = Livewire::test(CartPage::class);

        $component->call('incrementQuantity', $product->id)
            ->assertSet('subtotal', 200.0)
            ->assertDispatched('cartUpdated', totalCount: 2);

        $component->call('decrementQuantity', $product->id)
            ->assertSet('subtotal', 100.0)
            ->assertDispatched('cartUpdated', totalCount: 1);

        $component->call('removeItem', $product->id)
            ->assertSet('cart', [])
            ->assertDispatched('cartUpdated', totalCount: 0)
            ->assertDispatched('showNotification');
    }
}