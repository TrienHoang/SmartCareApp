<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EducationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'degree' => $this->faker->randomElement(['Bác sĩ Y khoa', 'Thạc sĩ Y học', 'Tiến sĩ Y học']),
            'school' => $this->faker->randomElement(['Đại học Y Hà Nội', 'Đại học Y Dược TP.HCM']),
            'start_year' => $this->faker->year(),
            'end_year' => $this->faker->year(),
            'description' => $this->faker->optional()->sentence(),
        ];
    }
}

