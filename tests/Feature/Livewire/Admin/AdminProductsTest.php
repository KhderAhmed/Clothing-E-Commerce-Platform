<?php

namespace Tests\Feature\Livewire\Admin\Products;

use App\Livewire\Admin\Products\Index;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminProductsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_search_and_filter_products_in_admin_panel()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $creator = User::factory()->create();

        Product::factory()->create([
            'name' => 'بنطال جينز رجالي',
            'status' => 'published',
            'created_by' => $creator->id,
        ]);

        Product::factory()->create([
            'name' => 'تيشيرت صيفي',
            'status' => 'draft',
            'created_by' => $creator->id,
        ]);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->assertViewHas('products', fn($products) => $products->count() === 2)
            ->set('statusFilter', 'published')
            ->assertViewHas('products', fn($products) => $products->count() === 1)
            ->set('search', 'جينز')
            ->assertViewHas('products', fn($products) => $products->first()->name === 'بنطال جينز رجالي');
    }

    public function test_admin_can_publish_and_delete_a_product()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create(['status' => 'draft']);

        $component = Livewire::actingAs($admin)->test(Index::class);

        // نشر المنتج
        $component->call('publish', $product->id)
            ->assertDispatched('product-updated');
        
        $this->assertEquals('published', $product->fresh()->status);

        // حذف المنتج
        $component->call('delete', $product->id)
            ->assertDispatched('product-updated');

        $this->assertNull(Product::find($product->id));
    }
}