<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? 1,
            'title' => fake()->sentence(),
            'message' => fake()->paragraph(),
            'is_read' => fake()->boolean(),
            'created_at' => now()
        ];
    }
}

