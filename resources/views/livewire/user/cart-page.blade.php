<div class="max-w-6xl mx-auto px-4 py-8 text-right" dir="rtl">
    
    <div class="border-b-2 border-zinc-300 dark:border-zinc-800 pb-5 mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">سلة التسوق</h1>
            <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">راجع المنتجات التي قمت باختيارها قبل إتمام الطلب.</p>
        </div>
        @if(count($cart) > 0)
            <button wire:click="clearCart" class="text-xs font-bold text-red-600 hover:text-red-700 bg-red-50 dark:bg-red-950/20 px-3 py-2 rounded-xl border border-red-200 dark:border-red-900 transition-all cursor-pointer">
                تفريغ السلة
            </button>
        @endif
    </div>

    @if(count($cart) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <div class="lg:col-span-2 space-y-4">
                @foreach($cart as $id => $item)
                    <div class="bg-white dark:bg-[#09090B] border-2 border-zinc-300 dark:border-zinc-800 rounded-2xl p-4 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm transition-all">
                        
                        <div class="flex items-center gap-4 w-full sm:w-auto">
                            <div class="w-20 h-20 bg-zinc-100 dark:bg-zinc-900 rounded-xl overflow-hidden shrink-0 border border-zinc-300 dark:border-zinc-800 flex items-center justify-center">
                                @if(isset($item['image']))
                                    <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-8 h-8 text-zinc-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.008 1.24l.885 1.77a2.25 2.25 0 002.007 1.24h1.98a2.25 2.25 0 002.007-1.24l.885-1.77a2.25 2.25 0 012.007-1.24h3.86m-18 0h18a2.25 2.25 0 012.25 2.25v4.5A2.25 2.25 0 0118 21H6a2.25 2.25 0 01-2.25-2.25v-4.5a2.25 2.25 0 012.25-2.25zM6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h8.25c.621 0 1.125.504 1.125 1.125V21M3 3h18v3.75H3V3z"></path></svg>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-zinc-900 dark:text-white">{{ $item['name'] }}</h3>
                                <p class="text-xs font-black text-[#f53003] mt-1">{{ number_format($item['price'], 2) }} د.إ</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto border-t sm:border-t-0 pt-3 sm:pt-0 border-zinc-200">
                            
                            <div class="flex items-center border-2 border-zinc-400 dark:border-zinc-700 rounded-xl bg-zinc-50 dark:bg-zinc-900 overflow-hidden">
                                <button wire:click="decrementQuantity({{ $id }})" wire:loading.attr="disabled" type="button" class="px-3 py-1.5 hover:bg-zinc-200 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-300 font-bold transition-colors cursor-pointer disabled:opacity-50">
                                    -
                                </button>
                                <span class="px-3 text-xs font-black text-zinc-900 dark:text-white min-w-[24px] text-center">
                                    {{ $item['quantity'] }}
                                </span>
                                <button wire:click="incrementQuantity({{ $id }})" wire:loading.attr="disabled" type="button" class="px-3 py-1.5 hover:bg-zinc-200 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-300 font-bold transition-colors cursor-pointer disabled:opacity-50">
                                    +
                                </button>
                            </div>

                            <div class="text-left sm:text-right min-w-[80px]">
                                <span class="text-xs text-zinc-400 block">الإجمالي</span>
                                <span class="text-xs font-black text-zinc-900 dark:text-white">
                                    {{ number_format($item['price'] * $item['quantity'], 2) }} د.إ
                                </span>
                            </div>

                            <button wire:click="removeItem({{ $id }})" class="text-zinc-400 hover:text-red-600 transition-colors p-1 cursor-pointer" title="حذف المنتج">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path>
                                </svg>
                            </button>

                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bg-white dark:bg-[#09090B] border-2 border-zinc-300 dark:border-zinc-800 rounded-2xl p-6 shadow-sm space-y-4">
                <h2 class="text-sm font-bold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-800 pb-3">ملخص الفاتورة</h2>
                
                <div class="space-y-2.5 text-xs font-semibold text-zinc-600 dark:text-zinc-400">
                    <div class="flex justify-between">
                        <span>المجموع الفرعي:</span>
                        <span class="text-zinc-900 dark:text-white font-black">{{ number_format($subtotal, 2) }} د.إ</span>
                    </div>
                    <div class="flex justify-between">
                        <span>الضريبة (5%):</span>
                        <span class="text-zinc-900 dark:text-white font-black">{{ number_format($tax, 2) }} د.إ</span>
                    </div>
                    <div class="flex justify-between border-t-2 border-dashed border-zinc-300 dark:border-zinc-800 pt-3 text-sm">
                        <span class="text-zinc-900 dark:text-white font-bold">الإجمالي النهائي:</span>
                        <span class="text-[#f53003] font-black text-base">{{ number_format($total, 2) }} د.إ</span>
                    </div>
                </div>

                <div class="pt-2">
                    <a href="/checkout" class="w-full bg-zinc-950 hover:bg-[#f53003] dark:bg-white dark:text-zinc-950 dark:hover:bg-[#f53003] dark:hover:text-white text-white font-bold py-3 px-4 rounded-xl text-xs flex items-center justify-center gap-2 transition-all duration-200 shadow-md cursor-pointer active:scale-[0.98]">
                        <span>المتابعة لإتمام الشراء</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"></path>
                        </svg>
                    </a>
                </div>
            </div>

        </div>
    @else
        <div class="bg-white dark:bg-[#09090B] border-2 border-zinc-300 dark:border-zinc-800 rounded-2xl p-12 text-center max-w-md mx-auto space-y-4 shadow-sm">
            <div class="w-16 h-16 bg-zinc-100 dark:bg-zinc-900 rounded-full flex items-center justify-center mx-auto text-zinc-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"></path>
                </svg>
            </div>
            <h2 class="text-base font-bold text-zinc-900 dark:text-white">سلتك فارغة حالياً!</h2>
            <p class="text-xs text-zinc-500 dark:text-zinc-400">يبدو أنك لم تقم بإضافة أي منتجات إلى سلتك بعد.</p>
            <div class="pt-2">
                <a href="/products" class="inline-flex items-center justify-center bg-zinc-950 text-white font-bold text-xs px-5 py-2.5 rounded-xl hover:bg-[#f53003] transition-colors shadow-sm">
                    تصفح المنتجات الآن
                </a>
            </div>
        </div>
    @endif
</div>