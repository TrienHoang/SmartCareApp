<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Promotion;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'appointment_id' => Appointment::inRandomOrder()->first()?->id ?? 1,
            'promotion_id' => Promotion::inRandomOrder()->first()?->id ?? null,
            'amount' => fake()->randomFloat(2, 100, 500),
            'payment_method' => fake()->randomElement(['cash', 'card', 'momo']),
            'status' => fake()->randomElement(['paid', 'pending']),
            'paid_at' => now()
        ];
    }
}

