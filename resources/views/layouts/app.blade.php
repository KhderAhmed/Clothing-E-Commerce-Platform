<!DOCTYPE html>
<html lang="ar" dir="rtl" class="h-full bg-[#fcfcfc] dark:bg-[#030303]">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم السحابية')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;900&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            letter-spacing: -0.01em;
        }

        .filament-dot-grid {
            background-image: radial-gradient(rgba(0, 0, 0, 0.03) 1px, transparent 0);
            background-size: 24px 24px;
        }

        .dark .filament-dot-grid {
            background-image: radial-gradient(rgba(255, 255, 255, 0.015) 1px, transparent 0);
        }
    </style>
</head>

<body class="h-full text-zinc-900 dark:text-zinc-100 antialiased filament-dot-grid">

    @auth
        <!-- شريط الملاحة الأساسي لمحرك فلامنت -->
        <nav
            class="sticky top-0 z-40 bg-white/80 dark:bg-[#030303]/80 backdrop-blur-md border-b border-zinc-200 dark:border-zinc-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-14 items-center">

                    <div class="flex items-center gap-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#f53003]"></span>
                        <span class="text-xs font-black text-zinc-950 dark:text-white tracking-tight">
                            لوحة البيانات الإدارية
                        </span>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="hidden sm:flex flex-col text-right">
                            <span
                                class="text-[11px] font-bold text-zinc-800 dark:text-zinc-200">{{ Auth::user()->name }}</span>
                        </div>

                        <span class="h-4 w-px bg-zinc-200 dark:bg-zinc-800"></span>

                        <a href="/"
                            class="text-[11px] font-bold text-zinc-400 hover:text-zinc-950 dark:hover:text-white transition-colors">
                            المتجر العام
                        </a>

                        <a href="/logout"
                            class="text-[11px] font-bold text-zinc-450 hover:text-[#f53003] transition-colors">
                            خروج
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    @endauth

    <!-- الحاوية التنفيذية للمحتوى الرئيسي -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">


        @if (auth()->user()->role == 'admin')
            {{-- <livewire:product-manager />
            <livewire:admin-orders /> --}}
        @else
        @endif

    </main>

    <!-- نظام التنبيه المصمت الأسفل (Toasts Notifications) -->
    @if (session()->has('success') || session()->has('message'))
        <div class="fixed bottom-6 left-6 z-50">
            <div
                class="bg-zinc-950 dark:bg-white text-white dark:text-zinc-950 border border-zinc-800 dark:border-zinc-200 px-4 py-2.5 rounded-lg shadow-sm flex items-center gap-2.5 text-[11px] font-bold">
                <span class="w-1 h-1 rounded-full bg-[#f53003]"></span>
                <span>{{ session('success') ?? session('message') }}</span>
            </div>
        </div>
    @endif

</body>

</html>
