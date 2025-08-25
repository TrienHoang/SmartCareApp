<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Experience>
 */
class ExperienceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'position'     => $this->faker->jobTitle(),
            'institution'  => $this->faker->company(), // Ví dụ: "Bệnh viện Bạch Mai"
            'start_year'   => $this->faker->year(),
            'end_year'     => $this->faker->optional()->year(),
            'description'  => $this->faker->optional()->paragraph(),
        ];
    }
}
