<button type="button"
        @click="$dispatch('trigger-logout')"
        class="w-full flex items-center gap-2 px-4 py-2.5 text-xs font-semibold text-red-600 hover:bg-red-50/50 dark:hover:bg-red-950/20 rounded-xl transition-all group cursor-pointer text-right border-none bg-transparent">
    <svg class="w-4 h-4 text-red-500 group-hover:translate-x-1 transition-transform shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"></path>
    </svg>
    <span>تسجيل الخروج</span>
</button>