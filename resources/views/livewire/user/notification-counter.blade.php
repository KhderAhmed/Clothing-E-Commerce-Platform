<button wire:click="markAsRead"
   class="relative p-2 text-zinc-700 dark:text-zinc-300 hover:text-red-600 rounded-xl transition-all cursor-pointer group">
    
    <svg class="w-5 h-5 transition-transform group-hover:rotate-12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" 
              d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a3 3 0 11-5.714 0m5.714 0a3 3 0 11-5.714 0M3.124 7.5A8.969 8.969 0 015.292 3m13.416 0a8.969 8.969 0 012.168 4.5"></path>
    </svg>
    
    @if($unreadCount > 0)
        <span class="absolute top-1 left-1 bg-red-600 text-white text-[9px] font-black w-3.5 h-3.5 rounded-full flex items-center justify-center ring-2 ring-white dark:ring-[#09090B] animate-pulse">
            {{ $unreadCount }}
        </span>
    @endif
</button>