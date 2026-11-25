<?php

namespace App\Livewire\User;

use App\Livewire\BaseComponent;
use App\Models\Color;
use App\Models\Size;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class DashboardUser extends BaseComponent
{
    /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    */

    public $status = 'all';

    public $search = '';

    public $minPrice = '';

    public $maxPrice = '';

    public $selectedColor = '';

    public $selectedSize = '';

    public $discountOnly = false;

    public $stockStatus = 'all';


    /*
    |--------------------------------------------------------------------------
    | Data
    |--------------------------------------------------------------------------
    */

    public $products = [];

    public $colors = [];

    public $sizes = [];


    /*
    |--------------------------------------------------------------------------
    | Livewire Events
    |--------------------------------------------------------------------------
    */

    protected $listeners = [
        'productUpdated' => 'refreshProducts',
    ];


    /*
    |--------------------------------------------------------------------------
    | Mount
    |--------------------------------------------------------------------------
    */

    public function mount()
    {
        $this->colors = Color::orderBy('name')->get();

        $this->sizes = Size::orderBy('name')->get();

        $this->loadProducts();
    }


    /*
    |--------------------------------------------------------------------------
    | Load Products
    |--------------------------------------------------------------------------
    */

    public function loadProducts()
    {
        $query = Product::query()
            ->select([
                'id',
                'name',
                'description',
                'price',
                'discount',
                'image',
                'status',
                'created_at',
                'updated_at',
            ])
            ->with([
                'creator:id,name',

                'variants:id,product_id,color_id,size_id,sku,price,stock',

                'variants.color:id,name,hex_code',

                'variants.size:id,name',
            ])
            ->where('status', 'published');


        /*
        |--------------------------------------------------------------------------
        | 2-A Advanced Search
        |--------------------------------------------------------------------------
        */

        if (filled($this->search)) {

            $search = trim($this->search);

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")

                    ->orWhere('description', 'like', "%{$search}%")

                    ->orWhereHas('variants', function ($variantQuery) use ($search) {

                        $variantQuery
                            ->where('sku', 'like', "%{$search}%")

                            ->orWhereHas('color', function ($colorQuery) use ($search) {
                                $colorQuery->where(
                                    'name',
                                    'like',
                                    "%{$search}%"
                                );
                            })

                            ->orWhereHas('size', function ($sizeQuery) use ($search) {
                                $sizeQuery->where(
                                    'name',
                                    'like',
                                    "%{$search}%"
                                );
                            });
                    });
            });
        }


        /*
        |--------------------------------------------------------------------------
        | 2-B Price Range
        |--------------------------------------------------------------------------
        */

        if ($this->minPrice !== '' && is_numeric($this->minPrice)) {

            $query->where(
                'price',
                '>=',
                (float) $this->minPrice
            );
        }

        if ($this->maxPrice !== '' && is_numeric($this->maxPrice)) {

            $query->where(
                'price',
                '<=',
                (float) $this->maxPrice
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 2-C Color + Size Filter
        |--------------------------------------------------------------------------
        */

        if (
            $this->selectedColor !== '' ||
            $this->selectedSize !== ''
        ) {

            $query->whereHas('variants', function ($variantQuery) {

                if ($this->selectedColor !== '') {

                    $variantQuery->where(
                        'color_id',
                        $this->selectedColor
                    );
                }

                if ($this->selectedSize !== '') {

                    $variantQuery->where(
                        'size_id',
                        $this->selectedSize
                    );
                }
            });
        }


        /*
        |--------------------------------------------------------------------------
        | 2-D Discount Filter
        |--------------------------------------------------------------------------
        */

        if ($this->discountOnly) {

            $query->where(
                'discount',
                '>',
                0
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 2-D Stock Filter
        |--------------------------------------------------------------------------
        */

        if ($this->stockStatus === 'in_stock') {

            $query->whereHas('variants', function ($variantQuery) {

                $variantQuery->where(
                    'stock',
                    '>',
                    0
                );
            });
        }

        if ($this->stockStatus === 'out_of_stock') {

            $query->whereDoesntHave('variants', function ($variantQuery) {

                $variantQuery->where(
                    'stock',
                    '>',
                    0
                );
            });
        }


        /*
        |--------------------------------------------------------------------------
        | Final Query
        |--------------------------------------------------------------------------
        */

        $this->products = $query
            ->latest()
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | Filter Updates
    |--------------------------------------------------------------------------
    */

    public function updatedSearch()
    {
        $this->loadProducts();
    }

    public function updatedMinPrice()
    {
        $this->loadProducts();
    }

    public function updatedMaxPrice()
    {
        $this->loadProducts();
    }

    public function updatedSelectedColor()
    {
        $this->loadProducts();
    }

    public function updatedSelectedSize()
    {
        $this->loadProducts();
    }

    public function updatedDiscountOnly()
    {
        $this->loadProducts();
    }

    public function updatedStockStatus()
    {
        $this->loadProducts();
    }

    public function updatedStatus()
    {
        $this->loadProducts();

        $this->dispatch(
            'productsUpdated',
            total: $this->products->count()
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Reverb / Real-Time Product Updates
    |--------------------------------------------------------------------------
    */

    public function refreshProducts()
    {
        $this->loadProducts();
    }


    /*
    |--------------------------------------------------------------------------
    | Add To Cart
    |--------------------------------------------------------------------------
    | ✨ FIXED: Handles old integer structure automatically
    */

    public function addToCart($productId)
    {
        if (!auth()->check()) {

            $this->dispatch(
                'showNotification',
                message: '⚠️ الرجاء تسجيل الدخول أولاً',
                type: 'warning'
            );

            return;
        }

        $product = Product::findOrFail($productId);

        $cart = session()->get('cart', []);

        /*
        |--------------------------------------------------------------------------
        | ✨ FIX: Clean up any old integer entries
        |--------------------------------------------------------------------------
        | This handles the conflict with ClothingAssistant that used to store
        | integers instead of arrays.
        */

        foreach ($cart as $id => $item) {
            if (!is_array($item)) {
                unset($cart[$id]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Add or increment
        |--------------------------------------------------------------------------
        */

        if (isset($cart[$productId]) && is_array($cart[$productId])) {

            $cart[$productId]['quantity']++;

        } else {

            $cart[$productId] = [
                'name'           => $product->name,
                'quantity'       => 1,
                'price'          => $product->final_price ?? $product->price,
                'original_price' => $product->price,
                'discount'       => $product->discount ?? 0,
                'image'          => $product->image ?? null,
            ];
        }


        session()->put('cart', $cart);


        $totalCount = collect($cart)->sum('quantity');


        /*
        |--------------------------------------------------------------------------
        | Cart Event
        |--------------------------------------------------------------------------
        */

        $this->dispatch(
            'cartUpdated',
            totalCount: $totalCount
        );


        /*
        |--------------------------------------------------------------------------
        | Notification
        |--------------------------------------------------------------------------
        */

        $this->dispatch(
            'showNotification',
            message: '🛒 تمت إضافة ' . $product->name . ' إلى السلة بنجاح',
            type: 'success'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Wishlist
    |--------------------------------------------------------------------------
    */

    public function toggleWishlist($productId)
    {
        if (!auth()->check()) {

            $this->dispatch(
                'showNotification',
                message: '⚠️ الرجاء تسجيل الدخول أولاً',
                type: 'warning'
            );

            return;
        }


        $userId = Auth::id();


        $existing = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();


        if ($existing) {

            $existing->delete();

            $message = '❤️ تم إزالة المنتج من المفضلة';

        } else {

            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);

            $message = '💖 تم إضافة المنتج إلى المفضلة';
        }


        /*
        |--------------------------------------------------------------------------
        | Clear Favorites Cache
        |--------------------------------------------------------------------------
        */

        cache()->forget(
            'user_favorites_' . $userId
        );


        /*
        |--------------------------------------------------------------------------
        | Refresh Products
        |--------------------------------------------------------------------------
        */

        $this->loadProducts();


        /*
        |--------------------------------------------------------------------------
        | Notification
        |--------------------------------------------------------------------------
        */

        $this->dispatch(
            'showNotification',
            message: $message,
            type: 'success'
        );


        /*
        |--------------------------------------------------------------------------
        | Update Wishlist Counter
        |--------------------------------------------------------------------------
        */

        $favoritesCount = Wishlist::where(
            'user_id',
            $userId
        )->count();


        $this->dispatch(
            'wishlistCountUpdated',
            count: $favoritesCount
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Check Wishlist Status
    |--------------------------------------------------------------------------
    */

    public function isFavorited($productId)
    {
        if (!auth()->check()) {
            return false;
        }


        $favorites = cache()->remember(
            'user_favorites_' . auth()->id(),
            300,
            function () {

                return Wishlist::where(
                    'user_id',
                    auth()->id()
                )
                    ->pluck('product_id')
                    ->toArray();
            }
        );


        return in_array(
            $productId,
            $favorites
        );
    }


    /*
    |--------------------------------------------------------------------------
    | View
    |--------------------------------------------------------------------------
    */

    protected function view(): string
    {
        return 'livewire.user.dashboarduser';
    }
}