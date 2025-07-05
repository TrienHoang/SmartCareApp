<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TreatmentPlan;
use App\Models\TreatmentPlanItem;

class TreatmentPlanItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo 5 kế hoạch điều trị, mỗi kế hoạch có 3 mục điều trị
        TreatmentPlan::factory()
            ->count(5)
            ->create()
            ->each(function ($plan) {
                TreatmentPlanItem::factory()
                    ->count(3)
                    ->create([
                        'treatment_plan_id' => $plan->id
                    ]);
            });
    }
}
