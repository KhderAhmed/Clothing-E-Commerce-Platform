<?php

namespace Database\Factories;

use App\Models\ChatAnalytic;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatAnalyticFactory extends Factory
{
    protected $model = ChatAnalytic::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'session_id' => $this->faker->uuid(),
            'intent' => 'search_product',
            'user_message' => 'أبحث عن قميص رجالي',
            'bot_reply' => 'إليك الخيارات المتاحة',
            'understood' => true,
            'response_time_ms' => 200,
            'entities' => json_encode(['color' => 'أسود']),
        ];
    }
}