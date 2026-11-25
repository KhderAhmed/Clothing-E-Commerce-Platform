<div>

    <body class="bg-white text-zinc-900 min-h-screen antialiased flex">

        <div class="w-full min-h-screen grid grid-cols-1 lg:grid-cols-12">
            
            <!-- ─── النصف الأول: الهوية البصرية والترحيب (يسار الشاشة في RTL) ─── -->
            <div class="hidden lg:flex lg:col-span-5 bg-[#09090B] p-12 flex-col justify-between relative overflow-hidden border-l border-zinc-800">
                <!-- تأثير ضوئي أحمر خافت جداً في الخلفية -->
                <div class="absolute -top-20 -left-20 w-80 h-80 bg-red-600/10 rounded-full blur-[120px] pointer-events-none"></div>
                
                <!-- الشعار -->
                <div class="flex items-center gap-2.5 relative z-10">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-600 animate-pulse"></span>
                    <span class="text-white font-black text-lg tracking-tight">متجرنا<span class="text-red-600">.</span></span>
                </div>

                <!-- النص الترحيبي الكبير -->
                <div class="space-y-4 relative z-10">
                    <h1 class="text-3xl font-black text-white leading-tight">
                        مرحباً بك في <br>
                        <span class="text-red-500">الجيل الجديد</span> من التسوق.
                    </h1>
                    <p class="text-zinc-400 text-xs leading-relaxed max-w-sm">
                        سجل دخولك الآن لتتمكن من تصفح المنتجات الحصرية وإدارة طلباتك من خلال لوحة التحكم الذكية والمطورة.
                    </p>
                </div>

                <!-- حقوق المنصة -->
                <div class="text-zinc-600 text-[11px] font-medium relative z-10">
                    &copy; {{ date('Y') }} متجرنا. جميع الحقوق محفوظة.
                </div>
            </div>

            <!-- ─── النصف الثاني: نموذج الدخول (يمين الشاشة في RTL) ─── -->
            <div class="col-span-12 lg:col-span-7 flex flex-col justify-center items-center p-6 sm:p-12 bg-[#FAFAFA]">
                
                <div class="w-full max-w-md bg-white border border-zinc-200/80 rounded-2xl p-8 sm:p-10 shadow-sm relative">
                    
                    <!-- زر العودة السريع للموبايل أو الخروج -->
                    <a href="/" class="absolute top-6 left-6 text-zinc-400 hover:text-zinc-900 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>

                    <!-- رأس النموذج (للشاشات الصغيرة والكبيرة) -->
                    <div class="mb-8">
                        <span class="lg:hidden inline-flex items-center gap-2 mb-4 text-xs font-bold text-zinc-900">
                            <span class="w-2 h-2 rounded-full bg-red-600"></span>
                            متجرنا
                        </span>
                        <h2 class="text-xl font-bold text-zinc-950 tracking-tight">تسجيل الدخول</h2>
                        <p class="text-xs text-zinc-400 mt-1">الرجاء إدخال البريد الإلكتروني وكلمة المرور للمتابعة.</p>
                    </div>

                    <!-- الفورم الفعلي -->
                    <form wire:submit="login" class="space-y-4">
                        @csrf
                        
                        <!-- حقل البريد الإلكتروني -->
                        <div>
                            <label class="block text-xs font-bold text-zinc-700 mb-2">البريد الإلكتروني</label>
                            <input   type="email" wire:model="email"
                                   class="w-full px-4 py-3 rounded-xl bg-zinc-50 border border-zinc-200 text-xs focus:outline-none focus:border-red-600 focus:bg-white transition-all placeholder-zinc-400"
                                   placeholder="name@example.com">
                            
                            @error('email')
                                <p class="text-red-600 text-[11px] font-semibold mt-1.5 flex items-center gap-1">
                                    <span class="w-1 h-1 rounded-full bg-red-600 inline-block"></span>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <!-- حقل كلمة المرور -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-xs font-bold text-zinc-700">كلمة المرور</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-[11px] font-medium text-zinc-400 hover:text-red-600 transition-colors">نسيت كلمة المرور؟</a>
                                @endif
                            </div>
                            <input type="password" name="password"   wire:model="password"   required
                                   class="w-full px-4 py-3 rounded-xl bg-zinc-50 border border-zinc-200 text-xs focus:outline-none focus:border-red-600 focus:bg-white transition-all placeholder-••••••••"
                                   placeholder="••••••••">
                        </div>
                        
                        <!-- خيار تذكرني المريح -->
                        <div class="flex items-center pt-1">
                            <input type="checkbox" name="remember" id="remember" 
                                   class="w-4 h-4 rounded border-zinc-300 text-red-600 focus:ring-0 cursor-pointer">
                            <label for="remember" class="text-xs font-medium text-zinc-500 mr-2 cursor-pointer select-none">تذكرني على هذا الجهاز</label>
                        </div>
                        
                        <!-- زر الدخول الفاخر -->
                        <button type="submit" 
                                class="w-full bg-zinc-950 hover:bg-zinc-900 text-white font-bold py-3.5 px-4 rounded-xl text-xs transition-colors glow-effect mt-2 flex items-center justify-center gap-2">
                            <span>دخول للمنصة</span>
                            <svg class="w-4 h-4 transform rotate-180" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                    </form>
                    
                    <!-- التذييل السريع -->
                    <div class="mt-6 pt-5 border-t border-zinc-100 text-center">
                        <p class="text-xs text-zinc-500">
                            ليس لديك حساب حتى الآن؟ 
                            <a href="{{ route('register') }}" class="font-bold text-red-600 hover:underline">إنشاء حساب جديد</a>
                        </p>
                    </div>

                </div>
            </div>

        </div>

    </body>
</html>

</div>
