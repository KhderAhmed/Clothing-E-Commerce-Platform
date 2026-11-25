<?php


namespace App\Livewire\User;

use App\Models\Rating;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class RatingStars extends Component
{
    public $productId;
    public $averageRating = 0;
    public $totalRatings = 0;
    public $userRating = 0;
    public $showModal = false;
    public $selectedRating = 0;
    public $reviewText = '';
    
    public function mount($productId)
    {
        $this->productId = $productId;
        $this->loadRatings();
    }
    
    public function loadRatings()
    {
        $product = Product::find($this->productId);
        
        if ($product) {
            // حساب متوسط التقييمات
            $this->averageRating = $product->ratings()->avg('rating') ?? 0;
            $this->totalRatings = $product->ratings()->count();
            
            // تقييم المستخدم الحالي
            if (Auth::check()) {
                $userRating = $product->ratings()
                    ->where('user_id', Auth::id())
                    ->first();
                    
                if ($userRating) {
                    $this->userRating = $userRating->rating;
                    $this->reviewText = $userRating->review ?? '';
                }
            }
        }
    }
    
    public function openModal($rating)
    {
        if (!Auth::check()) {
            $this->dispatch('showNotification', message: '⚠️ الرجاء تسجيل الدخول', type: 'warning');
            return;
        }
        
        $this->selectedRating = $rating;
        $this->showModal = true;
    }
    
    public function saveRating()
    {
        if (!Auth::check()) {
            return;
        }
        
        try {
            Rating::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'product_id' => $this->productId,
                ],
                [
                    'rating' => $this->selectedRating,
                    'review' => $this->reviewText,
                ]
            );
            
            // إعادة تحميل البيانات
            $this->loadRatings();
            $this->showModal = false;
            $this->selectedRating = 0;
            
            $this->dispatch('showNotification', message: '⭐ تم حفظ تقييمك بنجاح', type: 'success');
            $this->dispatch('ratingUpdated');
            
        } catch (\Exception $e) {
            $this->dispatch('showNotification', message: '❌ حدث خطأ: ' . $e->getMessage(), type: 'error');
        }
    }
    
    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedRating = 0;
        $this->reviewText = '';
    }
    
    public function render()
    {
        return view('livewire.user.rating-stars', [
            'averageRating' => round($this->averageRating, 1),
            'totalRatings' => $this->totalRatings,
            'userRating' => $this->userRating,
        ]);
    }
}