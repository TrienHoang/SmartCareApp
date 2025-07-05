<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Review;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Service;
use App\Models\Appointment;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'appointment_id' => Appointment::inRandomOrder()->first()?->id ?? 1,
            'patient_id'     => User::inRandomOrder()->first()?->id ?? 1,
            'doctor_id'      => Doctor::inRandomOrder()->first()?->id ?? 1,
            'service_id'     => Service::inRandomOrder()->first()?->id ?? 1,
            'rating'         => rand(1, 5),
            'comment'        => fake()->sentence(),
            'is_visible'     => fake()->boolean(80),
        ];
    }
}
