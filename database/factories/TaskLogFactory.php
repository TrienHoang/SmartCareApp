<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaskLog>
 */
class TaskLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['moi_tao', 'dang_lam', 'hoan_thanh', 'tre_han'];
        $from = $this->faker->randomElement($statuses);
        $to = $this->faker->randomElement(array_diff($statuses, [$from]));

        return [
            'task_id' => \App\Models\Task::inRandomOrder()->first()->id,
            'changed_by' => \App\Models\User::inRandomOrder()->first()->id,
            'from_status' => $from,
            'to_status' => $to,
            'changed_at' => now(),
        ];
    }
}
