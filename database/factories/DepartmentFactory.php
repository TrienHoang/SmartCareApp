<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'description' => fake()->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
            'slug' => fake()->slug(),
        ];
    }
}

