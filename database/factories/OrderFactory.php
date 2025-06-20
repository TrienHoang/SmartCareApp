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
            'user_id' => User::inRandomOrder()->first()?->id ?? 1,
            'appointment_id' => null,
            'payment_id' => null,
            'total_amount' => 0, // sẽ cập nhật lại sau khi attach services
            'status' => fake()->randomElement(['pending', 'paid', 'completed', 'cancelled']),
            'ordered_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
