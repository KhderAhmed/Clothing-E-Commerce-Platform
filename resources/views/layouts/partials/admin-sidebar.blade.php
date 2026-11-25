{{-- ══════════════════════════════════════════════════════════
     Filament-Style Admin Sidebar
═══════════════════════════════════════════════════════════ --}}
<aside x-data="{
    mobileOpen: false,
    collapsed: JSON.parse(localStorage.getItem('sidebar-collapsed') || 'false'),
    toggleCollapse() {
        this.collapsed = !this.collapsed;
        localStorage.setItem('sidebar-collapsed', this.collapsed);
    }
}" @keydown.escape.window="mobileOpen = false" class="relative">

    {{-- Overlay للموبايل --}}
    <div x-show="mobileOpen" x-transition.opacity @click="mobileOpen = false"
        class="fixed inset-0 bg-zinc-950/50 backdrop-blur-sm z-40 lg:hidden"></div>

    {{-- زر فتح Sidebar على الموبايل --}}
    <button @click="mobileOpen = true" type="button"
        class="lg:hidden fixed top-4 right-4 z-30 p-2 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm cursor-pointer">
        <svg class="w-5 h-5 text-zinc-700 dark:text-zinc-300" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
    </button>

    {{-- Sidebar --}}
    <div :class="{
        'translate-x-0': mobileOpen,
        'translate-x-full lg:translate-x-0': !mobileOpen,
        'lg:w-20': collapsed,
        'lg:w-64': !collapsed
    }"
        class="fixed top-0 right-0 h-screen bg-white dark:bg-[#0a0a0c] border-l border-zinc-200 dark:border-zinc-900 z-50 flex flex-col transition-all duration-300 ease-in-out w-64">

        {{-- ═══ Header / Logo ═══ --}}
        <div class="h-14 shrink-0 flex items-center justify-between px-4 border-b border-zinc-200 dark:border-zinc-900">

            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 overflow-hidden">
                <span class="w-7 h-7 rounded-lg bg-zinc-950 dark:bg-white flex items-center justify-center shrink-0">
                    <span class="w-2 h-2 rounded-full bg-[#f53003]"></span>
                </span>
                <span x-show="!collapsed" x-transition.opacity
                    class="text-sm font-black text-zinc-950 dark:text-white whitespace-nowrap">
                    لوحة الأدمن
                </span>
            </a>

            {{-- زر إغلاق للموبايل --}}
            <button @click="mobileOpen = false" type="button"
                class="lg:hidden p-1.5 text-zinc-400 hover:text-zinc-950 dark:hover:text-white cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

        </div>

        {{-- ═══ Navigation ═══ --}}
        <nav class="flex-1 overflow-y-auto overflow-x-hidden py-4 px-3 scrollbar-none">

            {{-- المجموعة 1: نظرة عامة --}}
            <div class="mb-5">
                <p x-show="!collapsed" x-transition.opacity
                    class="px-2 mb-2 text-[10px] font-black uppercase tracking-widest text-zinc-400 dark:text-zinc-600">
                    نظرة عامة
                </p>

                @php
                    $mainLinks = [
                        [
                            'route' => 'admin.dashboard',
                            'label' => 'الداشبورد',
                            'icon' => 'home',
                            'pattern' => 'admin.dashboard',
                        ],
                    ];
                @endphp

                @foreach ($mainLinks as $link)
                    @include('layouts.partials.sidebar-link', $link)
                @endforeach
            </div>

            {{-- المجموعة 2: المتجر --}}
            <div class="mb-5">
                <p x-show="!collapsed" x-transition.opacity
                    class="px-2 mb-2 text-[10px] font-black uppercase tracking-widest text-zinc-400 dark:text-zinc-600">
                    المتجر
                </p>

                @php
                    $storeLinks = [
                        [
                            'route' => 'admin.products',
                            'label' => 'المنتجات',
                            'icon' => 'box',
                            'pattern' => 'admin.products*',
                            'badge' => null,
                        ],
                        [
                            'route' => 'admin.orders',
                            'label' => 'الطلبات',
                            'icon' => 'cart',
                            'pattern' => 'admin.orders*',
                            'badge' => null,
                        ],
                        [
                            'route' => 'admin.chat-analytics',
                            'label' => 'تحليلات الشات',
                            'icon' => 'chart',
                            'pattern' => 'admin.chat-analytics*',
                        ],
                    ];
                @endphp

                @foreach ($storeLinks as $link)
                    @include('layouts.partials.sidebar-link', $link)
                @endforeach
            </div>

            {{-- المجموعة 3: الحساب --}}
            <div class="mb-5">
                <p x-show="!collapsed" x-transition.opacity
                    class="px-2 mb-2 text-[10px] font-black uppercase tracking-widest text-zinc-400 dark:text-zinc-600">
                    الحساب
                </p>

                {{-- المتجر العام --}}
                <a href="/" wire:navigate
                    class="group flex items-center gap-3 px-3 py-2 mb-0.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-900/50 hover:text-zinc-950 dark:hover:text-white transition-all">
                    <svg class="w-4.5 h-4.5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                    </svg>
                    <span x-show="!collapsed" x-transition.opacity class="text-xs font-bold whitespace-nowrap">المتجر
                        العام</span>
                </a>

                {{-- الملف الشخصي --}}
                <a href="{{ route('admin.profile') }}" wire:navigate
                    class="group flex items-center gap-3 px-3 py-2 mb-0.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-900/50 hover:text-zinc-950 dark:hover:text-white transition-all">
                    <svg class="w-4.5 h-4.5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    <span x-show="!collapsed" x-transition.opacity class="text-xs font-bold whitespace-nowrap">الملف
                        الشخصي</span>
                </a>
            </div>

        </nav>

        {{-- ═══ User Menu / Footer ═══ --}}
        <div class="shrink-0 border-t border-zinc-200 dark:border-zinc-900 p-3">

            <div x-data="{ userMenu: false }" class="relative">

                <button @click="userMenu = !userMenu" type="button"
                    class="w-full flex items-center gap-3 p-2 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-900/50 transition-all cursor-pointer">

                    <div
                        class="w-8 h-8 rounded-full bg-gradient-to-br from-[#f53003] to-[#d32b02] flex items-center justify-center text-white font-black text-xs shrink-0">
                        {{ mb_substr(auth()->user()->name ?? 'A', 0, 1) }}
                    </div>

                    <div x-show="!collapsed" x-transition.opacity class="flex-1 text-right min-w-0">
                        <p class="text-xs font-bold text-zinc-950 dark:text-white truncate">
                            {{ auth()->user()->name ?? 'Admin' }}
                        </p>
                        <p class="text-[10px] text-zinc-400 truncate">
                            {{ auth()->user()->email ?? '' }}
                        </p>
                    </div>

                    <svg x-show="!collapsed" x-transition.opacity class="w-3.5 h-3.5 text-zinc-400 shrink-0"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                    </svg>
                </button>

                {{-- Dropdown --}}
                <div x-show="userMenu" x-transition @click.outside="userMenu = false"
                    class="absolute bottom-full right-0 mb-2 w-56 bg-white dark:bg-[#121215] border border-zinc-200/80 dark:border-zinc-800/80 rounded-xl shadow-xl p-1.5 z-50">

                    <a href="{{ url('/profile-user') }}"
                        class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-900 rounded-lg transition-colors">
                        <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" stroke-width="1.8"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        الملف الشخصي
                    </a>

                    <hr class="my-1 border-zinc-100 dark:border-zinc-800">

                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-2 px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-50/50 dark:hover:bg-red-950/20 rounded-lg transition-colors cursor-pointer">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor"
                                stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                            </svg>
                            تسجيل الخروج
                        </button>
                    </form>
                </div>
            </div>

            {{-- زر الطي (Desktop فقط) --}}
            <button @click="toggleCollapse()" type="button"
                class="hidden lg:flex w-full items-center justify-center gap-2 mt-2 py-1.5 text-[10px] font-bold text-zinc-400 hover:text-zinc-950 dark:hover:text-white rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-900/50 transition-all cursor-pointer">
                <svg class="w-3.5 h-3.5 transition-transform" :class="{ 'rotate-180': collapsed }" fill="none"
                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                <span x-show="!collapsed" x-transition.opacity>تصغير القائمة</span>
            </button>

        </div>

    </div>

</aside>
