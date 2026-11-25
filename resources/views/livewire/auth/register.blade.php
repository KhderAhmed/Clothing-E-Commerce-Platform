<div class="w-full min-h-screen grid grid-cols-1 lg:grid-cols-12 flex">
    
    <div class="hidden lg:flex lg:col-span-5 bg-[#09090B] p-12 flex-col justify-between relative overflow-hidden border-l border-zinc-800">
        <div class="absolute -top-20 -left-20 w-80 h-80 bg-red-600/10 rounded-full blur-[120px] pointer-events-none"></div>
        
        <div class="flex items-center gap-2.5 relative z-10">
            <span class="w-2.5 h-2.5 rounded-full bg-red-600 animate-pulse"></span>
            <span class="text-white font-black text-lg tracking-tight">متجرنا<span class="text-red-600">.</span></span>
        </div>

        <div class="space-y-4 relative z-10">
            <h1 class="text-3xl font-black text-white leading-tight">
                انضم إلى <br>
                <span class="text-red-500">مستقبل التسوق</span> معنا اليوم.
            </h1>
            <p class="text-zinc-400 text-xs leading-relaxed max-w-sm">
                أنشئ حسابك المجاني الآن للوصول التام إلى العروض الحصرية، تتبع مشترياتك، وحفظ مفضلاتك في منصة واحدة ذكية.
            </p>
        </div>

        <div class="text-zinc-600 text-[11px] font-medium relative z-10">
            &copy; {{ date('Y') }} متجرنا. جميع الحقوق محفوظة.
        </div>
    </div>

    <div class="col-span-12 lg:col-span-7 flex flex-col justify-center items-center p-6 sm:p-12 bg-[#FAFAFA]">
        
        <div class="w-full max-w-md bg-white border border-zinc-200/80 rounded-2xl p-8 sm:p-10 shadow-sm relative">
            
            <a href="/" class="absolute top-6 left-6 text-zinc-400 hover:text-zinc-900 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </a>

            <div class="mb-6">
                <span class="lg:hidden inline-flex items-center gap-2 mb-4 text-xs font-bold text-zinc-900">
                    <span class="w-2 h-2 rounded-full bg-red-600"></span>
                    متجرنا
                </span>
                <h2 class="text-xl font-bold text-zinc-950 tracking-tight">إنشاء حساب جديد</h2>
                <p class="text-xs text-zinc-400 mt-1">ابدأ رحلتك معنا بملء البيانات التالية.</p>
            </div>

            @if(session()->has('message'))
                <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-xl p-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="font-semibold">{{ session('message') }}</span>
                </div>
            @endif

            <form wire:submit="register" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-xs font-bold text-zinc-700 mb-2">الاسم الكامل</label>
                    <input type="text" wire:model="name"
                           class="w-full px-4 py-3 rounded-xl bg-zinc-50 border border-zinc-200 text-xs focus:outline-none focus:border-red-600 focus:bg-white transition-all placeholder-zinc-400"
                           placeholder="أدخل اسمك الكامل">
                    
                    @error('name')
                        <p class="text-red-600 text-[11px] font-semibold mt-1.5 flex items-center gap-1">
                            <span class="w-1 h-1 rounded-full bg-red-600 inline-block"></span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-zinc-700 mb-2">البريد الإلكتروني</label>
                    <input type="email" wire:model="email"
                           class="w-full px-4 py-3 rounded-xl bg-zinc-50 border border-zinc-200 text-xs focus:outline-none focus:border-red-600 focus:bg-white transition-all placeholder-zinc-400"
                           placeholder="name@example.com">
                    
                    @error('email')
                        <p class="text-red-600 text-[11px] font-semibold mt-1.5 flex items-center gap-1">
                            <span class="w-1 h-1 rounded-full bg-red-600 inline-block"></span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-zinc-700 mb-2">كلمة المرور</label>
                    <input type="password" wire:model="password" required
                           class="w-full px-4 py-3 rounded-xl bg-zinc-50 border border-zinc-200 text-xs focus:outline-none focus:border-red-600 focus:bg-white transition-all placeholder-zinc-400"
                           placeholder="••••••••">
                    
                    @error('password')
                        <p class="text-red-600 text-[11px] font-semibold mt-1.5 flex items-center gap-1">
                            <span class="w-1 h-1 rounded-full bg-red-600 inline-block"></span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-zinc-700 mb-2">تأكيد كلمة المرور</label>
                    <input type="password" wire:model="password_confirmation" required
                           class="w-full px-4 py-3 rounded-xl bg-zinc-50 border border-zinc-200 text-xs focus:outline-none focus:border-red-600 focus:bg-white transition-all placeholder-zinc-400"
                           placeholder="••••••••">
                    
                    @error('password_confirmation')
                        <p class="text-red-600 text-[11px] font-semibold mt-1.5 flex items-center gap-1">
                            <span class="w-1 h-1 rounded-full bg-red-600 inline-block"></span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                
                <button type="submit" 
                        class="w-full bg-zinc-950 hover:bg-zinc-900 text-white font-bold py-3.5 px-4 rounded-xl text-xs transition-colors glow-effect mt-4 flex items-center justify-center gap-2">
                    <span>إنشاء الحساب</span>
                    <svg class="w-4 h-4 transform rotate-180" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </button>
            </form>
            
            <div class="mt-6 pt-5 border-t border-zinc-100 text-center">
                <p class="text-xs text-zinc-500">
                    لديك حساب بالفعل؟ 
                    <a href="/login" class="font-bold text-red-600 hover:underline">تسجيل الدخول</a>
                </p>
            </div>

        </div>
    </div>

</div>