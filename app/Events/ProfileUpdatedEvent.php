<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProfileUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $name;

    public function __construct($userId, $name)
    {
        $this->userId = $userId;
        $this->name = $name;
    }

    // سنبث عبر قناة عامة مؤقتاً لتطابق كود المنتجات لديك، مع فحص الـ ID بالجافاسكريبت
    public function broadcastOn(): array
    {
        return [
            new Channel('user-updates'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'ProfileUpdatedEvent';
    }
}