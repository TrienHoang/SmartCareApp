<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DoctorLeave;

class DoctorLeaveSeeder extends Seeder
{
    public function run(): void
    {
        DoctorLeave::factory()->count(10)->create();
    }
}

