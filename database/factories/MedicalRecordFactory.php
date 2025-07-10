<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\MedicalRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicalRecordFactory extends Factory
{
    protected $model = MedicalRecord::class;

    public function definition(): array
    {
        return [
            'appointment_id' => Appointment::inRandomOrder()->first()?->id ?? 1,
            'symptoms'      => $this->faker->sentence(),
            'diagnosis'     => $this->faker->words(3, true),
            'treatment'     => $this->faker->sentence(),
            'notes'         => $this->faker->optional()->paragraph(),
            'created_at'    => now(),
            'updated_at'    => now(),
        ];
    }
}
