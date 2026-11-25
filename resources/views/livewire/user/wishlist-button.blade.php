<button type="button" wire:click="toggle" wire:loading.attr="disabled" wire:target="toggle"
    class="wishlist-btn absolute top-3.5 left-3.5 z-10 p-2.5 backdrop-blur-md rounded-xl border transition-all duration-300
        cursor-pointer hover:scale-110 active:scale-95
        disabled:opacity-60 disabled:cursor-not-allowed disabled:hover:scale-100
        {{ $isFavorited
            ? 'text-red-500 bg-red-50/90 dark:bg-red-950/30 border-red-200 dark:border-red-800/50'
            : 'text-zinc-400 bg-white/90 dark:bg-zinc-900/90 hover:text-red-500 border-zinc-200/40 dark:border-zinc-800/40 hover:border-red-200 dark:hover:border-red-800/50' }}"
    aria-label="{{ $isFavorited ? 'إزالة من المفضلة' : 'إضافة إلى المفضلة' }}"
    title="{{ $isFavorited ? 'إزالة من المفضلة' : 'إضافة إلى المفضلة' }}">

    <div class="flex items-center gap-1.5">

        {{-- Heart --}}
        <svg wire:loading.class="opacity-40" wire:target="toggle" class="w-3.5 h-3.5 transition-all duration-300"
            fill="{{ $isFavorited ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.5"
            viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                clip-rule="evenodd"></path>
        </svg>

        {{-- Loading --}}
        <svg wire:loading wire:target="toggle" class="w-3 h-3 animate-spin text-current" fill="none"
            viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
            </circle>

            <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
        </svg>

    </div>
</button>
