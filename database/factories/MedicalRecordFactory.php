<?php
namespace Database\Factories;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicalRecordFactory extends Factory
{
    public function definition(): array
    {
        return [
            'appointment_id' => Appointment::inRandomOrder()->first()?->id ?? 1,
            'symptoms' => fake()->sentence(),
            'diagnosis' => fake()->words(3, true),
            'treatment' => fake()->sentence(),
            'notes' => fake()->optional()->paragraph(),
            'created_at' => now()
        ];
    }
}
    
