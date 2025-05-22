<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Room;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? 1,
            'room_id' => Room::inRandomOrder()->first()?->id ?? 1,
            'department_id' => Department::inRandomOrder()->first()?->id ?? 1,
            'specialization' => fake()->jobTitle(),
            'biography' => fake()->paragraph(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

