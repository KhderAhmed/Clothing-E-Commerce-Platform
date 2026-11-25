<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatAnalytic extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'session_id', 'intent', 'user_message',
        'bot_reply', 'understood', 'response_time_ms', 'entities',
    ];

    protected $casts = [
        'understood' => 'boolean',
        'entities'   => 'array',
    ];
}