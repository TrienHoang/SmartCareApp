<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\TaskLog;
use App\Models\User;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        // Nếu chưa có user nào thì tạo
        if (User::count() === 0) {
            User::factory()->count(5)->create();
        }

        $users = User::all();

        // Tạo 10 task
        Task::factory(10)->create()->each(function (Task $task) use ($users) {
            // Gán người thực hiện nếu model có field `assigned_to`
            $task->assigned_to = $users->random()->id;
            $task->save();

            // Bình luận
            TaskComment::factory(rand(1, 3))->create([
                'task_id' => $task->id,
                'user_id' => $users->random()->id,
            ]);

            // Log thay đổi
            TaskLog::factory(rand(1, 2))->create([
                'task_id' => $task->id,
                'changed_by' => $users->random()->id,
            ]);
        });
    }
}
