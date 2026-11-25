<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'discount',
        'discount_expires_at',
        'status',
        'created_by',
        'image'
    ];

    protected $casts = [
        'discount' => 'decimal:2',
        'discount_expires_at' => 'datetime',
        'price' => 'decimal:2',
    ];

    // العلاقات
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'wishlists');
    }
    // // هل المنتج مخفض حالياً؟
    // public function getIsDiscountedAttribute(): bool
    // {
    //     if (!$this->discount || $this->discount <= 0) {
    //         return false;
    //     }

    //     if ($this->discount_expires_at && $this->discount_expires_at->isPast()) {
    //         return false;
    //     }

    //     return true;
    // }

    // السعر بعد الخصم
    public function getFinalPriceAttribute(): float
    {
        if (!$this->is_discounted) {
            return (float) $this->price;
        }

        $discountAmount = $this->price * ($this->discount / 100);
        return round($this->price - $discountAmount, 2);
    }
    public function getIsDiscountedAttribute(): bool
{
    return $this->discount && $this->discount > 0;
}

// وأضف هاي
public function getIsDiscountExpiredAttribute(): bool
{
    return $this->discount_expires_at 
        && $this->discount_expires_at->isPast();
}

    // نسبة الخصم الظاهرة في الواجهة
    public function getDiscountPercentageAttribute(): int
    {
        return (int) round($this->discount ?? 0);
    }
    // علاقات التقييمات
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function userRating()
    {
        return $this->hasOne(Rating::class)->where('user_id', auth()->id());
    }

    // حساب متوسط التقييم
    public function getAverageRatingAttribute()
    {
        return round($this->ratings()->avg('rating') ?? 0, 1);
    }

    // عدد التقييمات
    public function getRatingsCountAttribute()
    {
        return $this->ratings()->count();
    }

    // توزيع التقييمات
    public function getRatingDistributionAttribute()
    {
        $distribution = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];

        $ratings = $this->ratings()
            ->select('rating', \DB::raw('count(*) as count'))
            ->groupBy('rating')
            ->get();

        foreach ($ratings as $rating) {
            $distribution[$rating->rating] = $rating->count;
        }

        return $distribution;
    }


    // هل المستخدم قيم المنتج
    public function getIsRatedByUserAttribute()
    {
        if (!auth()->check()) return false;
        return $this->ratings()->where('user_id', auth()->id())->exists();
    }

    // تقييم المستخدم الحالي
    public function getUserRatingAttribute()
    {
        if (!auth()->check()) return null;
        return $this->ratings()->where('user_id', auth()->id())->first();
    }
    // هل الخصم منتهي؟
    public function getDiscountExpiredAttribute(): bool
    {
        if (!$this->discount_expires_at) {
            return false;
        }
        return $this->discount_expires_at->isPast();
    }
    public function getIsFavoritedAttribute(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        // استخدام cache لتجنب الاستعلامات المتكررة
        $favorites = cache()->remember('user_favorites_' . auth()->id(), 300, function () {
            return Wishlist::where('user_id', auth()->id())->pluck('product_id')->toArray();
        });

        return in_array($this->id, $favorites);
    }
 public function variants()
{
    return $this->hasMany(ProductVariant::class);
}
}
