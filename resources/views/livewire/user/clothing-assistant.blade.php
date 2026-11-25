<div>
    {{-- ═══ الزر العائم ═══ --}}
    @if (!$isOpen)
        <button wire:click="toggle"
            class="fixed bottom-6 left-6 z-50 w-14 h-14 rounded-full bg-[#f53003] hover:bg-[#d42902] text-white shadow-lg shadow-[#f53003]/30 flex items-center justify-center transition-all hover:scale-110">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
            </svg>
            <span class="absolute -top-1 -right-1 w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></span>
        </button>
    @endif

    {{-- ═══ نافذة الشات ═══ --}}
    @if ($isOpen)
        <div class="fixed bottom-6 left-6 z-50 w-[380px] max-w-[calc(100vw-3rem)] h-[620px] max-h-[calc(100vh-3rem)] bg-white dark:bg-[#0d0d10] rounded-2xl shadow-2xl border border-zinc-200 dark:border-zinc-800 flex flex-col overflow-hidden"
             x-data
             x-init="$nextTick(() => { $refs.scroll.scrollTop = $refs.scroll.scrollHeight; })"
             @message-added.window="$nextTick(() => { $refs.scroll.scrollTop = $refs.scroll.scrollHeight; })"
             @beforeunload.window="if (@js(count($messages)) > 2) { @this.call('goodbyeNudge') }">

            {{-- الترويسة --}}
            <div class="shrink-0 bg-gradient-to-l from-[#f53003] to-[#d42902] px-4 py-3 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-white/20 backdrop-blur flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-black text-sm leading-tight">مساعد الأزياء</p>
                        <p class="text-white/70 text-[10px] font-bold flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
                            متصل • يرد فوراً
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-1">
                    <button wire:click="resetChat" wire:confirm="بدء محادثة جديدة؟"
                        class="p-1.5 rounded-lg text-white/80 hover:text-white hover:bg-white/10 transition-colors"
                        title="محادثة جديدة">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </button>
                    <button wire:click="toggle"
                        class="p-1.5 rounded-lg text-white/80 hover:text-white hover:bg-white/10 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- الرسائل --}}
            <div x-ref="scroll"
                class="flex-1 overflow-y-auto p-4 space-y-3 bg-zinc-50/50 dark:bg-[#0a0a0c]"
                style="scrollbar-width: thin;">

                @foreach ($messages as $i => $message)
                    <div wire:key="msg-{{ $i }}" class="flex {{ $message['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[88%] space-y-2">
                            <div class="rounded-2xl px-3.5 py-2.5 text-sm leading-relaxed
                                {{ $message['role'] === 'user'
                                    ? 'bg-[#f53003] text-white rounded-br-sm'
                                    : 'bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 border border-zinc-200 dark:border-zinc-800 rounded-bl-sm' }}">
                                {!! nl2br(e($message['content'])) !!}
                            </div>

                            {{-- المنتجات --}}
                            @if (!empty($message['products']))
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach ($message['products'] as $product)
                                        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl overflow-hidden hover:border-[#f53003] transition-colors group">
                                            <a href="{{ url('/product/' . $product['id']) }}">
                                                @if (!empty($product['image']))
                                                    <img src="{{ asset('storage/' . $product['image']) }}"
                                                        class="w-full h-20 object-cover group-hover:scale-105 transition-transform">
                                                @else
                                                    <div class="w-full h-20 bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-2xl">
                                                        👕
                                                    </div>
                                                @endif
                                            </a>
                                            <div class="p-2">
                                                <p class="text-[11px] font-bold text-zinc-900 dark:text-white truncate">
                                                    {{ $product['name'] }}
                                                </p>
                                                <p class="text-[11px] font-black text-[#f53003] mt-1">
                                                    {{ number_format($product['price'], 2) }} د.ل
                                                </p>
                                                <button wire:click="addToCart({{ $product['id'] }})"
                                                    type="button"
                                                    class="w-full mt-1.5 text-[10px] font-bold py-1 bg-zinc-950 dark:bg-white text-white dark:text-zinc-950 rounded-md hover:bg-emerald-500 dark:hover:bg-emerald-500 dark:hover:text-white transition-colors">
                                                    🛒 أضف للسلة
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- أزرار إتمام المهمة --}}
                                @if (count($message['products']) > 1)
                                    <div class="flex gap-2">
                                        <button wire:click="addAllToCart({{ json_encode(array_column($message['products'], 'id')) }})"
                                            type="button"
                                            class="flex-1 text-[11px] font-bold py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition-colors">
                                            🛒 أضف الكل
                                        </button>
                                        <button wire:click="useSuggestion('شوف بدائل')"
                                            type="button"
                                            class="flex-1 text-[11px] font-bold py-1.5 bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors">
                                            🔄 بدائل
                                        </button>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach

                {{-- ⭐ بطاقة التقييم --}}
                @if ($showRating && $rating === null)
                    <div class="bg-white dark:bg-zinc-900 border-2 border-[#f53003]/20 rounded-2xl p-4 text-center space-y-3">
                        <p class="text-sm font-bold text-zinc-900 dark:text-white">
                            كيف كانت تجربتك؟ 🌟
                        </p>
                        <div class="flex justify-center gap-1.5">
                            @for ($i = 1; $i <= 5; $i++)
                                <button wire:click="rateExperience({{ $i }})"
                                    type="button"
                                    class="text-2xl hover:scale-125 transition-transform">
                                    ⭐
                                </button>
                            @endfor
                        </div>
                        <button wire:click="skipRating" type="button"
                            class="text-[11px] font-bold text-zinc-400 hover:text-zinc-600 transition-colors">
                            لاحقاً
                        </button>
                    </div>
                @endif

                {{-- مؤشر الكتابة --}}
                @if ($isLoading)
                    <div class="flex justify-start">
                        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl rounded-bl-sm px-4 py-3 flex items-center gap-1.5">
                            <span class="w-2 h-2 bg-[#f53003] rounded-full animate-bounce" style="animation-delay: 0s"></span>
                            <span class="w-2 h-2 bg-[#f53003] rounded-full animate-bounce" style="animation-delay: 0.15s"></span>
                            <span class="w-2 h-2 bg-[#f53003] rounded-full animate-bounce" style="animation-delay: 0.3s"></span>
                        </div>
                    </div>
                @endif
            </div>

            {{-- اقتراحات --}}
            @if (!empty($suggestions) && !$isLoading && !$showRating)
                <div class="shrink-0 px-3 py-2 border-t border-zinc-200 dark:border-zinc-800 flex gap-1.5 overflow-x-auto bg-white dark:bg-[#0d0d10]"
                    style="scrollbar-width: none;">
                    @foreach ($suggestions as $sug)
                        <button wire:click="useSuggestion('{{ $sug }}')" type="button"
                            class="shrink-0 text-[11px] font-bold px-3 py-1.5 bg-zinc-100 dark:bg-zinc-900 hover:bg-[#f53003] hover:text-white text-zinc-700 dark:text-zinc-300 rounded-full transition-colors">
                            {{ $sug }}
                        </button>
                    @endforeach
                </div>
            @endif

            {{-- الإدخال --}}
            <form wire:submit.prevent="sendMessage"
                class="shrink-0 p-3 border-t border-zinc-200 dark:border-zinc-800 bg-white dark:bg-[#0d0d10]">
                <div class="flex items-end gap-2">
                    <textarea wire:model="userMessage" rows="1" placeholder="اكتب رسالتك..."
                        wire:keydown.enter.prevent="sendMessage"
                        class="flex-1 bg-zinc-50 dark:bg-zinc-900 text-sm text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 px-3.5 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-800 focus:outline-none focus:border-[#f53003] focus:ring-2 focus:ring-[#f53003]/10 transition-all resize-none"
                        style="min-height: 42px;"></textarea>

                    <button type="submit" wire:loading.attr="disabled" wire:target="sendMessage"
                        class="shrink-0 w-10 h-10 rounded-xl bg-[#f53003] hover:bg-[#d42902] text-white flex items-center justify-center transition-colors disabled:opacity-50">
                        <svg wire:loading.remove wire:target="sendMessage" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                        </svg>
                        <svg wire:loading wire:target="sendMessage" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </button>
                </div>

                @error('userMessage')
                    <p class="text-[#f53003] text-xs mt-1.5 font-bold">⚠️ {{ $message }}</p>
                @enderror
            </form>
        </div>
    @endif

    {{-- ═══ Voice Input (اختياري) ═══ --}}
    <script>
        window.startVoiceInput = function() {
            const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
            if (!SR) {
                alert('متصفحك لا يدعم الإدخال الصوتي');
                return;
            }
            const rec = new SR();
            rec.lang = 'ar-LY';
            rec.onresult = (e) => {
                const text = e.results[0][0].transcript;
                @this.set('userMessage', text);
                setTimeout(() => @this.call('sendMessage'), 200);
            };
            rec.start();
        };
    </script>
</div>