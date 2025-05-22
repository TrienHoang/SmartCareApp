<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;

class TreatmentHistoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'patient_id' => User::inRandomOrder()->first()?->id ?? 1,
            'doctor_id' => Doctor::inRandomOrder()->first()?->id ?? 1,
            'treatment_description' => fake()->sentence(),
            'treatment_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'created_at' => now()
        ];
    }
}

