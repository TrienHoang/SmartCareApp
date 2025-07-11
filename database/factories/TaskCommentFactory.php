<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaskComment>
 */
class TaskCommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
public function definition(): array
{
    return [
        'task_id' => \App\Models\Task::inRandomOrder()->first()->id,
        'user_id' => \App\Models\User::inRandomOrder()->first()->id,
        'comment' => $this->faker->sentence,
    ];
}

}
