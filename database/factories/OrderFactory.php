<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'customer_name' => $this->faker->name(),
            'customer_phone' => $this->faker->phoneNumber(),
            'city' => $this->faker->city(),
            'address' => $this->faker->address(),
            'payment_method' => 'cod',
            'subtotal' => 100.00,
            'tax' => 5.00,
            'total' => 105.00,
            'status' => 'pending',
        ];
    }
}