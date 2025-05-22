<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AppointmentLog;

class AppointmentLogSeeder extends Seeder
{
    public function run(): void
    {
        AppointmentLog::factory()->count(10)->create();
    }
}

