<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;

class FileUploadFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? 1,
            'appointment_id' => Appointment::inRandomOrder()->first()?->id ?? 1,
            'file_name' => fake()->word() . '.pdf',
            'file_path' => 'uploads/' . fake()->uuid() . '.pdf',
            'file_category' => fake()->randomElement(['xray', 'form', 'prescription']),
            'uploaded_at' => now()
        ];
    }
}

