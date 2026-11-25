<!DOCTYPE html>
<html lang="ar" dir="rtl" class="h-full bg-[#fcfcfc] dark:bg-[#030303]">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم الإدارية')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;900&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

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

        .scrollbar-none::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-none {
            scrollbar-width: none;
        }
    </style>
</head>

<body class="h-full text-zinc-900 dark:text-zinc-100 antialiased filament-dot-grid">

    @auth

        {{-- 🔥 Sidebar --}}
        @include('layouts.partials.admin-sidebar')

        {{-- 🎯 المحتوى الرئيسي --}}
        <div class="lg:pr-64 transition-all duration-300"
            :class="{ 'lg:pr-20': JSON.parse(localStorage.getItem('sidebar-collapsed') || 'false') }" x-data>

            {{-- Topbar (اختياري، يمكن الاستغناء عنه لأن الـ sidebar فيه كل شي) --}}
            <header
                class="sticky top-0 z-30 bg-white/80 dark:bg-[#030303]/80 backdrop-blur-md border-b border-zinc-200 dark:border-zinc-900">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-14 items-center">

                        <div class="flex items-center gap-3 lg:hidden">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#f53003]"></span>
                            <span class="text-xs font-black text-zinc-950 dark:text-white">
                                لوحة البيانات
                            </span>
                        </div>

                        <div class="hidden lg:flex items-center gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#f53003]"></span>
                            <span class="text-xs font-black text-zinc-950 dark:text-white tracking-tight">
                                @yield('title', 'لوحة البيانات الإدارية')
                            </span>
                        </div>

                        <div class="flex items-center gap-3">
                            <span
                                class="hidden sm:inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-bold rounded-full bg-emerald-50 text-emerald-700 dark:bg-emerald-950/20 dark:text-emerald-400">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                متصل
                            </span>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Main Content --}}
            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                {{ $slot }}
            </main>
        </div>

    @endauth

    @if (session()->has('success') || session()->has('message'))
        <div class="fixed bottom-6 left-6 z-50">
            <div
                class="bg-zinc-950 dark:bg-white text-white dark:text-zinc-950 border border-zinc-800 dark:border-zinc-200 px-4 py-2.5 rounded-lg shadow-sm flex items-center gap-2.5 text-[11px] font-bold">
                <span class="w-1 h-1 rounded-full bg-[#f53003]"></span>
                <span>{{ session('success') ?? session('message') }}</span>
            </div>
        </div>
    @endif

    @livewireScripts
</body>

</html>
