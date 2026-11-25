<?php

namespace Tests\Feature\Livewire\User;

use App\Livewire\User\DashboardUser;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_filters_published_products_by_search_price_range_and_discount()
    {
        $creator = User::factory()->create();
        
        Product::factory()->create([
            'name' => 'جاكيت جلد فاخر',
            'price' => 500,
            'discount' => 20,
            'status' => 'published',
            'created_by' => $creator->id,
        ]);

        Product::factory()->create([
            'name' => 'قميص قطني',
            'price' => 100,
            'discount' => 0,
            'status' => 'published',
            'created_by' => $creator->id,
        ]);

        Product::factory()->create([
            'name' => 'مسودة منتج',
            'price' => 300,
            'status' => 'draft',
            'created_by' => $creator->id,
        ]);

        $component = Livewire::test(DashboardUser::class);
        $this->assertCount(2, $component->get('products'));

        $component->set('search', 'جاكيت');
        $this->assertCount(1, $component->get('products'));

        $component->set('search', '')->set('discountOnly', true);
        $this->assertCount(1, $component->get('products'));

        $component->set('discountOnly', false)->set('minPrice', 50)->set('maxPrice', 150);
        $this->assertCount(1, $component->get('products'));
    }

    public function test_prevents_guests_from_adding_to_cart_or_toggling_wishlist()
    {
        $product = Product::factory()->create(['status' => 'published']);

        Livewire::test(DashboardUser::class)
            ->call('addToCart', $product->id)
            ->assertDispatched('showNotification', type: 'warning')
            ->call('toggleWishlist', $product->id)
            ->assertDispatched('showNotification', type: 'warning');

        $this->assertEquals(0, Wishlist::count());
    }

    public function test_allows_authenticated_user_to_add_product_to_cart_and_toggle_wishlist()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['name' => 'ساعة يد', 'price' => 250, 'status' => 'published']);

        Livewire::actingAs($user)
            ->test(DashboardUser::class)
            ->call('addToCart', $product->id)
            ->assertDispatched('cartUpdated', totalCount: 1)
            ->call('toggleWishlist', $product->id)
            ->assertDispatched('wishlistCountUpdated', count: 1);

        $this->assertEquals(1, session('cart')[$product->id]['quantity']);
        $this->assertTrue(Wishlist::where('user_id', $user->id)->where('product_id', $product->id)->exists());
    }
}