<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $price = $this->faker->randomFloat(2, 50, 1000);
        $hasDiscount = $this->faker->boolean(30); // 30% فرصة وجود خصم

        return [
            'name' => $this->faker->unique()->words(3, true),
            'description' => $this->faker->paragraph(),
            'price' => $price,
            'discount' => $hasDiscount ? $this->faker->randomFloat(2, 5, 50) : 0,
            'discount_expires_at' => $hasDiscount ? $this->faker->dateTimeBetween('now', '+1 month') : null,
            'status' => $this->faker->randomElement(['draft', 'published']),
            'created_by' => User::factory(), // سينشئ مستخدم تلقائياً إذا لم يُحدد
        ];
    }

    /**
     * حالة خاصة: منتج منشور دائماً
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
        ]);
    }

    /**
     * حالة خاصة: منتج بخصم نشط
     */
    public function withDiscount(): static
    {
        return $this->state(fn (array $attributes) => [
            'discount' => 20.00,
            'discount_expires_at' => now()->addDays(10),
        ]);
    }
}