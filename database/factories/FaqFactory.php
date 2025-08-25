<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FaqFactory extends Factory
{
    public function definition(): array
    {
        return [
            'question' => fake()->sentence() . '?',
            'service_category_id' => fake()->numberBetween(1, 10), // Assuming you have 10 service categories
            'display_order' => fake()->numberBetween(1, 100), // Random display
            'answer' => fake()->paragraph(),
            'is_active' => fake()->boolean()
        ];
    }
}
