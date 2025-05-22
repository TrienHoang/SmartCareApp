<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;

class TreatmentPlanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'patient_id' => User::inRandomOrder()->first()?->id ?? 1,
            'doctor_id' => Doctor::inRandomOrder()->first()?->id ?? 1,
            'plan_title' => 'Điều trị ' . fake()->word(),
            'total_estimated_cost' => fake()->randomFloat(2, 500, 2000),
            'notes' => fake()->optional()->sentence(),
            'created_at' => now()
        ];
    }
}

