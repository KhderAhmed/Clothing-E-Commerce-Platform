<div class="max-w-6xl mx-auto px-4 py-8 text-right" dir="rtl">
    
    <div class="border-b-2 border-zinc-300 dark:border-zinc-800 pb-5 mb-8">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">إتمام عملية الشراء</h1>
        <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">يرجى إدخال بيانات الشحن بدقة لضمان وصول طلبك بأسرع وقت.</p>
    </div>

    <form wire:submit.prevent="placeOrder" class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white dark:bg-[#09090B] border-2 border-zinc-300 dark:border-zinc-800 rounded-2xl p-6 shadow-sm space-y-5">
                <h2 class="text-base font-bold text-zinc-900 dark:text-white flex items-center gap-2 pb-3 border-b border-zinc-200 dark:border-zinc-800">
                    🚚 تفاصيل الشحن والتوصيل
                </h2>

                <div class="space-y-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-zinc-900 dark:text-zinc-200">الاسم الكامل للمستلم</label>
                        <input type="text" wire:model="name" class="w-full px-4 py-2.5 bg-white dark:bg-zinc-900 border-2 border-zinc-400 dark:border-zinc-700 rounded-xl text-sm font-semibold text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-600 transition-all shadow-sm">
                        @error('name') <span class="text-xs text-red-600 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-zinc-900 dark:text-zinc-200">رقم الهاتف (الواتساب)</label>
                            <input type="text" wire:model="phone" placeholder="مثال: 0501234567" class="w-full px-4 py-2.5 bg-white dark:bg-zinc-900 border-2 border-zinc-400 dark:border-zinc-700 rounded-xl text-sm font-semibold text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-600 transition-all shadow-sm text-left" dir="ltr">
                            @error('phone') <span class="text-xs text-red-600 font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-zinc-900 dark:text-zinc-200">المدينة / الإمارة</label>
                            <input type="text" wire:model="city" class="w-full px-4 py-2.5 bg-white dark:bg-zinc-900 border-2 border-zinc-400 dark:border-zinc-700 rounded-xl text-sm font-semibold text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-600 transition-all shadow-sm">
                            @error('city') <span class="text-xs text-red-600 font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-zinc-900 dark:text-zinc-200">العنوان بالتفصيل (الحي، الشارع، رقم البناء أو علامة مميزة)</label>
                        <textarea wire:model="address" rows="3" class="w-full px-4 py-2.5 bg-white dark:bg-zinc-900 border-2 border-zinc-400 dark:border-zinc-700 rounded-xl text-sm font-semibold text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-600 transition-all shadow-sm"></textarea>
                        @error('address') <span class="text-xs text-red-600 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-[#09090B] border-2 border-zinc-300 dark:border-zinc-800 rounded-2xl p-6 shadow-sm space-y-4">
                <h2 class="text-base font-bold text-zinc-900 dark:text-white flex items-center gap-2 pb-3 border-b border-zinc-200 dark:border-zinc-800">
                    💳 طريقة الدفع المفضلة
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="border-2 {{ $payment_method === 'cod' ? 'border-red-600 bg-red-50/10' : 'border-zinc-300' }} rounded-xl p-4 flex items-center gap-3 cursor-pointer transition-all">
                        <input type="radio" wire:model.live="payment_method" value="cod" class="text-red-600 focus:ring-red-500">
                        <div>
                            <span class="block text-xs font-bold text-zinc-900 dark:text-white">الدفع عند الاستلام (COD)</span>
                            <span class="block text-[11px] text-zinc-500 mt-0.5">ادفع نقداً فور استلام شحنتك عند باب البيت.</span>
                        </div>
                    </label>

                    <label class="border-2 {{ $payment_method === 'card' ? 'border-red-600 bg-red-50/10' : 'border-zinc-300' }} rounded-xl p-4 flex items-center gap-3 cursor-pointer transition-all opacity-60">
                        <input type="radio" wire:model.live="payment_method" value="card" class="text-red-600 focus:ring-red-500" disabled>
                        <div>
                            <span class="block text-xs font-bold text-zinc-900 dark:text-white">بطاقة الائتمان / الدفع الإلكتروني</span>
                            <span class="block text-[11px] text-zinc-500 mt-0.5">قريباً (متوفر حالياً الدفع عند الاستلام فقط).</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white dark:bg-[#09090B] border-2 border-zinc-300 dark:border-zinc-800 rounded-2xl p-6 shadow-sm space-y-4">
                <h2 class="text-sm font-bold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-800 pb-3">مراجعة المنتجات</h2>
                
                <div class="max-h-60 overflow-y-auto space-y-3 pr-1">
                    @foreach($cart as $id => $item)
                        <div class="flex items-center justify-between text-xs gap-4 border-b border-zinc-100 dark:border-zinc-900 pb-2.5">
                            <div class="flex items-center gap-2">
                                <span class="bg-zinc-100 dark:bg-zinc-900 px-2 py-1 rounded-md font-black text-[10px] text-zinc-800 dark:text-zinc-200 border border-zinc-300">
                                    {{ $item['quantity'] }}x
                                </span>
                                <span class="font-bold text-zinc-900 dark:text-zinc-100 truncate max-w-[140px]">{{ $item['name'] }}</span>
                            </div>
                            <span class="font-black text-zinc-900 dark:text-white">{{ number_format($item['price'] * $item['quantity'], 2) }} د.إ</span>
                        </div>
                    @endforeach
                </div>

                <div class="space-y-2.5 text-xs font-semibold text-zinc-600 dark:text-zinc-400 pt-2 border-t border-zinc-200 dark:border-zinc-800">
                    <div class="flex justify-between">
                        <span>المجموع الفرعي:</span>
                        <span class="text-zinc-900 dark:text-white font-black">{{ number_format($subtotal, 2) }} د.إ</span>
                    </div>
                    <div class="flex justify-between">
                        <span>الضريبة المضافة (5%):</span>
                        <span class="text-zinc-900 dark:text-white font-black">{{ number_format($tax, 2) }} د.إ</span>
                    </div>
                    <div class="flex justify-between border-t-2 border-dashed border-zinc-300 dark:border-zinc-800 pt-3 text-sm">
                        <span class="text-zinc-900 dark:text-white font-bold">الإجمالي المستحق:</span>
                        <span class="text-[#f53003] font-black text-base">{{ number_format($total, 2) }} د.إ</span>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" 
                            wire:loading.attr="disabled"
                            class="w-full bg-[#f53003] hover:bg-red-700 text-white font-bold py-3.5 px-4 rounded-xl text-xs flex items-center justify-center gap-2 transition-all duration-200 shadow-md cursor-pointer active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed">
                        
                        <span wire:loading wire:target="placeOrder" class="w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                        
                        <span wire:loading.remove wire:target="placeOrder">🛍️ تأكيد وطلب الشراء الآن</span>
                        <span wire:loading wire:target="placeOrder">جاري معالجة طلبك الفاخر...</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>