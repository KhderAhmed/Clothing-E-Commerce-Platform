<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price'
    ];

    // علاقة العنصر مع الطلب الرئيسي التابع له
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // علاقة العنصر مع المنتج الأصلي
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}