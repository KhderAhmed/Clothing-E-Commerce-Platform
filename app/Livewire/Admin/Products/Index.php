<?php

namespace App\Livewire\Admin\Products;

use App\Livewire\BaseComponent;
use App\Models\Product;
use App\Events\ProductUpdatedEvent;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class Index extends BaseComponent
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = 'all';

    protected function view(): string
    {
        return 'livewire.admin.products.index';
    }

    public function with(): array
    {
        $query = Product::with('creator');

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if (trim($this->search) !== '') {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        return [
            'products' => $query->latest()->paginate(10),
        ];
    }

    public function updatingSearch()       { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }

    public function publish($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['status' => 'published']);

        broadcast(new ProductUpdatedEvent($product, 'published'));

        session()->flash('message', 'تم نشر المنتج بنجاح.');
        $this->dispatch('product-updated');
    }

    public function delete($id)
    {
        $product = Product::with('variants')->findOrFail($id);
        $productName = $product->name;

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->variants()->delete();
        $product->delete();

        broadcast(new ProductUpdatedEvent(null, 'deleted', $productName));

        session()->flash('message', 'تم حذف المنتج بنجاح.');
        $this->dispatch('product-updated');
    }

    #[On('refreshProducts')]
    public function refreshProducts()
    {
        $this->resetPage();
        $this->dispatch('$refresh');
    }

    #[On('echo:products,ProductUpdatedEvent')]
    public function onProductUpdated($payload)
    {
        logger('📦 تم استقبال حدث Reverb', $payload);
        $this->resetPage();
        $this->dispatch('$refresh');
    }
}