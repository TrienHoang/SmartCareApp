<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PromotionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->bothify('PROMO##??')),
            'description' => fake()->sentence(),
            'discount_percentage' => fake()->randomFloat(2, 5, 50),
            'valid_from' => now(),
            'valid_until' => now()->addDays(30)
        ];
    }
}

