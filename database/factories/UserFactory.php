<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'username'      => $this->faker->unique()->userName(),
            'password'      => Hash::make('password'), // ✅ chuẩn Laravel
            'full_name'     => $this->faker->name(),
            'phone'         => $this->faker->phoneNumber(),
            'email'         => $this->faker->unique()->safeEmail(),
            'facebook_id'   => $this->faker->uuid(),
            'google_id'     => $this->faker->uuid(),
            'gender'        => $this->faker->randomElement(['Nam', 'Nữ']),
            'date_of_birth' => $this->faker->date('Y-m-d', '-18 years'),
            'address'       => $this->faker->address(),
            'role_id'       => Role::inRandomOrder()->value('id') ?? 1, // ✅ .value() ngắn gọn
            'avatar'        => $this->faker->imageUrl(200, 200, 'people'),
            'created_at'    => now(),
            'updated_at'    => now(),
        ];
    }
}
