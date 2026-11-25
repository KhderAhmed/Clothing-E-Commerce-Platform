<div>
    <h1 class="text-2xl font-bold mb-6">
        {{ $title ?? 'إدارة الطلبات' }}
    </h1>

    @if (auth()->user()->role == 'admin')

        <div class="space-y-6">




            {{-- =========================================================
                إحصائيات الطلبات
                ========================================================= --}}
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4">

                @php
                    $orderStats = [
                        'pending' => [
                            'label' => 'قيد الانتظار',
                            'color' => 'yellow',
                        ],
                        'confirmed' => [
                            'label' => 'مؤكد',
                            'color' => 'blue',
                        ],
                        'processing' => [
                            'label' => 'قيد التجهيز',
                            'color' => 'purple',
                        ],
                        'shipped' => [
                            'label' => 'تم الشحن',
                            'color' => 'indigo',
                        ],
                        'delivered' => [
                            'label' => 'تم التسليم',
                            'color' => 'emerald',
                        ],
                        'cancelled' => [
                            'label' => 'ملغي',
                            'color' => 'red',
                        ],
                    ];
                @endphp

                @foreach ($orderStats as $statStatus => $stat)
                    <div
                        class="bg-white dark:bg-[#09090b]
                                border border-zinc-200 dark:border-zinc-850
                                rounded-xl p-4 shadow-xs">

                        <div
                            class="text-[10px] font-bold text-zinc-400
                                    dark:text-zinc-500 mb-2">
                            {{ $stat['label'] }}
                        </div>

                        <div class="text-2xl font-black text-zinc-900 dark:text-white">

                            @if (isset($statusCounts))
                                {{ $statusCounts[$statStatus] ?? 0 }}
                            @else
                                —
                            @endif

                        </div>
                    </div>
                @endforeach

            </div>


            {{-- =========================================================
                جدول الطلبات
                ========================================================= --}}
            <div
                class="bg-white dark:bg-[#09090b]
                        rounded-xl border border-zinc-200
                        dark:border-zinc-850 shadow-xs overflow-hidden">

                <div
                    class="px-6 py-4 border-b border-zinc-100
                            dark:border-zinc-900 flex flex-col
                            sm:flex-row items-start sm:items-center
                            justify-between gap-3">

                    <div>
                        <h2 class="text-sm font-black text-zinc-900 dark:text-white">
                            الطلبات
                        </h2>

                        <p class="text-[10px] text-zinc-400 mt-1">
                            إدارة ومتابعة حالات طلبات العملاء
                        </p>
                    </div>

                    {{-- فلترة الحالات --}}
                    @if (isset($statusFilter))
                        <div>
                            <select wire:model.live="statusFilter"
                                class="text-xs bg-zinc-50 dark:bg-zinc-900
                                       border border-zinc-200 dark:border-zinc-800
                                       rounded-lg px-3 py-2
                                       text-zinc-700 dark:text-zinc-300
                                       focus:outline-none
                                       focus:border-[#f53003]">

                                <option value="all">كل الطلبات</option>
                                <option value="pending">قيد الانتظار</option>
                                <option value="confirmed">مؤكد</option>
                                <option value="processing">قيد التجهيز</option>
                                <option value="shipped">تم الشحن</option>
                                <option value="delivered">تم التسليم</option>
                                <option value="cancelled">ملغي</option>

                            </select>
                        </div>
                    @endif

                </div>


                <div class="overflow-x-auto">

                    <table
                        class="min-w-full divide-y divide-zinc-200
                                  dark:divide-zinc-800 text-right">

                        <thead class="bg-zinc-50 dark:bg-zinc-900/50">

                            <tr>

                                <th
                                    class="px-6 py-3 text-[10px] font-black
                                           text-zinc-400 dark:text-zinc-500
                                           uppercase tracking-wider">
                                    رقم الطلب
                                </th>

                                <th
                                    class="px-6 py-3 text-[10px] font-black
                                           text-zinc-400 dark:text-zinc-500
                                           uppercase tracking-wider">
                                    العميل
                                </th>

                                <th
                                    class="px-6 py-3 text-[10px] font-black
                                           text-zinc-400 dark:text-zinc-500
                                           uppercase tracking-wider">
                                    المدينة
                                </th>

                                <th
                                    class="px-6 py-3 text-[10px] font-black
                                           text-zinc-400 dark:text-zinc-500
                                           uppercase tracking-wider">
                                    الإجمالي
                                </th>

                                <th
                                    class="px-6 py-3 text-[10px] font-black
                                           text-zinc-400 dark:text-zinc-500
                                           uppercase tracking-wider">
                                    طريقة الدفع
                                </th>

                                <th
                                    class="px-6 py-3 text-[10px] font-black
                                           text-zinc-400 dark:text-zinc-500
                                           uppercase tracking-wider">
                                    الحالة
                                </th>

                                <th
                                    class="px-6 py-3 text-[10px] font-black
                                           text-zinc-400 dark:text-zinc-500
                                           uppercase tracking-wider text-left">
                                    الإجراءات
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-900">

                            @forelse ($orders as $order)
                                @php

                                    /*
                                    |--------------------------------------------------------------------------
                                    | تحويل Enum إلى string
                                    |--------------------------------------------------------------------------
                                    */

                                    $orderStatus =
                                        $order->status instanceof \App\Enums\OrderStatus
                                            ? $order->status->value
                                            : (string) $order->status;

                                    /*
                                    |--------------------------------------------------------------------------
                                    | ألوان الحالات
                                    |--------------------------------------------------------------------------
                                    */

                                    $statusClasses = [
                                        'pending' =>
                                            'bg-yellow-500/10 text-yellow-600 dark:text-yellow-400 border-yellow-500/20',

                                        'confirmed' =>
                                            'bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-500/20',

                                        'processing' =>
                                            'bg-purple-500/10 text-purple-600 dark:text-purple-400 border-purple-500/20',

                                        'shipped' =>
                                            'bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border-indigo-500/20',

                                        'delivered' =>
                                            'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20',

                                        'cancelled' => 'bg-red-500/10 text-red-600 dark:text-red-400 border-red-500/20',
                                    ];

                                    /*
                                    |--------------------------------------------------------------------------
                                    | أسماء الحالات
                                    |--------------------------------------------------------------------------
                                    */

                                    $statusNames = [
                                        'pending' => 'قيد الانتظار',

                                        'confirmed' => 'مؤكد',

                                        'processing' => 'قيد التجهيز',

                                        'shipped' => 'تم الشحن',

                                        'delivered' => 'تم التسليم',

                                        'cancelled' => 'ملغي',
                                    ];

                                @endphp


                                <tr wire:key="order-{{ $order->id }}"
                                    class="hover:bg-zinc-50/50
                                           dark:hover:bg-zinc-900/10
                                           transition-colors">


                                    {{-- رقم الطلب --}}
                                    <td class="px-6 py-4 whitespace-nowrap">

                                        <div
                                            class="font-black text-xs
                                                    text-zinc-900 dark:text-white">

                                            #{{ $order->id }}

                                        </div>

                                        <div class="text-[10px] text-zinc-400 mt-1">

                                            {{ $order->created_at?->format('Y-m-d H:i') }}

                                        </div>

                                    </td>


                                    {{-- العميل --}}
                                    <td class="px-6 py-4">

                                        <div class="flex items-center gap-2">

                                            <div
                                                class="h-7 w-7 rounded-lg
                                                        bg-zinc-100 dark:bg-zinc-900
                                                        border border-zinc-200
                                                        dark:border-zinc-800
                                                        flex items-center justify-center
                                                        text-[10px] font-black
                                                        text-zinc-500">

                                                {{ Str::substr($order->customer_name ?? 'ع', 0, 1) }}

                                            </div>

                                            <div>

                                                <div
                                                    class="text-xs font-bold
                                                            text-zinc-900 dark:text-white">

                                                    {{ $order->customer_name ?? 'غير معروف' }}

                                                </div>

                                                <div class="text-[10px] text-zinc-400">

                                                    {{ $order->customer_phone ?? '—' }}

                                                </div>

                                            </div>

                                        </div>

                                    </td>


                                    {{-- المدينة --}}
                                    <td class="px-6 py-4 whitespace-nowrap">

                                        <span
                                            class="text-xs font-medium
                                                     text-zinc-600
                                                     dark:text-zinc-400">

                                            {{ $order->city ?? '—' }}

                                        </span>

                                    </td>


                                    {{-- الإجمالي --}}
                                    <td class="px-6 py-4 whitespace-nowrap">

                                        <span
                                            class="text-xs font-black
                                                     text-zinc-900
                                                     dark:text-white">

                                            {{ number_format((float) $order->total, 2) }}

                                        </span>

                                        <span class="text-[9px] text-zinc-400">
                                            د.ل
                                        </span>

                                    </td>


                                    {{-- طريقة الدفع --}}
                                    <td class="px-6 py-4 whitespace-nowrap">

                                        @if ($order->payment_method === 'cod')
                                            <span
                                                class="px-2 py-1 rounded-md
                                                         bg-zinc-100
                                                         dark:bg-zinc-900
                                                         text-zinc-600
                                                         dark:text-zinc-400
                                                         border border-zinc-200
                                                         dark:border-zinc-800
                                                         text-[10px] font-bold">

                                                الدفع عند الاستلام

                                            </span>
                                        @elseif($order->payment_method === 'card')
                                            <span
                                                class="px-2 py-1 rounded-md
                                                         bg-blue-500/10
                                                         text-blue-600
                                                         dark:text-blue-400
                                                         border border-blue-500/20
                                                         text-[10px] font-bold">

                                                بطاقة

                                            </span>
                                        @else
                                            <span class="text-[10px] text-zinc-400">
                                                {{ $order->payment_method ?? 'غير محدد' }}
                                            </span>
                                        @endif

                                    </td>


                                    {{-- الحالة --}}
                                    <td class="px-6 py-4 whitespace-nowrap">

                                        <span
                                            class="px-2 py-1 inline-flex
                                                   text-[10px] font-bold
                                                   rounded-md border
                                                   {{ $statusClasses[$orderStatus] ?? 'bg-zinc-100 text-zinc-800 border-zinc-200' }}">

                                            {{ $statusNames[$orderStatus] ?? $orderStatus }}

                                        </span>

                                    </td>


                                    {{-- الإجراءات --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-left">

                                        <div class="flex items-center justify-end gap-2">

                                            {{-- معاينة --}}
                                            @if (method_exists($this, 'showOrder'))
                                                <button wire:click="showOrder({{ $order->id }})"
                                                    class="px-3 py-1.5
                                                           bg-zinc-50
                                                           hover:bg-zinc-950
                                                           dark:bg-zinc-900
                                                           dark:hover:bg-white
                                                           text-zinc-600
                                                           dark:text-zinc-400
                                                           hover:text-white
                                                           dark:hover:text-black
                                                           rounded-lg
                                                           border border-zinc-200
                                                           dark:border-zinc-800
                                                           cursor-pointer
                                                           text-[10px] font-bold
                                                           transition-colors">

                                                    معاينة

                                                </button>
                                            @endif


                                            {{-- تغيير الحالة --}}
                                            @if (method_exists($this, 'updateStatus'))
                                                <select
                                                    wire:change="updateStatus({{ $order->id }}, $event.target.value)"
                                                    class="text-[10px] font-bold
                                                           bg-zinc-50
                                                           dark:bg-zinc-900
                                                           text-zinc-700
                                                           dark:text-zinc-300
                                                           border border-zinc-200
                                                           dark:border-zinc-800
                                                           rounded-lg
                                                           px-2 py-1.5
                                                           focus:outline-none
                                                           focus:border-[#f53003]">

                                                    <option value="">
                                                        تعديل الحالة
                                                    </option>

                                                    <option value="pending" @selected($orderStatus === 'pending')>
                                                        قيد الانتظار
                                                    </option>

                                                    <option value="confirmed" @selected($orderStatus === 'confirmed')>
                                                        مؤكد
                                                    </option>

                                                    <option value="processing" @selected($orderStatus === 'processing')>
                                                        قيد التجهيز
                                                    </option>

                                                    <option value="shipped" @selected($orderStatus === 'shipped')>
                                                        تم الشحن
                                                    </option>

                                                    <option value="delivered" @selected($orderStatus === 'delivered')>
                                                        تم التسليم
                                                    </option>

                                                    <option value="cancelled" @selected($orderStatus === 'cancelled')>
                                                        ملغي
                                                    </option>

                                                </select>
                                            @endif

                                        </div>

                                    </td>

                                </tr>


                            @empty

                                <tr>

                                    <td colspan="7" class="px-6 py-14 text-center">

                                        <div
                                            class="flex flex-col
                                                    items-center justify-center">

                                            <div
                                                class="h-12 w-12 rounded-xl
                                                        bg-zinc-100
                                                        dark:bg-zinc-900
                                                        flex items-center
                                                        justify-center
                                                        text-xl mb-3">

                                                📦

                                            </div>

                                            <div
                                                class="text-xs font-bold
                                                        text-zinc-500
                                                        dark:text-zinc-400">

                                                لا توجد طلبات حالياً

                                            </div>

                                            <div
                                                class="text-[10px]
                                                        text-zinc-400 mt-1">

                                                ستظهر طلبات العملاء هنا عند إنشائها.

                                            </div>

                                        </div>

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                @if (method_exists($orders, 'links'))
                    <div
                        class="px-6 py-4 border-t
                                border-zinc-100 dark:border-zinc-900">

                        {{ $orders->links() }}

                    </div>
                @endif

            </div>

        </div>


        {{-- =========================================================
            سكريبت Reverb / Pusher
            لم يتم تغييره
            ========================================================= --}}

        <script src="https://js.pusher.com/7.2/pusher.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {

                var connectionStatus = document.getElementById('connectionStatus');
                var messagesDiv = document.getElementById('messages');

                if (!connectionStatus || !messagesDiv) return;

                var pusher = new Pusher('cvpn1b5wez68dbe1kqh', {
                    cluster: 'mt1',
                    wsHost: 'localhost',
                    wsPort: 8080,
                    forceTLS: false,
                    enabledTransports: ['ws'],
                    disableStats: true
                });

                pusher.connection.bind('connecting', function() {

                    connectionStatus.className =
                        'inline-flex items-center gap-2 px-3 py-1 text-[11px] font-bold rounded-lg bg-amber-500/5 text-amber-500 border border-amber-500/10';

                    connectionStatus.innerHTML =
                        '<span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span> تهيئة الاتصال الحركي...';

                });

                pusher.connection.bind('connected', function() {

                    connectionStatus.className =
                        'inline-flex items-center gap-2 px-3 py-1 text-[11px] font-bold rounded-lg bg-emerald-500/5 text-emerald-500 border border-emerald-500/10';

                    connectionStatus.innerHTML =
                        '<span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> متصل بالشبكة حياً';

                    var channel = pusher.subscribe('products');

                    channel.bind('ProductUpdatedEvent', function(data) {

                        var text = '🔄 تحديث في المنصة';

                        if (data.action === 'created')
                            text = '➕ منتج جديد: ' + (data.name || '');

                        if (data.action === 'updated')
                            text = '✏️ تعديل منتج: ' + (data.name || '');

                        if (data.action === 'published')
                            text = '📢 نشر علني: ' + (data.name || '');

                        if (data.action === 'deleted')
                            text = '🗑️ إزالة عنصر مؤخراً';

                        var alertBox = document.createElement('div');

                        alertBox.className =
                            'text-[11px] font-black px-4 py-2 rounded-lg bg-white dark:bg-[#09090b] border border-[#f53003] text-[#f53003] shadow-sm transition-all duration-300';

                        alertBox.innerHTML = text;

                        if (messagesDiv.children.length > 0)
                            messagesDiv.removeChild(messagesDiv.firstChild);

                        messagesDiv.appendChild(alertBox);

                        setTimeout(function() {

                            alertBox.style.opacity = '0';

                            setTimeout(function() {
                                alertBox.remove();
                            }, 300);

                        }, 4000);

                        if (window.Livewire)
                            Livewire.dispatch('refreshProducts');

                    });

                });

                pusher.connection.bind('disconnected', function() {

                    connectionStatus.className =
                        'inline-flex items-center gap-2 px-3 py-1 text-[11px] font-bold rounded-lg bg-red-500/5 text-red-500 border border-red-500/10';

                    connectionStatus.innerHTML =
                        '<span class="h-1.5 w-1.5 rounded-full bg-red-500"></span> غير متصل';

                });

            });
        </script>
    @else
        {{-- =========================================================
            403
            ========================================================= --}}

        <div class="min-h-[400px] flex items-center justify-center">

            <div class="text-center">

                <div class="text-6xl mb-4">
                    🔒
                </div>

                <h2 class="text-xl font-black
                           text-zinc-900 dark:text-white mb-2">

                    403

                </h2>

                <p class="text-sm text-zinc-500 dark:text-zinc-400">

                    ليس لديك صلاحية للدخول إلى هذه الصفحة

                </p>

            </div>

        </div>

    @endif

</div>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var connectionStatus = document.getElementById('connectionStatus');
        var messagesDiv = document.getElementById('messages');
        if (!connectionStatus || !messagesDiv) return;

        // تهيئة اتصال الـ Pusher المحلي (Websocket)
        var pusher = new Pusher('cvpn1b5wez68dbe1kqhf', {
            cluster: 'mt1',
            wsHost: 'localhost',
            wsPort: 8080,
            forceTLS: false,
            enabledTransports: ['ws'],
            disableStats: true
        });

        pusher.connection.bind('connecting', function() {
            connectionStatus.className =
                'inline-flex items-center gap-2 px-3 py-1 text-[11px] font-bold rounded-lg bg-amber-500/5 text-amber-500 border border-amber-500/10';
            connectionStatus.innerHTML =
                '<span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span> تهيئة الاتصال الحركي...';
        });

        pusher.connection.bind('connected', function() {
            connectionStatus.className =
                'inline-flex items-center gap-2 px-3 py-1 text-[11px] font-bold rounded-lg bg-emerald-500/5 text-emerald-500 border border-emerald-500/10';
            connectionStatus.innerHTML =
                '<span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> متصل بالشبكة حياً';

            // الاشتراك في القناة
            var orderChannel = pusher.subscribe('admin-orders');

            // الاستماع لحدث الطلبات الجديدة
            orderChannel.bind('OrderPlaced', function(data) {

                // إنشاء الإشعار المنبثق باللون الأحمر المميز
                var alertBox = document.createElement('div');
                alertBox.className =
                    'text-[11px] font-black px-4 py-2.5 rounded-lg bg-white dark:bg-[#09090b] border-2 border-[#f53003] text-[#f53003] shadow-md transition-all duration-300 animate-bounce';
                alertBox.innerHTML = '🔔 طلب جديد واصل! رقم #' + data.orderId + ' للعميل ' +
                    data.customerName;

                if (messagesDiv.children.length > 0) messagesDiv.removeChild(messagesDiv
                    .firstChild);
                messagesDiv.appendChild(alertBox);

                // تدمير الإشعار بعد 5 ثوانٍ بسلاسة
                setTimeout(function() {
                    alertBox.style.opacity = '0';
                    setTimeout(function() {
                        alertBox.remove();
                    }, 300);
                }, 5000);

                // مناداة الـ Component لتحديث السجلات حياً
                if (typeof Livewire !== 'undefined') {
                    Livewire.dispatch('refreshOrders');
                }
            });
        });

        pusher.connection.bind('disconnected', function() {
            connectionStatus.className =
                'inline-flex items-center gap-2 px-3 py-1 text-[11px] font-bold rounded-lg bg-red-500/5 text-red-500 border border-red-500/10';
            connectionStatus.innerHTML =
                '<span class="h-1.5 w-1.5 rounded-full bg-red-500"></span> غير متصل';
        });
    });
</script>
</div>
