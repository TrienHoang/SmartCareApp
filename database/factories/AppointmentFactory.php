<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'patient_id' => User::inRandomOrder()->first()?->id ?? 1,
            'doctor_id' => Doctor::inRandomOrder()->first()?->id ?? 1,
            'service_id' => Service::inRandomOrder()->first()?->id ?? 1,
            'appointment_time' => fake()->dateTimeBetween('now', '+10 days'),
            'end_time' => fn (array $attributes) => Carbon::instance($attributes['appointment_time'])->addMinutes(30),
            'status' => fake()->randomElement(['pending', 'confirmed', 'cancelled', 'completed']),
            'reason' => fake()->sentence(),
            'cancel_reason' => fake()->optional()->sentence(),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}

