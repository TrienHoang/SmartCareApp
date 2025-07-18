<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SpecialtyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word() . ' học',
            'description' => $this->faker->sentence(6),
        ];
    }
}
