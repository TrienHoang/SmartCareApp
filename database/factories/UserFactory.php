<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'username' => fake()->unique()->userName(),
            'password' => bcrypt('password'), // hoặc Hash::make
            'full_name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail,
            'facebook_id' => fake()->uuid(),
            'gender' => fake()->randomElement(['Nam', 'Nữ']),
            'date_of_birth' => fake()->date(),
            'address' => fake()->address(),
            'role_id' => Role::inRandomOrder()->first()?->id ?? 1,
            'avatar' => fake()->imageUrl(200, 200, 'people'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

