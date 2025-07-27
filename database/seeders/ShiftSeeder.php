<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Shift;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Shift::insert([
            ['name' => 'Sáng', 'start_time' => '07:00:00', 'end_time' => '12:00:00'],
            ['name' => 'Chiều', 'start_time' => '13:00:00', 'end_time' => '17:00:00'],
            ['name' => 'Full', 'start_time' => '07:00:00', 'end_time' => '17:00:00'],
        ]);
    }
}
