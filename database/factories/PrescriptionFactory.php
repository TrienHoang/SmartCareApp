<?php

namespace Database\Factories;

use App\Models\MedicalRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrescriptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'medical_record_id' => MedicalRecord::inRandomOrder()->first()?->id ?? 1,
            'prescribed_at' => now(),
            'notes' => fake()->sentence()
        ];
    }
}

