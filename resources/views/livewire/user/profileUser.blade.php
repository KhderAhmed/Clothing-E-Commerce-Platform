<div class="max-w-4xl mx-auto px-4 py-8 space-y-8 text-right" dir="rtl">
    
    <div class="border-b-2 border-zinc-300 dark:border-zinc-800 pb-5">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">إعدادات الحساب</h1>
        <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">قم بإدارة بياناتك الشخصية وأمان حسابك.</p>
    </div>

    <div class="bg-white dark:bg-[#09090B] border-2 border-zinc-300 dark:border-zinc-800 rounded-2xl p-6 shadow-sm">
        <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-5 flex items-center gap-2">
            <svg class="w-5 h-5 text-zinc-700 dark:text-zinc-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path>
            </svg>
            الملف الشخصي
        </h2>

        <form wire:submit.prevent="updateProfile" class="space-y-6">
            @if (session()->has('profile_success'))
                <div class="p-3 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 text-xs font-bold rounded-xl border-2 border-emerald-300 dark:border-emerald-800/50">
                    {{ session('profile_success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-zinc-900 dark:text-zinc-200">الاسم الكامل</label>
                    <input type="text" wire:model="name" class="w-full px-4 py-2.5 bg-white dark:bg-zinc-900 border-2 border-zinc-400 dark:border-zinc-700 rounded-xl text-sm font-semibold text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-600 transition-all shadow-sm">
                    @error('name') <span class="text-xs text-red-600 font-bold block mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-zinc-900 dark:text-zinc-200">البريد الإلكتروني</label>
                    <input type="email" wire:model="email" class="w-full px-4 py-2.5 bg-white dark:bg-zinc-900 border-2 border-zinc-400 dark:border-zinc-700 rounded-xl text-sm font-semibold text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-600 transition-all shadow-sm">
                    @error('email') <span class="text-xs text-red-600 font-bold block mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="px-6 py-2.5 bg-zinc-950 dark:bg-white text-white dark:text-zinc-950 font-bold text-xs rounded-xl hover:opacity-90 transition-all flex items-center gap-2 cursor-pointer disabled:opacity-50 shadow-md">
                    <span wire:loading wire:target="updateProfile" class="w-3.5 h-3.5 border-2 border-current border-t-transparent rounded-full animate-spin"></span>
                    <span>حفظ التعديلات</span>
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white dark:bg-[#09090B] border-2 border-zinc-300 dark:border-zinc-800 rounded-2xl p-6 shadow-sm">
        <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-5 flex items-center gap-2">
            <svg class="w-5 h-5 text-zinc-700 dark:text-zinc-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"></path>
            </svg>
            تغيير كلمة المرور
        </h2>

        <form wire:submit.prevent="updatePassword" class="space-y-6">
            @if (session()->has('password_success'))
                <div class="p-3 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 text-xs font-bold rounded-xl border-2 border-emerald-300 dark:border-emerald-800/50">
                    {{ session('password_success') }}
                </div>
            @endif

            <div class="space-y-2">
                <label class="text-xs font-bold text-zinc-900 dark:text-zinc-200">مفتاح المرور الحالي</label>
                <input type="password" wire:model="current_password" class="w-full max-w-md px-4 py-2.5 bg-white dark:bg-zinc-900 border-2 border-zinc-400 dark:border-zinc-700 rounded-xl text-sm font-semibold text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-600 transition-all shadow-sm">
                @error('current_password') <span class="text-xs text-red-600 font-bold block mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-zinc-900 dark:text-zinc-200">كلمة المرور الجديدة</label>
                    <input type="password" wire:model="password" class="w-full px-4 py-2.5 bg-white dark:bg-zinc-900 border-2 border-zinc-400 dark:border-zinc-700 rounded-xl text-sm font-semibold text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-600 transition-all shadow-sm">
                    @error('password') <span class="text-xs text-red-600 font-bold block mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-zinc-900 dark:text-zinc-200">تأكيد كلمة المرور الجديدة</label>
                    <input type="password" wire:model="password_confirmation" class="w-full px-4 py-2.5 bg-white dark:bg-zinc-900 border-2 border-zinc-400 dark:border-zinc-700 rounded-xl text-sm font-semibold text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-600 transition-all shadow-sm">
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="px-6 py-2.5 bg-red-600 text-white font-bold text-xs rounded-xl hover:bg-red-700 transition-all flex items-center gap-2 cursor-pointer disabled:opacity-50 shadow-md">
                    <span wire:loading wire:target="updatePassword" class="w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                    <span>تحديث كلمة المرور</span>
                </button>
            </div>
        </form>
    </div>
</div>