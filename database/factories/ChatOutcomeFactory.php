<?php

namespace Database\Factories;

use App\Models\ChatOutcome;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatOutcomeFactory extends Factory
{
    protected $model = ChatOutcome::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'session_id' => $this->faker->uuid(),
            'found_product' => true,
            'added_to_cart' => true,
            'completed_purchase' => false,
            'rating' => 5,
            'feedback' => 'ممتاز جداً',
        ];
    }
}