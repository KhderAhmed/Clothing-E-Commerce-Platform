<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - متجر عالمي</title>

    @livewireStyles

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;900&display=swap"
        rel="stylesheet">
    {{-- <script src="/unpkg.com/alpinejs" defer></script> --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }

        .scrollbar-none::-webkit-scrollbar {
            display: none;
        }

        .ui-mesh-bg {
            background-image: radial-gradient(rgba(245, 48, 3, 0.015) 1px, transparent 0);
            background-size: 32px 32px;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .notification-slide {
            animation: slideIn 0.3s ease-out;
        }
    </style>
</head>

<body class="bg-[#FAFAFA] dark:bg-[#09090B] text-zinc-900 dark:text-zinc-100 min-h-screen pb-16 ui-mesh-bg antialiased">

    <div id="notificationContainer"
        class="fixed top-20 left-4 right-4 z-50 flex flex-col gap-2 max-w-xs mx-auto pointer-events-none"></div>

    <nav
        class="sticky top-0 z-50 bg-white/80 dark:bg-[#09090B]/80 backdrop-blur-md border-b border-zinc-200/80 dark:border-zinc-800/80 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-4">

                <div class="flex items-center shrink-0">
                    <a href="/"
                        class="text-xl font-black tracking-tight text-zinc-950 dark:text-white flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-600 animate-pulse"></span>
                        <span>متجرنا<span class="text-red-600">.</span></span>
                    </a>
                </div>

                <div class="flex-1 max-w-xl hidden md:block">
                    <div class="relative">
                        <input type="text" placeholder="ابحث عن المنتجات الرائدة..."
                            class="w-full bg-zinc-50 dark:bg-zinc-900 text-xs text-zinc-900 dark:text-zinc-100 pl-4 pr-10 py-3 rounded-xl border border-zinc-200 dark:border-zinc-800 focus:outline-none focus:border-red-600 dark:focus:border-red-600 focus:bg-white dark:focus:bg-black transition-all placeholder-zinc-400">
                        <div
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-zinc-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span id="connectionStatus"
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-semibold rounded-full bg-zinc-100 text-zinc-600 dark:bg-zinc-900 dark:text-zinc-400">
                        <span class="h-1.5 w-1.5 rounded-full bg-zinc-400 animate-pulse"></span>
                        جاري الاتصال...
                    </span>

                    <span class="h-5 w-px bg-zinc-200 dark:bg-zinc-800"></span>

                    <a href="#"
                        class="relative p-2 text-zinc-700 dark:text-zinc-300 hover:text-red-600 rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <span
                            class="absolute top-1 left-1 bg-red-600 text-white text-[9px] font-black w-3.5 h-3.5 rounded-full flex items-center justify-center ring-2 ring-white dark:ring-[#09090B]">
                            ٣
                        </span>
                    </a>


                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex items-center gap-2 text-xs font-bold text-zinc-700 dark:text-zinc-300 hover:text-red-600 px-3 py-2 bg-zinc-50 dark:bg-zinc-900 border border-zinc-200/60 dark:border-zinc-800/60 rounded-xl transition-colors cursor-pointer">
                                <span>{{ Auth::user()->name ?? 'الحساب' }}</span>
                                <svg class="w-3.5 h-3.5 transition-transform" :class="{ 'rotate-180': open }" fill="none"
                                    stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5">
                                    </path>
                                </svg>
                            </button>
                            <div x-show="open" @click.outside="open = false" x-transition
                                class="absolute left-0 mt-2 w-48 bg-white dark:bg-[#121215] border border-zinc-200/80 dark:border-zinc-800/80 rounded-2xl shadow-xl p-1.5 z-50">
                                <a href="{{ url('/profile-user') }}"
                                    class="flex items-center gap-2 px-4 py-2.5 text-xs font-semibold text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-900 rounded-xl transition-colors">
                                    الملف الشخصي
                                </a>
                                <hr class="my-1 border-zinc-100 dark:border-zinc-800">
                                <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-2 px-4 py-2.5 text-xs font-semibold text-red-600 hover:bg-red-50/50 dark:hover:bg-red-950/20 rounded-xl transition-all group cursor-pointer text-right border-none bg-transparent">
                                        <svg class="w-4 h-4 text-red-500 group-hover:translate-x-1 transition-transform shrink-0"
                                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75">
                                            </path>
                                        </svg>
                                        <span>تسجيل الخروج</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                    @endauth
                </div>
            </div>
        </div>

        <div
            class="bg-zinc-50/50 dark:bg-[#121215]/50 border-t border-zinc-200/60 dark:border-zinc-800/60 text-[11px] font-bold">
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-10 flex items-center gap-6 overflow-x-auto whitespace-nowrap scrollbar-none">
                <a href="#" class="flex items-center gap-1 text-zinc-950 dark:text-white hover:text-red-600">📂 كل
                    الفئات</a>
                <a href="#" class="text-zinc-500 dark:text-zinc-400 hover:text-red-600 transition-colors">🔥 عروض
                    اليوم</a>
                <a href="#" class="text-zinc-500 dark:text-zinc-400 hover:text-red-600 transition-colors">📱
                    الجوالات</a>
                <a href="#" class="text-zinc-500 dark:text-zinc-400 hover:text-red-600 transition-colors">👕
                    الموضة</a>
                <a href="#" class="text-zinc-500 dark:text-zinc-400 hover:text-red-600 transition-colors">🏠
                    المنزل</a>
                <a href="#" class="text-red-600 font-black">💥 تخفيضات كبرى</a>
            </div>
        </div>
    </nav>

    {{ $slot }}

    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        function showNotification(message, type = 'info') {
            const container = document.getElementById('notificationContainer');
            if (!container) return; // حماية في حال عدم وجود الكونتينر في الصفحة

            const colors = {
                success: 'bg-zinc-950 dark:bg-white',
                error: 'bg-red-600',
                info: 'bg-red-600',
                warning: 'bg-amber-600'
            };
            const textColors = {
                success: 'text-white dark:text-zinc-950',
                error: 'text-white',
                info: 'text-white',
                warning: 'text-white'
            };

            const notification = document.createElement('div');
            notification.className =
                `${colors[type]} ${textColors[type]} px-4 py-3 rounded-xl shadow-lg notification-slide mb-2 pointer-events-auto border border-zinc-800/10 flex items-center justify-between text-right`;
            notification.innerHTML = `<span class="text-xs font-bold">${message}</span>`;
            container.appendChild(notification);

            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }, 4000);
        }

        function initPusher() {
            const connectionStatus = document.getElementById('connectionStatus');

            // تعريف الـ Pusher للاتصال بـ Laravel Reverb
            const pusher = new Pusher('{{ env('REVERB_APP_KEY', 'cvpn1b5wez68dbe1kqhf') }}', {
                cluster: 'mt1',
                wsHost: '{{ env('REVERB_HOST', 'localhost') }}',
                wsPort: {{ env('REVERB_PORT', 8080) }},
                forceTLS: false,
                enabledTransports: ['ws'],
                disableStats: true
            });

            // مراقبة حالة الاتصال
            pusher.connection.bind('connected', () => {
                if (connectionStatus) {
                    connectionStatus.innerHTML =
                        '<span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> متصل';
                    connectionStatus.className =
                        'inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-bold rounded-full bg-emerald-50 text-emerald-700 dark:bg-emerald-950/20 dark:text-emerald-400';
                }
            });

            pusher.connection.bind('disconnected', () => {
                if (connectionStatus) {
                    connectionStatus.innerHTML =
                        '<span class="h-1.5 w-1.5 rounded-full bg-red-500"></span> غير متصل';
                    connectionStatus.className =
                        'inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-bold rounded-full bg-red-50 text-red-700';
                }
            });

            // 1. الاشتراك في قناة المنتجات (العامة)
            pusher.subscribe('products').bind('ProductUpdatedEvent', (data) => {
                let message = '',
                    type = 'info';
                switch (data.action) {
                    case 'created':
                        message = `➕ تم إضافة: ${data.name}`;
                        type = 'success';
                        break;
                    case 'updated':
                        message = `✏️ تم تحديث: ${data.name}`;
                        type = 'info';
                        break;
                    case 'published':
                        message = `📢 تم نشر: ${data.name}`;
                        type = 'success';
                        break;
                    case 'deleted':
                        message = `🗑️ تم حذف منتج`;
                        type = 'warning';
                        break;
                }
                showNotification(message, type);
                Livewire.dispatch('productUpdated');
            });

            // 2. الاشتراك في قناة تحديثات المستخدمين
            @auth
            const currentUserId = {{ auth()->id() }};

            pusher.subscribe('user-updates').bind('ProfileUpdatedEvent', (data) => {
                // التحقق من أن التحديث يخص هذا المستخدم المفتوح حسابه حالياً فقط
                if (parseInt(data.userId) === parseInt(currentUserId)) {
                    showNotification(`👤 تم تحديث بيانات ملفك الشخصي بنجاح`, 'success');

                    // إطلاق الحدث لـ Livewire لإعادة تحميل البيانات في الخانات فوراً
                    Livewire.dispatch('refreshProfile');
                }
            });
        @endauth
        }

        document.addEventListener('DOMContentLoaded', () => {
            initPusher();

            // الاستماع لإشعارات الـ Livewire المحلية المباشرة
            Livewire.on('showNotification', ({
                message,
                type
            }) => {
                showNotification(message, type);
            });
        });
    </script>

    @livewireScripts
<livewire:user.clothing-assistant />
</body>

</html>
