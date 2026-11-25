<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }} - الواجهة الفاخرة</title>

        <!-- استدعاء خط Cairo الاحترافي بأعلى جودة -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <style>
            body { font-family: 'Cairo', sans-serif; }
            .ui-mesh-bg {
                background-image: radial-gradient(rgba(225, 29, 72, 0.03) 1px, transparent 0);
                background-size: 24px 24px;
            }
            .dark .ui-mesh-bg {
                background-image: radial-gradient(rgba(255, 255, 255, 0.015) 1px, transparent 0);
                background-size: 24px 24px;
            }
            .premium-shadow {
                box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.03), 0 2px 4px rgba(0, 0, 0, 0.02), 0 12px 24px rgba(0, 0, 0, 0.03);
            }
            .dark .premium-shadow {
                box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.05), 0 12px 24px rgba(0, 0, 0, 0.4);
            }
        </style>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-[#F4F4F6] dark:bg-[#08080A] text-zinc-900 dark:text-zinc-100 min-h-screen antialiased flex flex-col justify-between ui-mesh-bg p-3 sm:p-5">

        <!-- ─── 1. شريط الإشعارات الحي الذكي (UX Micro-interaction) ─── -->
        <div class="w-full max-w-7xl mx-auto mb-3 hidden sm:block">
            <div class="bg-zinc-900 dark:bg-[#121215] border border-zinc-800 text-zinc-300 rounded-xl px-4 py-1.5 text-[11px] flex items-center justify-between font-medium">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>جميع خوادم المتجر تعمل بكفاءة ١٠٠٪</span>
                </div>
                <div class="text-zinc-400">آخر تحديث للمنتجات: منذ دقيقة</div>
            </div>
        </div>

        <!-- ─── 2. إطار شريط التنقل (Minimalist Premium Navbar) ─── -->
        <div class="w-full max-w-7xl mx-auto">
            <header class="bg-white/80 dark:bg-[#121215]/80 backdrop-blur-xl border border-zinc-200/50 dark:border-zinc-800/60 rounded-2xl premium-shadow">
                <div class="px-6 h-16 flex items-center justify-between">
                    
                    <!-- الشعار بهوية خطية حادة -->
                    <a href="/" class="flex items-center gap-3 group">
                        <div class="w-7 h-7 bg-rose-600 rounded-lg flex items-center justify-center text-white font-black text-sm shadow-sm transition-transform group-hover:scale-105">
                            S
                        </div>
                        <span class="text-lg font-black tracking-tight text-zinc-950 dark:text-white">
                            مَـتْـجَـرنَـا<span class="text-rose-600">.</span>
                        </span>
                    </a>

                    <!-- أزرار الدخول وتسهيل التصفح -->
                    <div class="flex items-center gap-2">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/home') }}" class="text-xs font-bold text-zinc-900 bg-zinc-100 dark:bg-zinc-800 dark:text-white px-4 py-2 rounded-xl hover:bg-zinc-200 transition-all">
                                    لوحة التحكم
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-xs font-bold text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white px-3 py-2 transition-colors">
                                    تسجيل الدخول
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 px-4 py-2.5 rounded-xl transition-all shadow-xs active:scale-[0.98]">
                                        حساب جديد
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </header>
        </div>

        <!-- ─── 3. بؤرة المحتوى الرئيسي (Main Content Center) ─── -->
        <main class="w-full max-w-7xl mx-auto my-6 flex-grow flex flex-col gap-6">
            
            <!-- لوحة الترحيب الكبرى (Hero Hub) -->
            <div class="bg-white dark:bg-[#121215] border border-zinc-200/60 dark:border-zinc-800/60 rounded-3xl p-6 sm:p-16 premium-shadow relative overflow-hidden grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- الإضاءة الخلفية للـ UX -->
                <div class="absolute -top-10 -right-10 w-72 h-72 bg-rose-500/5 rounded-full blur-3xl pointer-events-none"></div>

                <!-- النصوص التوجيهية -->
                <div class="lg:col-span-7 space-y-5 text-right relative z-10">
                    <div class="inline-flex items-center gap-2 bg-zinc-50 dark:bg-zinc-900 text-zinc-600 dark:text-zinc-400 text-[11px] font-bold px-3 py-1 rounded-lg border border-zinc-200/60 dark:border-zinc-800/60">
                        ✨ إصدار واجهة المستخدم المستقرة v2.0
                    </div>
                    
                    <h1 class="text-3xl sm:text-5xl font-black text-zinc-950 dark:text-white leading-[1.25] tracking-tight">
                        تسوّق بذكاء، وبسرعة <br>
                        <span class="text-rose-600 dark:text-rose-500">تفوق توقعاتك.</span>
                    </h1>
                    
                    <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 max-w-lg leading-relaxed font-medium">
                        منصة تجارة إلكترونية صُممت لتمنحك تجربة خالية من التعقيد. واجهات مرنة للغاية، معالجة فورية للبيانات، وتصفح متناسق بالكامل مع الأجهزة الذكية.
                    </p>

                    <!-- أزرار التنقل السريع ذات المساحات الواسعة للضغط (Clickable Areas) -->
                    <div class="pt-4 flex flex-col sm:flex-row items-center gap-3">
                        <a href="{{ url('/products') }}" class="w-full sm:w-auto bg-zinc-950 dark:bg-zinc-50 text-white dark:text-zinc-950 font-bold px-8 py-3.5 rounded-xl text-xs shadow-xs hover:opacity-90 transition-all flex items-center justify-center gap-2 cursor-pointer">
                            الانتقال إلى صالة العرض
                            <svg class="w-4 h-4 transform rotate-180" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                        <a href="#ux-benefits" class="w-full sm:w-auto text-zinc-600 dark:text-zinc-400 font-bold px-8 py-3.5 rounded-xl text-xs border border-zinc-200 dark:border-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-900 transition-colors text-center">
                            الخصائص الفنية للمنصة
                        </a>
                    </div>
                </div>

                <!-- واجهة توضيحية مصغرة فائقة النقاء (Sleek Product Highlight Card) -->
                <div class="lg:col-span-5 relative z-10 hidden lg:block">
                    <div class="bg-zinc-50 dark:bg-[#18181B] border border-zinc-200 dark:border-zinc-800/80 p-5 rounded-2xl space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold text-zinc-400">معاينة الواجهة الحية</span>
                            <span class="w-2 h-2 rounded-full bg-rose-600"></span>
                        </div>
                        <div class="bg-white dark:bg-[#121215] border border-zinc-200/60 dark:border-zinc-800/60 rounded-xl p-3 flex items-center gap-3">
                            <div class="w-10 h-10 bg-rose-500/10 rounded-lg flex items-center justify-center text-rose-600 font-bold text-xs">📦</div>
                            <div class="flex-1 space-y-1.5">
                                <div class="h-2 bg-zinc-200 dark:bg-zinc-800 rounded w-1/2"></div>
                                <div class="h-1.5 bg-zinc-100 dark:bg-zinc-900 rounded w-5/6"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ─── 4. شبكة المزايا المرتكزة على تجربة المستخدم (UX Features Grid) ─── -->
            <div id="ux-benefits" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                
                <!-- كرت ميزة 1 -->
                <div class="bg-white dark:bg-[#121215] border border-zinc-200/50 dark:border-zinc-800/60 rounded-2xl p-5 premium-shadow flex gap-4 items-center">
                    <div class="p-2 bg-rose-50 dark:bg-rose-950/20 text-rose-600 rounded-lg shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-xs text-zinc-950 dark:text-zinc-100 mb-0.5">تصفح فوري فائق السرعة</h3>
                        <p class="text-[11px] text-zinc-400">تم ضغط وعرض الكود بأعلى معايير الويب الفورية.</p>
                    </div>
                </div>

                <!-- كرت ميزة 2 -->
                <div class="bg-white dark:bg-[#121215] border border-zinc-200/50 dark:border-zinc-800/60 rounded-2xl p-5 premium-shadow flex gap-4 items-center">
                    <div class="p-2 bg-rose-50 dark:bg-rose-950/20 text-rose-600 rounded-lg shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-xs text-zinc-950 dark:text-zinc-100 mb-0.5">حماية مشفرة بالكامل</h3>
                        <p class="text-[11px] text-zinc-400">خصوصية تامة لبيانات حسابك وطلباتك المسجلة.</p>
                    </div>
                </div>

                <!-- كرت ميزة 3 -->
                <div class="bg-white dark:bg-[#121215] border border-zinc-200/50 dark:border-zinc-800/60 rounded-2xl p-5 premium-shadow flex gap-4 items-center">
                    <div class="p-2 bg-rose-50 dark:bg-rose-950/20 text-rose-600 rounded-lg shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-xs text-zinc-950 dark:text-zinc-100 mb-0.5">دعم متناسق للوضع الليلي</h3>
                        <p class="text-[11px] text-zinc-400">تتحول الواجهة تلقائياً لراحة عينيك في بيئات الإضاءة المنخفضة.</p>
                    </div>
                </div>

            </div>

        </main>

        <!-- ─── 5. الفوتر الخطّي الفاخر (Minimal Premium Footer) ─── -->
        <div class="w-full max-w-7xl mx-auto">
            <footer class="bg-white dark:bg-[#121215] border border-zinc-200/50 dark:border-zinc-800/60 rounded-2xl py-4 text-center text-[11px] text-zinc-400 font-medium premium-shadow">
                &copy; {{ date('Y') }} {{ config('app.name') }}. المنصة مصممة بأعلى معايير جودة وتجربة المستخدم.
            </footer>
        </div>

    {{-- <livewire:clothing-assistant /> --}}
    </body>

</html>