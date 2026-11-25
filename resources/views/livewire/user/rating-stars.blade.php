{{-- resources/views/livewire/rating-stars.blade.php --}}

<div class="relative">
    <!-- عرض النجوم -->
    <div class="flex items-center gap-2">
        <div class="flex gap-1">
            @for ($i = 1; $i <= 5; $i++)
                <button 
                    wire:click="openModal({{ $i }})"
                    type="button"
                    class="focus:outline-none transition-transform hover:scale-110">
                    <svg class="w-5 h-5 {{ $userRating >= $i ? 'text-yellow-500' : 'text-gray-300 dark:text-gray-600' }}" 
                         fill="{{ $userRating >= $i ? 'currentColor' : 'none' }}" 
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                              d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                </button>
            @endfor
        </div>
        
        @if($totalRatings > 0)
            <div class="flex items-center gap-1">
                <span class="text-sm font-bold text-gray-900 dark:text-white">{{ number_format($averageRating, 1) }}</span>
                <span class="text-xs text-gray-500">({{ $totalRatings }} {{ $totalRatings == 1 ? 'تقييم' : 'تقييمات' }})</span>
            </div>
        @else
            <span class="text-xs text-gray-400">لا توجد تقييمات</span>
        @endif
    </div>

    <!-- مودال التقييم -->
    @if($showModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" 
             style="position: fixed; top: 0; left: 0; right: 0; bottom: 0;">
            <div class="bg-white dark:bg-gray-900 rounded-2xl max-w-md w-full p-6 shadow-2xl">
                
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                        {{ $userRating > 0 ? 'تعديل التقييم' : 'تقييم المنتج' }}
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- اختيار النجوم في المودال -->
                <div class="flex justify-center gap-3 mb-6">
                    @for ($i = 1; $i <= 5; $i++)
                        <button 
                            wire:click="selectedRating = {{ $i }}"
                            type="button"
                            class="transition-all hover:scale-110">
                            <svg class="w-10 h-10 {{ $selectedRating >= $i ? 'text-yellow-500' : 'text-gray-300' }}" 
                                 fill="{{ $selectedRating >= $i ? 'currentColor' : 'none' }}" 
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" 
                                      d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                        </button>
                    @endfor
                </div>
                
                <!-- حقل التعليق -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        اكتب رأيك (اختياري)
    </label>
                    <textarea 
                        wire:model="reviewText"
                        rows="3"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                        placeholder="شاركنا تجربتك مع هذا المنتج..."></textarea>
                </div>
                
                <!-- الأزرار -->
                <div class="flex gap-3">
                    <button 
                        wire:click="saveRating"
                        class="flex-1 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-bold py-2 px-4 rounded-lg transition-all">
                        {{ $userRating > 0 ? 'تحديث' : 'إرسال' }}
                    </button>
                    <button 
                        wire:click="closeModal"
                        class="px-6 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-all">
                        إلغاء
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>