<div class="max-w-6xl mx-auto px-4 py-8" dir="rtl">

    <div class="mb-8">
        <h1 class="text-2xl font-black text-zinc-900 dark:text-white">
            طلباتي
        </h1>

        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
            متابعة حالة طلباتك الحالية والسابقة
        </p>
    </div>

    @if ($orders->count())

        <div class="space-y-4">

            @foreach ($orders as $order)
                <div
                    class="bg-white dark:bg-[#09090B]
                            border-2 border-zinc-200 dark:border-zinc-800
                            rounded-2xl p-5 shadow-sm">

                    <div class="flex items-center justify-between gap-4">

                        <div>
                            <h2 class="font-black text-zinc-900 dark:text-white">
                                طلب #{{ $order->id }}
                            </h2>

                            <p class="text-xs text-zinc-500 mt-1">
                                {{ $order->created_at->format('Y-m-d H:i') }}
                            </p>
                        </div>

                        <div class="text-left">
                            <span class="text-xs font-bold text-zinc-500">
                                الحالة
                            </span>

                            <div class="text-sm font-black text-red-600">
                                {{ $order->status->label() }}
                            </div>
                        </div>

                    </div>

                    <div
                        class="mt-4 pt-4 border-t border-zinc-200 dark:border-zinc-800
                                flex items-center justify-between">

                        <span class="text-sm font-bold text-zinc-600 dark:text-zinc-400">
                            الإجمالي
                        </span>

                        <span class="text-lg font-black text-zinc-900 dark:text-white">
                            {{ number_format($order->total, 2) }} د.إ
                        </span>

                    </div>

                </div>
            @endforeach

        </div>
    @else
        <div class="text-center py-16">
            <div class="text-5xl mb-4">📦</div>

            <h2 class="font-black text-zinc-900 dark:text-white">
                لا توجد طلبات حتى الآن
            </h2>

            <p class="text-sm text-zinc-500 mt-2">
                عندما تقوم بإنشاء طلب، سيظهر هنا.
            </p>
        </div>

    @endif

</div>
