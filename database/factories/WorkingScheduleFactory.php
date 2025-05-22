<?php
namespace Database\Factories;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkingScheduleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'doctor_id' => Doctor::inRandomOrder()->first()?->id ?? 1,
            'day_of_week' => fake()->dayOfWeek(),
            'start_time' => '08:00',
            'end_time' => '17:00',
        ];
    }
}
