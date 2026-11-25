<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserPreferenceFactory extends Factory
{
    protected $model = UserPreference::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'preferred_size' => $this->faker->randomElement(['S', 'M', 'L', 'XL', 'XXL']),
            'favorite_colors' => $this->faker->randomElements(['أسود', 'أبيض', 'أزرق', 'أحمر', 'رمادي'], 2),
            'preferred_style' => $this->faker->randomElement(['كاجوال', 'رسمي', 'رياضي', 'سهرة']),
            'typical_budget' => $this->faker->randomFloat(2, 100, 800),
            'body_type' => $this->faker->randomElement(['عادي', 'نحيف', 'متناسق', 'رياضي']),
            'interactions_count' => $this->faker->numberBetween(1, 20),
            'last_seen_at' => now(),
        ];
    }
}