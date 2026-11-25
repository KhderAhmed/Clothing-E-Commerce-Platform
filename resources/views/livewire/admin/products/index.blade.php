<div class="space-y-6 antialiased selection:bg-[#f53003]/10 selection:text-[#f53003]">

    {{-- ══════════════════════════════════════════════════════════
         الترويسة + زر إضافة منتج
    ═══════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">

        <div>
            <h1 class="text-xl font-black text-zinc-950 dark:text-white tracking-tight">
                المنتجات
            </h1>
            <p class="text-xs text-zinc-400 mt-1">
                {{ $products->total() }} منتج في المنصة
            </p>
        </div>

        <a href="{{ route('admin.products.create') }}" wire:navigate
            class="inline-flex items-center gap-2 bg-[#f53003] hover:bg-[#d42902] text-white font-bold px-4 py-2.5 rounded-lg text-xs transition-colors shadow-sm">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            إضافة منتج
        </a>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         رسالة النجاح
    ═══════════════════════════════════════════════════════════ --}}
    @if (session()->has('message'))
        <div
            class="p-3.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 rounded-lg text-xs font-bold flex items-center gap-2">
            <span>✅</span> {{ session('message') }}
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════
         الفلترة + البحث
    ═══════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">

        {{-- Tabs --}}
        <div
            class="bg-zinc-100 dark:bg-zinc-900/80 p-1 rounded-xl inline-flex gap-1 border border-zinc-200/60 dark:border-zinc-800/60 shadow-xs">
            <button wire:click="$set('statusFilter', 'all')"
                class="px-4 py-1.5 rounded-lg font-bold text-xs transition-all cursor-pointer {{ $statusFilter == 'all' ? 'bg-white dark:bg-black text-zinc-950 dark:text-white shadow-xs border border-zinc-200/40 dark:border-zinc-800/40' : 'text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200' }}">
                الكل
            </button>
            <button wire:click="$set('statusFilter', 'draft')"
                class="px-4 py-1.5 rounded-lg font-bold text-xs transition-all cursor-pointer {{ $statusFilter == 'draft' ? 'bg-amber-500/10 text-amber-600 dark:text-amber-400' : 'text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200' }}">
                المسودات
            </button>
            <button wire:click="$set('statusFilter', 'published')"
                class="px-4 py-1.5 rounded-lg font-bold text-xs transition-all cursor-pointer {{ $statusFilter == 'published' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200' }}">
                المنشورات
            </button>
        </div>

        {{-- البحث --}}
        <div class="relative w-full sm:w-72">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="ابحث عن منتج..."
                class="w-full bg-white dark:bg-zinc-900 text-xs text-zinc-900 dark:text-zinc-100 pr-9 pl-3 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-800 focus:outline-none focus:border-[#f53003] transition-all placeholder-zinc-400">
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-zinc-400">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         الجدول
    ═══════════════════════════════════════════════════════════ --}}
    <div
        class="bg-white dark:bg-[#09090b] rounded-xl border border-zinc-200 dark:border-zinc-850 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800 text-right">
                <thead class="bg-zinc-50 dark:bg-zinc-900/40">
                    <tr>
                        <th
                            class="px-6 py-3.5 text-[10px] font-black text-zinc-400 dark:text-zinc-500 uppercase tracking-wider">
                            تفاصيل السلعة</th>
                        <th
                            class="px-6 py-3.5 text-[10px] font-black text-zinc-400 dark:text-zinc-500 uppercase tracking-wider">
                            السعر</th>
                        <th
                            class="px-6 py-3.5 text-[10px] font-black text-zinc-400 dark:text-zinc-500 uppercase tracking-wider">
                            الخصم</th>
                        <th
                            class="px-6 py-3.5 text-[10px] font-black text-zinc-400 dark:text-zinc-500 uppercase tracking-wider">
                            الحالة</th>
                        <th
                            class="px-6 py-3.5 text-[10px] font-black text-zinc-400 dark:text-zinc-500 uppercase tracking-wider">
                            المنشئ</th>
                        <th
                            class="px-6 py-3.5 text-[10px] font-black text-zinc-400 dark:text-zinc-500 uppercase tracking-wider text-left">
                            إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-900/60">
                    @forelse ($products as $product)
                        <tr wire:key="product-{{ $product->id }}"
                            class="hover:bg-zinc-50/50 dark:hover:bg-zinc-900/20 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-10 w-10 rounded-lg bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 overflow-hidden flex-shrink-0">
                                        @if ($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                class="object-cover h-full w-full">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-zinc-50 dark:bg-zinc-900 text-zinc-400 text-sm">
                                                📦</div>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-zinc-950 dark:text-white text-xs mb-0.5 truncate">
                                            {{ $product->name }}</div>
                                        <div class="text-[11px] text-zinc-400 dark:text-zinc-500 max-w-xs truncate">
                                            {{ $product->description ?? 'لا يوجد وصف' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($product->is_discounted)
                                    <div class="flex flex-col">
                                        <span class="text-[9px] text-zinc-400 line-through font-medium">
                                            {{ number_format($product->price, 2) }} د.ل</span>
                                        <span class="font-black text-emerald-600 dark:text-emerald-500 text-xs mt-0.5">
                                            {{ number_format($product->final_price, 2) }} د.ل</span>
                                    </div>
                                @else
                                    <span class="font-black text-zinc-950 dark:text-white text-xs">
                                        {{ number_format($product->price, 2) }} د.ل</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($product->is_discounted)
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 bg-red-500/10 text-red-600 dark:text-red-400 rounded-md text-[10px] font-bold">
                                        🔥 {{ $product->discount_percentage }}%
                                    </span>
                                @else
                                    <span class="text-[10px] text-zinc-400 dark:text-zinc-600">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 py-0.5 inline-flex text-[10px] font-bold rounded-md {{ $product->status == 'published' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-amber-500/10 text-amber-600 dark:text-amber-400' }}">
                                    {{ $product->status == 'published' ? 'منشور' : 'مسودة' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="h-5 w-5 rounded-md bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 flex items-center justify-center text-[9px] font-black text-zinc-500 dark:text-zinc-400 uppercase">
                                        {{ \Illuminate\Support\Str::substr($product->creator?->name ?? 'غ', 0, 1) }}
                                    </div>
                                    <span class="text-[11px] font-bold text-zinc-600 dark:text-zinc-400">
                                        {{ $product->creator?->name ?? 'غير معروف' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-left space-x-1 space-x-reverse">

                                @if ($product->status == 'draft')
                                    <button wire:click="publish({{ $product->id }})"
                                        wire:confirm="هل تريد نشر هذا المنتج؟"
                                        class="px-2.5 py-1 bg-zinc-50 dark:bg-zinc-900 hover:bg-emerald-500 dark:hover:bg-emerald-600 text-zinc-600 dark:text-zinc-400 hover:text-white dark:hover:text-white rounded border border-zinc-200 dark:border-zinc-800 cursor-pointer text-[10px] font-bold transition-all">
                                        نشر
                                    </button>
                                @endif

                                <a href="{{ route('admin.products.edit', $product->id) }}" wire:navigate
                                    class="inline-block px-2.5 py-1 bg-zinc-50 dark:bg-zinc-900 hover:bg-zinc-950 dark:hover:bg-white text-zinc-600 dark:text-zinc-400 hover:text-white dark:hover:text-black rounded border border-zinc-200 dark:border-zinc-800 cursor-pointer text-[10px] font-bold transition-all">
                                    تعديل
                                </a>

                                <button wire:click="delete({{ $product->id }})"
                                    wire:confirm="هل تريد بالتأكيد إزالة هذا المنتج؟"
                                    class="px-2.5 py-1 bg-zinc-50 dark:bg-zinc-900 hover:bg-[#f53003] text-[#f53003] hover:text-white rounded border border-zinc-200 dark:border-zinc-800 cursor-pointer text-[10px] font-bold transition-all">
                                    حذف
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-xs text-zinc-400 dark:text-zinc-500 font-medium">
                                📭 لا توجد منتجات في هذه القائمة.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="pt-2">
        {{ $products->links() }}
    </div>

</div>