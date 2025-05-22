<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkingSchedule;

class WorkingScheduleSeeder extends Seeder
{
    public function run(): void
    {
        WorkingSchedule::factory()->count(30)->create();
    }
}

