<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MedicineFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => ucfirst(fake()->word()) . 'max',
            'description' => fake()->sentence(),
            'unit' => fake()->randomElement(['viên', 'ml', 'gói']),
            'dosage' => fake()->randomElement(['1 viên/ngày', '2 viên/ngày', '5ml/lần']),
            'price' => fake()->randomFloat(2, 10, 100),
            'created_at' => now()
        ];
    }
}

