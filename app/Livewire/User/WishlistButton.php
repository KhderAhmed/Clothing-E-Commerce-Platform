<?php

namespace App\Livewire\User;

use App\Models\Wishlist;
use Livewire\Component;

class WishlistButton extends Component
{
    public $productId;

    public $isFavorited = false;


    /*
    |--------------------------------------------------------------------------
    | Mount
    |--------------------------------------------------------------------------
    */

    public function mount($productId)
    {
        $this->productId = $productId;

        $this->checkIfFavorited();
    }


    /*
    |--------------------------------------------------------------------------
    | Check Wishlist
    |--------------------------------------------------------------------------
    */

    public function checkIfFavorited()
    {
        if (!auth()->check()) {
            $this->isFavorited = false;
            return;
        }

        $this->isFavorited = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $this->productId)
            ->exists();
    }


    /*
    |--------------------------------------------------------------------------
    | Toggle Wishlist
    |--------------------------------------------------------------------------
    */

    public function toggle()
    {
        /*
        |--------------------------------------------------------------------------
        | Authentication
        |--------------------------------------------------------------------------
        */

        if (!auth()->check()) {

            $this->dispatch(
                'showNotification',
                message: '⚠️ الرجاء تسجيل الدخول أولاً',
                type: 'warning'
            );

            return;
        }


        $userId = auth()->id();


        /*
        |--------------------------------------------------------------------------
        | Add / Remove
        |--------------------------------------------------------------------------
        */

        if ($this->isFavorited) {

            Wishlist::where('user_id', $userId)
                ->where('product_id', $this->productId)
                ->delete();

            $this->isFavorited = false;

            $message = '❤️ تم إزالة المنتج من المفضلة';

        } else {

            Wishlist::firstOrCreate([
                'user_id' => $userId,
                'product_id' => $this->productId,
            ]);

            $this->isFavorited = true;

            $message = '💖 تم إضافة المنتج إلى المفضلة';
        }


        /*
        |--------------------------------------------------------------------------
        | Clear Wishlist Cache
        |--------------------------------------------------------------------------
        */

        cache()->forget(
            'user_favorites_' . $userId
        );


        /*
        |--------------------------------------------------------------------------
        | Wishlist Counter
        |--------------------------------------------------------------------------
        */

        $count = Wishlist::where(
            'user_id',
            $userId
        )->count();


        $this->dispatch(
            'wishlistCountUpdated',
            count: $count
        );


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
        | Notify Other Livewire Components
        |--------------------------------------------------------------------------
        |
        | Keep this event because other components may depend on it.
        |
        */

        $this->dispatch(
            'wishlistToggled',
            productId: $this->productId,
            isFavorited: $this->isFavorited
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        return view('livewire.user.wishlist-button')
            ->layout('layouts.user');
    }
}