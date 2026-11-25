<div class="space-y-6">

    {{-- الترويسة --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-black text-zinc-950 dark:text-white">تحليلات المساعد الذكي</h1>
            <p class="text-sm text-zinc-500 mt-1">راقب أداء المساعد ورضا الزبائن</p>
        </div>

        <select wire:model.live="period"
            class="bg-white dark:bg-zinc-900 text-sm font-bold px-4 py-2 rounded-xl border border-zinc-200 dark:border-zinc-800 focus:outline-none focus:border-[#f53003] cursor-pointer">
            <option value="1">آخر 24 ساعة</option>
            <option value="7">آخر 7 أيام</option>
            <option value="30">آخر 30 يوم</option>
            <option value="90">آخر 90 يوم</option>
        </select>
    </div>

    {{-- بطاقات KPI --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

        <div class="bg-white dark:bg-[#0d0d10] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5">
            <p class="text-[10px] font-black text-zinc-400 uppercase">معدل الرضا (CSAT)</p>
            <p class="text-3xl font-black text-emerald-500 mt-2">{{ $csatScore }}%</p>
            <p class="text-[11px] text-zinc-500 mt-1 font-bold">من {{ $totalMessages }} رسالة</p>
        </div>

        <div class="bg-white dark:bg-[#0d0d10] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5">
            <p class="text-[10px] font-black text-zinc-400 uppercase">متوسط التقييم</p>
            <p class="text-3xl font-black text-amber-500 mt-2">{{ $avgRating }} ⭐</p>
            <p class="text-[11px] text-zinc-500 mt-1 font-bold">من 5 نجوم</p>
        </div>

        <div class="bg-white dark:bg-[#0d0d10] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5">
            <p class="text-[10px] font-black text-zinc-400 uppercase">معدل الفهم</p>
            <p class="text-3xl font-black text-blue-500 mt-2">{{ $understoodRate }}%</p>
            <p class="text-[11px] text-zinc-500 mt-1 font-bold">فهم صحيح</p>
        </div>

        <div class="bg-white dark:bg-[#0d0d10] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5">
            <p class="text-[10px] font-black text-zinc-400 uppercase">سرعة الرد</p>
            <p class="text-3xl font-black text-[#f53003] mt-2">{{ $avgResponseTime }}ms</p>
            <p class="text-[11px] text-zinc-500 mt-1 font-bold">متوسط</p>
        </div>

    </div>

    {{-- بطاقات التحويل --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 text-white rounded-2xl p-5">
            <p class="text-[11px] font-black opacity-80">معدل التحويل</p>
            <p class="text-3xl font-black mt-2">{{ $conversionRate }}%</p>
            <p class="text-xs mt-1 opacity-90">{{ $purchases }} شراء من {{ $sessionsCount }} جلسة</p>
        </div>
        <div class="bg-white dark:bg-[#0d0d10] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5">
            <p class="text-[11px] font-black text-zinc-400">إضافات للسلة</p>
            <p class="text-3xl font-black text-zinc-950 dark:text-white mt-2">{{ $cartAdds }}</p>
        </div>
        <div class="bg-white dark:bg-[#0d0d10] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5">
            <p class="text-[11px] font-black text-zinc-400">إجمالي المحادثات</p>
            <p class="text-3xl font-black text-zinc-950 dark:text-white mt-2">{{ $sessionsCount }}</p>
        </div>
    </div>

    {{-- توزيع النوايا --}}
    <div class="bg-white dark:bg-[#0d0d10] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6">
        <h2 class="text-base font-black text-zinc-950 dark:text-white mb-5">توزيع النوايا</h2>
        <div class="space-y-3">
            @foreach ($intentsDistribution as $item)
                @php
                    $maxCount = $intentsDistribution->max('count');
                    $percentage = $maxCount > 0 ? ($item->count / $maxCount) * 100 : 0;
                @endphp
                <div>
                    <div class="flex justify-between text-xs font-bold text-zinc-600 dark:text-zinc-400 mb-1">
                        <span>{{ $item->intent }}</span>
                        <span>{{ $item->count }}</span>
                    </div>
                    <div class="w-full bg-zinc-100 dark:bg-zinc-900 rounded-full h-2">
                        <div class="bg-[#f53003] h-2 rounded-full transition-all"
                            style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- أسئلة ما فهمها المساعد --}}
    <div class="bg-white dark:bg-[#0d0d10] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6">
        <h2 class="text-base font-black text-zinc-950 dark:text-white mb-3">أسئلة ما فهمها المساعد</h2>
        <p class="text-xs text-zinc-500 mb-4">هذه فرصك لتحسين القاموس 💡</p>

        @if ($unknownQuestions->isEmpty())
            <p class="text-sm text-emerald-500 font-bold">ممتاز! كل الأسئلة تم فهمها 🎉</p>
        @else
            <div class="space-y-2">
                @foreach ($unknownQuestions as $q)
                    <div class="p-3 bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/50 rounded-lg text-sm text-zinc-700 dark:text-zinc-300">
                        "{{ $q }}"
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>