<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentLogFactory extends Factory
{
    public function definition(): array
    {
        return [
            'appointment_id' => Appointment::inRandomOrder()->first()?->id ?? 1,
            'changed_by' => User::inRandomOrder()->first()?->id ?? 1,
            'status_before' => fake()->randomElement(['pending', 'confirmed']),
            'status_after' => fake()->randomElement(['confirmed', 'cancelled', 'completed']),
            'change_time' => now(),
            'note' => fake()->sentence(),
        ];
    }
}

