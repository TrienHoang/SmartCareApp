<?php
namespace Database\Factories;

use App\Models\ServiceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'service_cate_id' => ServiceCategory::inRandomOrder()->first()?->id ?? 1,
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 100, 500),
            'duration' => fake()->numberBetween(15, 90),
            'status' => fake()->randomElement(['active', 'inactive']),
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null
        ];
    }
}
