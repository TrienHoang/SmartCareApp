<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentHistoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'payment_id' => Payment::inRandomOrder()->first()?->id ?? 1,
            'amount' => fake()->randomFloat(2, 100, 500),
            'payment_method' => fake()->randomElement(['cash', 'card']),
            'payment_date' => now(),
        ];
    }
}

