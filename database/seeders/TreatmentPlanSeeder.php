<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TreatmentPlan;

class TreatmentPlanSeeder extends Seeder
{
    public function run(): void
    {
        TreatmentPlan::factory()->count(10)->create();
    }
}

