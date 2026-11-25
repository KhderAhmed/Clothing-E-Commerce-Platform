<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatOutcome extends Model
{
     use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'found_product',
        'added_to_cart',
        'completed_purchase',
        'rating',
        'feedback',
    ];

    protected $casts = [
        'found_product'      => 'boolean',
        'added_to_cart'      => 'boolean',
        'completed_purchase' => 'boolean',
    ];
}
