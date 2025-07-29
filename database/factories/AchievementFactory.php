<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Achievement>
 */
class AchievementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Không cần 'doctor_id' ở đây vì bạn đã set trong Seeder
            'title'        => $this->faker->sentence(4),
            'organization' => $this->faker->company(), // ví dụ: WHO, Bộ Y Tế
            'year'         => $this->faker->year(),    // đúng định dạng cột
            'description'  => $this->faker->optional()->paragraph(2),
        ];
    }
}
