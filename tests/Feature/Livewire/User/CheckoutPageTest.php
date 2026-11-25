<?php

namespace Tests\Feature\Livewire\User;

use App\Livewire\User\CheckoutPage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CheckoutPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirects_to_cart_if_checkout_is_accessed_with_an_empty_cart()
    {
        Livewire::test(CheckoutPage::class)
            ->assertRedirect('/cart');
    }

    public function test_validates_shipping_details_before_placing_order()
    {
        $product = Product::factory()->create(['price' => 100]);
        session(['cart' => [$product->id => ['name' => 'تيشيرت', 'price' => 100, 'quantity' => 1]]]);

        Livewire::test(CheckoutPage::class)
            ->set('name', '')
            ->set('phone', '123')
            ->set('city', '')
            ->set('address', 'قصير')
            ->call('placeOrder')
            ->assertHasErrors(['name', 'phone', 'city', 'address']);
    }

    public function test_creates_order_and_order_items_clears_session_and_redirects_on_valid_checkout()
    {
        $user = User::factory()->create(['name' => 'خضر أحمد']);
        $product = Product::factory()->create(['price' => 100]);

        session(['cart' => [
            $product->id => ['name' => $product->name, 'price' => 100, 'quantity' => 2]
        ]]);

        Livewire::actingAs($user)
            ->test(CheckoutPage::class)
            ->assertSet('name', 'خضر أحمد')
            ->set('phone', '0912345678')
            ->set('city', 'طرطوس')
            ->set('address', 'شارع الثورة - بناء رقم 5 - الطابق الثاني')
            ->set('payment_method', 'cod')
            ->call('placeOrder')
            ->assertHasNoErrors()
            ->assertDispatched('cartUpdated', totalCount: 0)
            ->assertRedirect('/order-success');

        $this->assertEquals(1, Order::count());
        $this->assertEquals(210.0, Order::first()->total);
        $this->assertTrue(OrderItem::where('product_id', $product->id)->where('quantity', 2)->exists());
        $this->assertFalse(session()->has('cart'));
    }
}