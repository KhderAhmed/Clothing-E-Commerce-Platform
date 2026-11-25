<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\On;

class CartCounter extends Component
{
    public $cartCount = 0;

    public function mount()
    {
        // جلب العدد الأولي عند تحميل الصفحة أول مرة
        $cart = session()->get('cart', []);
        $this->cartCount = collect($cart)->sum('quantity');
    }

    /**
     * 🔥 الاستماع الفوري بالوقت الفعلي لحدث تحديث السلة
     */
    #[On('cartUpdated')]
    public function updateCount($totalCount)
    {
        $this->cartCount = $totalCount;
    }

    public function render()
    {
        return <<<'HTML'
        <div class="relative inline-flex items-center">
            <svg class="w-5 h-5 text-zinc-900 dark:text-zinc-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"></path>
            </svg>
            
            @if($cartCount > 0)
                <span class="absolute -top-1.5 -right-1.5 bg-[#f53003] text-white text-[10px] font-black w-4 h-4 rounded-full flex items-center justify-center animate-pulse">
                    {{ $cartCount }}
                </span>
            @endif
        </div>
        HTML;
    }
}