<div class="space-y-6 antialiased selection:bg-[#f53003]/10 selection:text-[#f53003] max-w-4xl mx-auto">

    {{-- ══════════════════════════════════════════════════════════
         الترويسة + زر رجوع
    ═══════════════════════════════════════════════════════════ --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.products') }}" wire:navigate
            class="p-2 rounded-lg bg-zinc-100 dark:bg-zinc-900 hover:bg-zinc-200 dark:hover:bg-zinc-800 text-zinc-500 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-black text-zinc-950 dark:text-white tracking-tight">
                {{ $isEditing ? 'تعديل المنتج' : 'إضافة منتج جديد' }}
            </h1>
            <p class="text-xs text-zinc-400 mt-0.5">
                {{ $isEditing ? 'قم بتحديث بيانات المنتج أدناه' : 'املأ البيانات لإضافة منتج جديد للمنصة' }}
            </p>
        </div>
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
         النموذج
    ═══════════════════════════════════════════════════════════ --}}
    <form wire:submit.prevent="save" class="space-y-6">

        {{-- ─── المعلومات الأساسية ─── --}}
        <div class="bg-white dark:bg-[#09090b] rounded-xl border border-zinc-200 dark:border-zinc-850 p-6 shadow-xs">

            <div class="flex items-center gap-2.5 mb-6 pb-4 border-b border-zinc-100 dark:border-zinc-900">
                <span class="w-1 h-3.5 bg-[#f53003] rounded-full"></span>
                <h2 class="text-xs font-black text-zinc-900 dark:text-white tracking-tight">
                    المعلومات الأساسية
                </h2>
            </div>

            <div class="space-y-5">

                {{-- الاسم --}}
                <div>
                    <label class="block text-[11px] font-bold text-zinc-400 dark:text-zinc-500 mb-2">
                        اسم المنتج <span class="text-[#f53003]">*</span>
                    </label>
                    <input type="text" wire:model="name" placeholder="مثال: هاتف ذكي احترافي"
                        class="block w-full bg-zinc-50/50 dark:bg-zinc-900/50 text-xs text-zinc-900 dark:text-zinc-100 px-3.5 py-2.5 rounded-lg border border-zinc-200 dark:border-zinc-800 focus:outline-none focus:border-[#f53003] focus:bg-white dark:focus:bg-black transition-all">
                    @error('name')
                        <span class="text-[#f53003] text-[10px] mt-1.5 block font-bold">⚠️ {{ $message }}</span>
                    @enderror
                </div>

                {{-- الوصف --}}
                <div>
                    <label class="block text-[11px] font-bold text-zinc-400 dark:text-zinc-500 mb-2">
                        الوصف التفصيلي
                    </label>
                    <textarea wire:model="description" rows="4" placeholder="وصف مختصر..."
                        class="block w-full bg-zinc-50/50 dark:bg-zinc-900/50 text-xs text-zinc-900 dark:text-zinc-100 px-3.5 py-2.5 rounded-lg border border-zinc-200 dark:border-zinc-800 focus:outline-none focus:border-[#f53003] focus:bg-white dark:focus:bg-black transition-all resize-none"></textarea>
                    @error('description')
                        <span class="text-[#f53003] text-[10px] mt-1.5 block font-bold">⚠️ {{ $message }}</span>
                    @enderror
                </div>

                {{-- الصورة --}}
                <div>
                    <label class="block text-[11px] font-bold text-zinc-400 dark:text-zinc-500 mb-2">
                        صورة المنتج
                    </label>
                    <div class="flex items-center gap-4">
                        <div class="relative flex-1">
                            <input type="file" wire:model="image" id="product_image_input" class="hidden"
                                accept="image/*">
                            <label for="product_image_input"
                                class="flex items-center justify-center gap-2 w-full bg-zinc-50 dark:bg-zinc-900 text-zinc-500 dark:text-zinc-400 border border-dashed border-zinc-200 dark:border-zinc-800 rounded-lg py-2.5 px-3 cursor-pointer text-xs font-bold hover:border-[#f53003] hover:text-zinc-800 dark:hover:text-zinc-200 transition-all">
                                <span>📁 اختر صورة</span>
                            </label>
                        </div>

                        <div wire:loading wire:target="image"
                            class="text-[10px] text-zinc-400 font-bold animate-pulse flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-zinc-400 animate-ping"></span> جاري الرفع...
                        </div>

                        @if ($image)
                            <div
                                class="relative h-14 w-14 rounded-lg overflow-hidden border border-zinc-200 dark:border-zinc-800 shadow-xs flex-shrink-0">
                                <img src="{{ $image->temporaryUrl() }}" class="object-cover h-full w-full">
                            </div>
                        @elseif ($old_image)
                            <div
                                class="relative h-14 w-14 rounded-lg overflow-hidden border border-zinc-200 dark:border-zinc-800 shadow-xs flex-shrink-0">
                                <img src="{{ asset('storage/' . $old_image) }}" class="object-cover h-full w-full">
                            </div>
                        @endif
                    </div>
                    @error('image')
                        <span class="text-[#f53003] text-[10px] mt-1.5 block font-bold">⚠️ {{ $message }}</span>
                    @enderror
                </div>

            </div>
        </div>

        {{-- ─── التسعير ─── --}}
        <div class="bg-white dark:bg-[#09090b] rounded-xl border border-zinc-200 dark:border-zinc-850 p-6 shadow-xs">

            <div class="flex items-center gap-2.5 mb-6 pb-4 border-b border-zinc-100 dark:border-zinc-900">
                <span class="w-1 h-3.5 bg-[#f53003] rounded-full"></span>
                <h2 class="text-xs font-black text-zinc-900 dark:text-white tracking-tight">
                    التسعير والخصم
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- السعر --}}
                <div>
                    <label class="block text-[11px] font-bold text-zinc-400 dark:text-zinc-500 mb-2">
                        السعر الأصلي <span class="text-[#f53003]">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" step="0.01" wire:model="price" placeholder="0.00"
                            class="block w-full bg-zinc-50/50 dark:bg-zinc-900/50 text-xs text-zinc-900 dark:text-zinc-100 pl-12 pr-3.5 py-2.5 rounded-lg border border-zinc-200 dark:border-zinc-800 focus:outline-none focus:border-[#f53003] focus:bg-white dark:focus:bg-black transition-all">
                        <div
                            class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-zinc-400 text-[10px] font-bold pointer-events-none">
                            د.ل
                        </div>
                    </div>
                    @error('price')
                        <span class="text-[#f53003] text-[10px] mt-1.5 block font-bold">⚠️ {{ $message }}</span>
                    @enderror
                </div>

                {{-- الخصم --}}
                <div>
                    <label
                        class="block text-[11px] font-bold text-red-500 dark:text-red-400 mb-2 flex items-center gap-1">
                        🔥 نسبة الخصم (%)
                    </label>
                    <input type="number" step="1" min="0" max="100" wire:model="discount"
                        placeholder="مثال: 25"
                        class="block w-full bg-zinc-50/50 dark:bg-zinc-900/50 text-xs text-zinc-900 dark:text-zinc-100 px-3.5 py-2.5 rounded-lg border border-zinc-200 dark:border-zinc-800 focus:outline-none focus:border-[#f53003] focus:bg-white dark:focus:bg-black transition-all">
                    @error('discount')
                        <span class="text-[#f53003] text-[10px] mt-1.5 block font-bold">⚠️ {{ $message }}</span>
                    @enderror
                </div>

                {{-- تاريخ انتهاء الخصم --}}
                <div class="md:col-span-2">
                    <label class="block text-[11px] font-bold text-zinc-400 dark:text-zinc-500 mb-2">
                        تاريخ انتهاء الخصم (اختياري)
                    </label>
                    <input type="date" wire:model="discount_expires_at"
                        class="block w-full bg-zinc-50/50 dark:bg-zinc-900/50 text-xs text-zinc-900 dark:text-zinc-100 px-3.5 py-2.5 rounded-lg border border-zinc-200 dark:border-zinc-800 focus:outline-none focus:border-[#f53003] focus:bg-white dark:focus:bg-black transition-all">
                    @error('discount_expires_at')
                        <span class="text-[#f53003] text-[10px] mt-1.5 block font-bold">⚠️ {{ $message }}</span>
                    @enderror
                </div>

            </div>
        </div>

        {{-- ─── Variants ─── --}}
        <div class="bg-white dark:bg-[#09090b] rounded-xl border border-zinc-200 dark:border-zinc-850 p-6 shadow-xs">

            <div class="flex items-center justify-between mb-6 pb-4 border-b border-zinc-100 dark:border-zinc-900">
                <div class="flex items-center gap-2.5">
                    <span class="w-1 h-3.5 bg-[#f53003] rounded-full"></span>
                    <div>
                        <h2 class="text-xs font-black text-zinc-900 dark:text-white tracking-tight">
                            متغيرات المنتج (Variants)
                        </h2>
                        <p class="text-[9px] text-zinc-400 mt-0.5">أضف اللون والقياس والمخزون لكل نسخة</p>
                    </div>
                </div>
                <span class="px-2 py-1 rounded-md bg-zinc-100 dark:bg-zinc-900 text-[9px] font-bold text-zinc-500">
                    {{ count($variants) }} Variant
                </span>
            </div>

            {{-- حقول الإدخال --}}
            <div class="space-y-3">

                {{-- اللون (معدل بـ Custom Alpine Dropdown) --}}
                <div>
                    <label class="block text-[10px] font-bold text-zinc-400 mb-1.5">اللون</label>
                    <div x-data="{ open: false, selected: @entangle('variantColor') }" class="relative">
                        <button type="button" @click="open = !open" @click.outside="open = false"
                            class="w-full flex items-center justify-between bg-zinc-50/50 dark:bg-zinc-900/50 text-xs text-zinc-900 dark:text-zinc-100 px-3.5 py-2.5 rounded-lg border border-zinc-200 dark:border-zinc-800 focus:outline-none focus:border-[#f53003] transition-all font-bold">
                            <span
                                x-text="selected ? ($el.closest('div').querySelector('[data-val=\'' + selected + '\']')?.innerText || 'اختر اللون') : 'بدون لون'"></span>
                            <svg class="w-3.5 h-3.5 text-zinc-400 transition-transform duration-200"
                                :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>

                        <div x-show="open" x-transition style="display: none;"
                            class="absolute z-50 w-full mt-1 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg shadow-xl max-h-48 overflow-y-auto py-1">

                            <div @click="selected = ''; open = false"
                                :class="{ 'bg-[#f53003] text-white': !
                                    selected, 'text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800': selected }"
                                class="px-3.5 py-2 text-xs font-bold cursor-pointer transition-colors flex items-center justify-between">
                                <span>بدون لون</span>
                                <span x-show="!selected">✓</span>
                            </div>

                            @foreach ($colors as $color)
                                <div data-val="{{ $color->id }}"
                                    @click="selected = '{{ $color->id }}'; open = false"
                                    :class="{ 'bg-[#f53003] text-white': selected ==
                                        '{{ $color->id }}', 'text-zinc-800 dark:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-800': selected !=
                                            '{{ $color->id }}' }"
                                    class="px-3.5 py-2 text-xs font-bold cursor-pointer transition-colors flex items-center justify-between">
                                    <span>{{ $color->name }}</span>
                                    <span x-show="selected == '{{ $color->id }}'">✓</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @error('variantColor')
                        <span class="text-[#f53003] text-[10px] mt-1 block font-bold">⚠️ {{ $message }}</span>
                    @enderror
                </div>

                {{-- القياس (معدل بـ Custom Alpine Dropdown) --}}
                <div>
                    <label class="block text-[10px] font-bold text-zinc-400 mb-1.5">القياس</label>
                    <div x-data="{ open: false, selected: @entangle('variantSize') }" class="relative">
                        <button type="button" @click="open = !open" @click.outside="open = false"
                            class="w-full flex items-center justify-between bg-zinc-50/50 dark:bg-zinc-900/50 text-xs text-zinc-900 dark:text-zinc-100 px-3.5 py-2.5 rounded-lg border border-zinc-200 dark:border-zinc-800 focus:outline-none focus:border-[#f53003] transition-all font-bold">
                            <span
                                x-text="selected ? ($el.closest('div').querySelector('[data-val=\'' + selected + '\']')?.innerText || 'اختر القياس') : 'بدون قياس'"></span>
                            <svg class="w-3.5 h-3.5 text-zinc-400 transition-transform duration-200"
                                :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>

                        <div x-show="open" x-transition style="display: none;"
                            class="absolute z-50 w-full mt-1 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg shadow-xl max-h-48 overflow-y-auto py-1">

                            <div @click="selected = ''; open = false"
                                :class="{ 'bg-[#f53003] text-white': !
                                    selected, 'text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800': selected }"
                                class="px-3.5 py-2 text-xs font-bold cursor-pointer transition-colors flex items-center justify-between">
                                <span>بدون قياس</span>
                                <span x-show="!selected">✓</span>
                            </div>

                            @foreach ($sizes as $size)
                                <div data-val="{{ $size->id }}"
                                    @click="selected = '{{ $size->id }}'; open = false"
                                    :class="{ 'bg-[#f53003] text-white': selected ==
                                        '{{ $size->id }}', 'text-zinc-800 dark:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-800': selected !=
                                            '{{ $size->id }}' }"
                                    class="px-3.5 py-2 text-xs font-bold cursor-pointer transition-colors flex items-center justify-between">
                                    <span>{{ $size->name }}</span>
                                    <span x-show="selected == '{{ $size->id }}'">✓</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @error('variantSize')
                        <span class="text-[#f53003] text-[10px] mt-1 block font-bold">⚠️ {{ $message }}</span>
                    @enderror
                </div>

                {{-- SKU --}}
                <div>
                    <label class="block text-[10px] font-bold text-zinc-400 mb-1.5">SKU <span
                            class="text-[#f53003]">*</span></label>
                    <input type="text" wire:model="variantSku" placeholder="مثال: TS-BLK-M"
                        class="block w-full bg-zinc-50/50 dark:bg-zinc-900/50 text-xs text-zinc-900 dark:text-zinc-100 px-3.5 py-2.5 rounded-lg border border-zinc-200 dark:border-zinc-800 focus:outline-none focus:border-[#f53003] transition-all">
                    @error('variantSku')
                        <span class="text-[#f53003] text-[10px] mt-1 block font-bold">⚠️ {{ $message }}</span>
                    @enderror
                </div>

                {{-- السعر + المخزون --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-zinc-400 mb-1.5">سعر النسخة</label>
                        <input type="number" step="0.01" min="0" wire:model="variantPrice"
                            placeholder="سعر اختياري"
                            class="block w-full bg-zinc-50/50 dark:bg-zinc-900/50 text-xs text-zinc-900 dark:text-zinc-100 px-3.5 py-2.5 rounded-lg border border-zinc-200 dark:border-zinc-800 focus:outline-none focus:border-[#f53003] transition-all">
                        @error('variantPrice')
                            <span class="text-[#f53003] text-[10px] mt-1 block font-bold">⚠️ {{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-zinc-400 mb-1.5">المخزون <span
                                class="text-[#f53003]">*</span></label>
                        <input type="number" min="0" wire:model="variantStock"
                            class="block w-full bg-zinc-50/50 dark:bg-zinc-900/50 text-xs text-zinc-900 dark:text-zinc-100 px-3.5 py-2.5 rounded-lg border border-zinc-200 dark:border-zinc-800 focus:outline-none focus:border-[#f53003] transition-all">
                        @error('variantStock')
                            <span class="text-[#f53003] text-[10px] mt-1 block font-bold">⚠️ {{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- زر إضافة --}}
                <button type="button" wire:click="addVariant" wire:loading.attr="disabled" wire:target="addVariant"
                    class="w-full flex items-center justify-center gap-2 bg-zinc-900 hover:bg-[#f53003] dark:bg-white dark:hover:bg-[#f53003] text-white dark:text-black dark:hover:text-white font-bold px-4 py-2.5 rounded-lg text-xs transition-all cursor-pointer">
                    <span wire:loading.remove wire:target="addVariant">+ إضافة Variant</span>
                    <span wire:loading wire:target="addVariant">جاري الإضافة...</span>
                </button>

            </div>

            {{-- قائمة الـ Variants --}}
            @if (count($variants) > 0)
                <div class="mt-5 space-y-2">
                    @foreach ($variants as $index => $variant)
                        <div wire:key="variant-{{ $variant['id'] ?? 'new-' . $index }}"
                            class="p-3 bg-zinc-50 dark:bg-zinc-900/60 border border-zinc-200 dark:border-zinc-800 rounded-lg">
                            <div class="flex items-start justify-between gap-3">

                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-1.5 mb-2">
                                        @if (!empty($variant['color_name']))
                                            <span
                                                class="px-2 py-1 bg-white dark:bg-black border border-zinc-200 dark:border-zinc-800 rounded-md text-[9px] font-bold">
                                                🎨 {{ $variant['color_name'] }}
                                            </span>
                                        @elseif (!empty($variant['color_id']))
                                            @php $c = $colors->firstWhere('id', $variant['color_id']); @endphp
                                            @if ($c)
                                                <span
                                                    class="px-2 py-1 bg-white dark:bg-black border border-zinc-200 dark:border-zinc-800 rounded-md text-[9px] font-bold">
                                                    🎨 {{ $c->name }}
                                                </span>
                                            @endif
                                        @endif

                                        @if (!empty($variant['size_name']))
                                            <span
                                                class="px-2 py-1 bg-white dark:bg-black border border-zinc-200 dark:border-zinc-800 rounded-md text-[9px] font-bold">
                                                📏 {{ $variant['size_name'] }}
                                            </span>
                                        @elseif (!empty($variant['size_id']))
                                            @php $s = $sizes->firstWhere('id', $variant['size_id']); @endphp
                                            @if ($s)
                                                <span
                                                    class="px-2 py-1 bg-white dark:bg-black border border-zinc-200 dark:border-zinc-800 rounded-md text-[9px] font-bold">
                                                    📏 {{ $s->name }}
                                                </span>
                                            @endif
                                        @endif
                                    </div>

                                    <div
                                        class="text-[10px] font-mono font-bold text-zinc-600 dark:text-zinc-300 truncate">
                                        SKU: {{ $variant['sku'] }}
                                    </div>

                                    <div class="flex items-center gap-3 mt-2">
                                        <span class="text-[9px] text-zinc-400">
                                            💰 {{ $variant['price'] ?? 'سعر المنتج الأساسي' }}
                                        </span>
                                        <span
                                            class="text-[9px] font-bold {{ ($variant['stock'] ?? 0) > 0 ? 'text-emerald-600' : 'text-[#f53003]' }}">
                                            📦 Stock: {{ $variant['stock'] ?? 0 }}
                                        </span>
                                    </div>
                                </div>

                                <button type="button" wire:click="removeVariant({{ $index }})"
                                    wire:confirm="هل تريد حذف هذا الـ Variant؟"
                                    class="flex-shrink-0 px-2 py-1 text-[9px] font-bold text-[#f53003] bg-red-500/5 hover:bg-[#f53003] hover:text-white rounded-md border border-red-500/10 transition-all cursor-pointer">
                                    حذف
                                </button>

                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div
                    class="mt-4 p-6 text-center rounded-lg bg-zinc-50 dark:bg-zinc-900/50 border border-dashed border-zinc-200 dark:border-zinc-800">
                    <div class="text-lg mb-1">📦</div>
                    <p class="text-[10px] font-bold text-zinc-400">لم تتم إضافة أي Variants بعد</p>
                </div>
            @endif

        </div>

        {{-- ─── الأزرار ─── --}}
        <div class="flex gap-3 pt-2">
            <button type="submit" wire:loading.attr="disabled"
                class="flex-1 justify-center inline-flex items-center gap-2 bg-[#f53003] hover:bg-[#d42902] text-white font-bold px-5 py-3 rounded-lg text-xs transition-colors cursor-pointer shadow-sm disabled:opacity-50">
                <span wire:loading.remove wire:target="save">
                    {{ $isEditing ? '💾 تحديث المنتج' : '➕ حفظ المنتج' }}
                </span>
                <span wire:loading wire:target="save">⏳ جاري الحفظ...</span>
            </button>

            <a href="{{ route('admin.products') }}" wire:navigate
                class="inline-flex items-center justify-center bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-900 dark:hover:bg-zinc-800 text-zinc-600 dark:text-zinc-400 font-bold px-5 py-3 rounded-lg text-xs transition-colors">
                إلغاء
            </a>
        </div>

    </form>
</div>
