<?php

namespace Database\Factories;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorLeaveFactory extends Factory
{
    public function definition(): array
    {
        return [
            'doctor_id' => Doctor::inRandomOrder()->first()?->id ?? 1,
            'start_date' => fake()->dateTimeBetween('-1 week', 'now'),
            'end_date' => fake()->dateTimeBetween('now', '+1 week'),
            'reason' => fake()->sentence(),
            'created_at' => now(),
            'approved' => fake()->boolean()
        ];
    }
}
