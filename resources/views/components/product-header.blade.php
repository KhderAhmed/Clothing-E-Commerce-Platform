@props(['products', 'status', 'colors', 'sizes'])

<div class="flex items-center gap-3 flex-wrap">

    {{-- Search --}}
    <div class="relative">
        <input type="text" wire:model.live.debounce.400ms="search" placeholder="ابحث عن منتج، SKU، لون أو مقاس..."
            class="w-64 text-xs font-medium bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl pl-9 pr-3 py-2.5 text-zinc-700 dark:text-zinc-300 placeholder:text-zinc-400 focus:outline-none focus:border-[#f53003] transition-all">

        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-400" fill="none" stroke="currentColor"
            stroke-width="2" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="8"></circle>
            <path d="m21 21-4.35-4.35"></path>
        </svg>

        <div wire:loading wire:target="search" class="absolute right-3 top-1/2 -translate-y-1/2">
            <span
                class="block w-3.5 h-3.5 border-2 border-[#f53003] border-t-transparent rounded-full animate-spin"></span>
        </div>
    </div>

    {{-- Price Range --}}
    <div class="flex items-center gap-2">

        <input type="number" wire:model.live.debounce.400ms="minPrice" min="0" placeholder="من"
            class="w-20 text-xs font-medium bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-zinc-700 dark:text-zinc-300 placeholder:text-zinc-400 focus:outline-none focus:border-[#f53003]">

        <span class="text-zinc-400 text-xs">—</span>

        <input type="number" wire:model.live.debounce.400ms="maxPrice" min="0" placeholder="إلى"
            class="w-20 text-xs font-medium bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-zinc-700 dark:text-zinc-300 placeholder:text-zinc-400 focus:outline-none focus:border-[#f53003]">

        <span class="text-[10px] text-zinc-400">د.إ</span>

    </div>

    {{-- Color Filter --}}
    <select wire:model.live="selectedColor"
        class="text-xs font-bold bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-zinc-700 dark:text-zinc-300 focus:outline-none focus:border-[#f53003] cursor-pointer">
        <option value="">🎨 كل الألوان</option>

        @foreach ($colors as $color)
            <option value="{{ $color->id }}">
                {{ $color->name }}
            </option>
        @endforeach
    </select>


    {{-- Size Filter --}}
    <select wire:model.live="selectedSize"
        class="text-xs font-bold bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-zinc-700 dark:text-zinc-300 focus:outline-none focus:border-[#f53003] cursor-pointer">
        <option value="">📏 كل المقاسات</option>

        @foreach ($sizes as $size)
            <option value="{{ $size->id }}">
                {{ $size->name }}
            </option>
        @endforeach
    </select>

    {{-- Discount Filter --}}
    <label class="flex items-center gap-2 text-xs font-bold text-zinc-700 dark:text-zinc-300 cursor-pointer">
        <input type="checkbox" wire:model.live="discountOnly"
            class="rounded border-zinc-300 text-[#f53003] focus:ring-[#f53003]">
        🔥 خصومات فقط
    </label>

    {{-- Stock Filter --}}
    <select wire:model.live="stockStatus"
        class="text-xs font-bold bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-zinc-700 dark:text-zinc-300 focus:outline-none focus:border-[#f53003] cursor-pointer">
        <option value="all">📦 كل المنتجات</option>
        <option value="in_stock">✅ متوفر فقط</option>
        <option value="out_of_stock">❌ غير متوفر</option>
    </select>
    {{-- Status --}}
    <select wire:model.live="status"
        class="text-xs font-bold bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-zinc-700 dark:text-zinc-300 focus:outline-none focus:border-[#f53003] cursor-pointer">
        <option value="all">📋 الكل</option>
        <option value="published">✅ منشور</option>
        <option value="draft">📝 مسودة</option>
    </select>

    {{-- Products Count --}}
    <span
        class="text-xs font-medium text-zinc-400 bg-zinc-100 dark:bg-zinc-900 px-3 py-2.5 rounded-xl border border-zinc-200/40 dark:border-zinc-800/40">
        {{ $products->count() }} منتج
    </span>

<div>
<div class="relative h-0.5 my-12 rounded-full bg-gradient-to-r from-transparent via-indigo-500 to-transparent">
  <div class="absolute inset-0 -inset-x-4 blur-lg opacity-60 bg-gradient-to-r from-transparent via-indigo-500 to-transparent"></div>
</div>
</div>

</div>

