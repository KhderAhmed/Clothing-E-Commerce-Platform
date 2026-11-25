@props(['product'])

<div class="group bg-white dark:bg-[#121215] rounded-2xl border border-zinc-200/80 dark:border-zinc-800/80 overflow-hidden flex flex-col hover:border-zinc-300 dark:hover:border-zinc-700 transition-all duration-300 relative">

    @if ($product->is_discounted)
        <span class="absolute top-3.5 right-3.5 z-10 bg-gradient-to-r from-red-600 to-red-500 text-white text-[10px] font-black px-2.5 py-1 rounded-lg shadow-md animate-pulse">
            🔥 خصم {{ $product->discount_percentage }}%
        </span>
    @endif

    @livewire('user.wishlist-button', ['productId' => $product->id], key('wishlist-' . $product->id))

    <div class="relative aspect-square bg-gradient-to-br from-zinc-50 to-zinc-100 dark:from-zinc-900/20 dark:to-zinc-900/40 p-6 flex items-center justify-center border-b border-zinc-100 dark:border-zinc-900/50">
        @if ($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                class="max-w-[85%] max-h-[85%] object-contain group-hover:scale-105 transition-transform duration-500 ease-out">
        @else
            <div class="w-full h-full flex flex-col items-center justify-center bg-zinc-50 dark:bg-zinc-900 text-zinc-400 p-4">
                <span class="text-2xl mb-1">📦</span>
                <span class="text-[10px] font-bold text-zinc-400">لا توجد صورة</span>
            </div>
        @endif
    </div>

    <div class="p-4 flex flex-col flex-grow">
        <div class="flex items-center justify-between mb-3">
            @livewire('user.rating-stars', ['productId' => $product->id], key('rating-' . $product->id))
        </div>

        <div class="mb-3 flex-grow">
            <h3 class="font-bold text-zinc-950 dark:text-white text-sm line-clamp-2 leading-snug group-hover:text-[#f53003] transition-colors mb-1">
                {{ $product->name }}
            </h3>
            <p class="text-[11px] text-zinc-400 dark:text-zinc-500 line-clamp-2 leading-relaxed">
                {{ $product->description ?? 'شامل الضمان الذهبي والشحن المجاني السريع حتى باب المنزل.' }}
            </p>
     
@if ($product->variants->count() > 0)
    <div class="mb-4 space-y-2">

        {{-- Colors --}}
        @php
            $colors = $product->variants
                ->whereNotNull('color_id')
                ->pluck('color')
                ->filter()
                ->unique('id');
        @endphp

        @if ($colors->count() > 0)
            <div>
                <p class="text-[10px] font-bold text-zinc-500 dark:text-zinc-400 mb-1.5">
                    🎨 اللون
                </p>

                <div class="flex flex-wrap gap-1.5">
                    @foreach ($colors as $color)
                        <span
                            title="{{ $color->name }}"
                            class="w-6 h-6 rounded-full border-2 border-white dark:border-zinc-800 shadow-sm ring-1 ring-zinc-200 dark:ring-zinc-700"
                            style="background-color: {{ $color->hex_code }}">
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Sizes --}}
        @php
            $sizes = $product->variants
                ->whereNotNull('size_id')
                ->pluck('size')
                ->filter()
                ->unique('id');
        @endphp

        @if ($sizes->count() > 0)
            <div>
                <p class="text-[10px] font-bold text-zinc-500 dark:text-zinc-400 mb-1.5">
                    📏 المقاسات
                </p>

                <div class="flex flex-wrap gap-1.5">
                    @foreach ($sizes as $size)
                        <span
                            class="px-2.5 py-1 rounded-lg bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 text-[10px] font-bold text-zinc-700 dark:text-zinc-300">
                            {{ $size->name }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Variants count --}}
        <div class="flex items-center justify-between pt-1">
            <span class="text-[9px] text-zinc-400">
                🧩 {{ $product->variants->count() }} خيارات متاحة
            </span>

            @if ($product->variants->sum('stock') > 0)
                <span class="text-[9px] font-bold text-green-600 dark:text-green-400">
                    ✓ متوفر
                </span>
            @else
                <span class="text-[9px] font-bold text-red-500">
                    ✕ غير متوفر
                </span>
            @endif
        </div>

    </div>
@endif
   </div>
        <div class="flex items-baseline gap-2 mb-4 border-t border-zinc-100 dark:border-zinc-800 pt-3">
            <span class="text-lg font-black text-zinc-950 dark:text-white">
                {{ number_format($product->final_price, 2) }}
                <span class="text-[10px] font-normal text-zinc-400">د.إ</span>
            </span>
            @if ($product->is_discounted)
                <span class="text-xs text-zinc-400 line-through">
                    {{ number_format($product->price, 2) }} د.إ
                </span>
            @endif
        </div>

        <button wire:click="addToCart({{ $product->id }})" wire:loading.attr="disabled" type="button"
            class="w-full bg-gradient-to-r from-zinc-950 to-zinc-800 hover:from-[#f53003] hover:to-[#d42802] dark:from-zinc-800 dark:to-zinc-900 dark:hover:from-[#f53003] dark:hover:to-[#d42802] text-white font-bold py-2.5 px-4 rounded-xl text-xs flex items-center justify-center gap-2 transition-all duration-200 cursor-pointer active:scale-[0.98] disabled:opacity-60 disabled:cursor-not-allowed">

            <svg wire:loading.remove wire:target="addToCart({{ $product->id }})" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>

            <span wire:loading wire:target="addToCart({{ $product->id }})" class="w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
            <span>إضافة إلى السلة</span>
        </button>
    </div>
</div>