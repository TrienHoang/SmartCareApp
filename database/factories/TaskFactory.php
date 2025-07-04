<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['moi_tao', 'dang_lam', 'hoan_thanh', 'tre_han'];
        $priorities = ['thap', 'trung_binh', 'cao'];

        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'assigned_to' => \App\Models\User::inRandomOrder()->first()->id,
            'created_by' => \App\Models\User::inRandomOrder()->first()->id,
            'status' => $this->faker->randomElement($statuses),
            'priority' => $this->faker->randomElement($priorities),
            'deadline' => now()->addDays(rand(1, 10)),
        ];
    }
}
