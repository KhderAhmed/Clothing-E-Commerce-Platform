<?php

namespace Tests\Feature\Livewire\Admin\Products;

use App\Livewire\Admin\Products\Form;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProductFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_new_product_with_variants()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Livewire::actingAs($admin)
            ->test(Form::class)
            ->set('name', 'قميص رسمي جديد')
            ->set('description', 'وصف تفصيلي للقميص الرسمي')
            ->set('price', 150.00)
            ->set('discount', 10)
            ->set('variantSku', 'SHIRT-OFF-01')
            ->set('variantStock', 25)
            ->call('addVariant')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('admin.products'));

        $product = Product::where('name', 'قميص رسمي جديد')->first();

        $this->assertNotNull($product);
        $this->assertEquals(150.00, $product->price);
        $this->assertCount(1, $product->variants);
    }
}