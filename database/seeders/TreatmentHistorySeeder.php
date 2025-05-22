<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TreatmentHistory;

class TreatmentHistorySeeder extends Seeder
{
    public function run(): void
    {
        TreatmentHistory::factory()->count(15)->create();
    }
}


