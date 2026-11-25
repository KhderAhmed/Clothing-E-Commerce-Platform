<?php

namespace App\Livewire\Admin\Products;

use App\Livewire\BaseComponent;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Size;
use App\Events\ProductUpdatedEvent;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class Form extends BaseComponent
{
    use WithFileUploads;

    public ?Product $product = null;

    public $name;
    public $description;
    public $price;
    public $discount;
    public $discount_expires_at;
    public $image;
    public $old_image;

    public $variants = [];

    public $variantColor;
    public $variantSize;
    public $variantSku;
    public $variantPrice;
    public $variantStock = 0;

    public function mount(?Product $product = null): void
    {
        if ($product && $product->exists) {
            $this->product = $product;
            $this->fillFromProduct($product);
        }
    }

    protected function view(): string
    {
        return 'livewire.admin.products.form';
    }

    public function with(): array
    {
        return [
            'colors' => Color::orderBy('name')->get(),
            'sizes'  => Size::orderBy('name')->get(),
            'isEditing' => (bool) $this->product,
        ];
    }

    protected function fillFromProduct(Product $product): void
    {
        $product->load(['variants.color', 'variants.size']);

        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->discount = $product->discount;

        $this->discount_expires_at = $product->discount_expires_at
            ? Carbon::parse($product->discount_expires_at)->format('Y-m-d')
            : null;

        $this->old_image = $product->image;

        $this->variants = $product->variants->map(fn($v) => [
            'id'         => $v->id,
            'color_id'   => $v->color_id,
            'color_name' => $v->color?->name,
            'size_id'    => $v->size_id,
            'size_name'  => $v->size?->name,
            'sku'        => $v->sku,
            'price'      => $v->price,
            'stock'      => $v->stock,
        ])->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'discount_expires_at' => 'nullable|date',
            'image' => 'nullable|image',
        ];
    }

    protected function variantRules(): array
    {
        return [
            'variantColor' => 'nullable|exists:colors,id',
            'variantSize' => 'nullable|exists:sizes,id',
            'variantSku' => ['required', 'string', 'max:100', 'unique:product_variants,sku'],
            'variantPrice' => 'nullable|numeric|min:0',
            'variantStock' => 'required|integer|min:0',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Variants Management
    |--------------------------------------------------------------------------
    */

    public function addVariant(): void
    {
        $this->validate($this->variantRules());

        foreach ($this->variants as $variant) {
            if (
                ($variant['color_id'] ?? null) == $this->variantColor &&
                ($variant['size_id'] ?? null) == $this->variantSize
            ) {
                $this->addError('variantColor', 'هذا اللون والقياس مضافان مسبقاً.');
                return;
            }
        }

        $this->variants[] = [
            'color_id' => $this->variantColor ?: null,
            'size_id'  => $this->variantSize ?: null,
            'sku'      => $this->variantSku,
            'price'    => ($this->variantPrice !== null && $this->variantPrice !== '')
                ? $this->variantPrice
                : null,
            'stock'    => (int) $this->variantStock,
        ];

        $this->resetVariantForm();
    }

    public function removeVariant($index): void
    {
        if (!isset($this->variants[$index])) return;

        if (!empty($this->variants[$index]['id'])) {
            ProductVariant::findOrFail($this->variants[$index]['id'])->delete();
        }

        unset($this->variants[$index]);
        $this->variants = array_values($this->variants);

        session()->flash('message', 'تم حذف الـ Variant.');
    }

    public function resetVariantForm(): void
    {
        $this->reset(['variantColor', 'variantSize', 'variantSku', 'variantPrice', 'variantStock']);
        $this->variantStock = 0;
        $this->resetValidation(['variantColor', 'variantSize', 'variantSku', 'variantPrice', 'variantStock']);
    }

    /*
    |--------------------------------------------------------------------------
    | Save
    |--------------------------------------------------------------------------
    */

    public function save()
    {
        $this->validate();

    // ✅ معالجة القيم الفارغة
    $discount = ($this->discount === '' || $this->discount === null || $this->discount === false)
        ? 0
        : (float) $this->discount;

    $data = [
        'name' => $this->name,
        'description' => $this->description,
        'price' => $this->price,
        'discount' => $discount,
        'discount_expires_at' => ($discount > 0 && $this->discount_expires_at)
            ? Carbon::parse($this->discount_expires_at)
            : null,
    ];

        if ($this->image) {
            $data['image'] = $this->image->store('products', 'public');
        }

        if ($this->product) {
            // Update
            if ($this->image && $this->product->image) {
                Storage::disk('public')->delete($this->product->image);
            }

            $this->product->update($data);
            $action = 'updated';
            $message = 'تم تحديث المنتج بنجاح.';

            foreach ($this->variants as $variant) {
                if (!empty($variant['id'])) continue;

                $this->product->variants()->create([
                    'color_id' => $variant['color_id'] ?? null,
                    'size_id'  => $variant['size_id'] ?? null,
                    'sku'      => $variant['sku'],
                    'price'    => $variant['price'] ?? null,
                    'stock'    => $variant['stock'],
                ]);
            }

            broadcast(new ProductUpdatedEvent($this->product, $action));
            session()->flash('message', $message);

            return $this->redirect(route('admin.products'), navigate: true);
        }

        // Create
        $data['status'] = 'draft';
        $data['created_by'] = auth()->id();

        $product = Product::create($data);

        foreach ($this->variants as $variant) {
            $product->variants()->create([
                'color_id' => $variant['color_id'] ?? null,
                'size_id'  => $variant['size_id'] ?? null,
                'sku'      => $variant['sku'],
                'price'    => $variant['price'] ?? null,
                'stock'    => $variant['stock'],
            ]);
        }

        broadcast(new ProductUpdatedEvent($product, 'created'));
        session()->flash('message', 'تمت إضافة المنتج بنجاح.');

        return $this->redirect(route('admin.products'), navigate: true);
    }
}
