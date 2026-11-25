<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    protected $fillable = [
        'user_id', 'preferred_size', 'favorite_colors',
        'preferred_style', 'typical_budget', 'body_type',
        'interactions_count', 'last_seen_at',
    ];

    protected $casts = [
        'favorite_colors' => 'array',
        'last_seen_at'    => 'datetime',
        'typical_budget'  => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * إضافة لون للمفضلة (بدون تكرار، بحد أقصى 5)
     */
    public function addFavoriteColor(string $color): void
    {
        $colors = $this->favorite_colors ?? [];
        if (!in_array($color, $colors)) {
            array_unshift($colors, $color);
            $this->favorite_colors = array_slice(array_unique($colors), 0, 5);
        }
    }
}