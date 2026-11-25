<div class="max-w-md mx-auto px-4 py-16 text-center space-y-6" dir="rtl">
    
    <div class="w-20 h-20 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 rounded-full flex items-center justify-center mx-auto border-2 border-emerald-400 dark:border-emerald-800 animate-bounce">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path>
        </svg>
    </div>

    <div class="space-y-2">
        <h1 class="text-2xl font-black text-zinc-900 dark:text-white">تم استلام طلبك بنجاح!</h1>
        <p class="text-sm font-bold text-zinc-600 dark:text-zinc-400">
            شكراً لثقتك بنا. جاري الآن مراجعة وتجهيز طلبك الفاخر، وسنقوم بالتواصل معك عبر الهاتف لتأكيد الشحن فوراً.
        </p>
    </div>

    @if (session()->has('order_success'))
        <div class="p-3 bg-zinc-100 dark:bg-zinc-900 text-zinc-800 dark:text-zinc-300 text-xs font-bold rounded-xl border-2 border-zinc-300 dark:border-zinc-800">
            {{ session('order_success') }}
        </div>
    @endif

    <div class="pt-4 flex flex-col gap-3">
        <a href="/" class="w-full bg-[#f53003] hover:bg-red-700 text-white font-bold py-3 px-4 rounded-xl text-xs flex items-center justify-center gap-2 transition-all duration-200 shadow-md cursor-pointer active:scale-[0.98]">
            <span>العودة إلى الصفحة الرئيسية</span>
        </a>
        
        <a href="/products" class="w-full bg-zinc-950 hover:bg-zinc-800 dark:bg-white dark:text-zinc-950 dark:hover:bg-zinc-200 text-white font-bold py-3 px-4 rounded-xl text-xs flex items-center justify-center gap-2 transition-all duration-200 shadow-sm cursor-pointer border-2 border-zinc-300 dark:border-zinc-700">
            <span>متابعة تسوق منتجات أخرى</span>
        </a>
    </div>
</div>