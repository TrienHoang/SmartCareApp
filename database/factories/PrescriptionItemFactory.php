<?php

namespace Database\Factories;

use App\Models\Prescription;
use App\Models\Medicine;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrescriptionItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'prescription_id' => Prescription::inRandomOrder()->first()?->id ?? 1,
            'medicine_id' => Medicine::inRandomOrder()->first()?->id ?? 1,
            'quantity' => fake()->numberBetween(1, 10),
            'usage_instructions' => fake()->sentence()
        ];
    }
}

