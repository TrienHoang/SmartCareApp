<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TreatmentPlan;
use App\Models\TreatmentPlanItem;
use App\Models\User;
use App\Models\Role;

class TreatmentPlanSeeder extends Seeder
{
    public function run(): void
    {
        $doctorRoleId = Role::where('name', 'doctor')->first()->id;
        $patientRoleId = Role::where('name', 'patient')->first()->id;

        $doctors = User::where('role_id', $doctorRoleId)->get();
        $patients = User::where('role_id', $patientRoleId)->get();

        for ($i = 0; $i < 5; $i++) {
            $plan = TreatmentPlan::factory()->create([
                'doctor_id' => $doctors->random()->id,
                'patient_id' => $patients->random()->id,
            ]);

            TreatmentPlanItem::factory()->count(rand(2, 4))->create([
                'treatment_plan_id' => $plan->id,
            ]);
        }
    }
}
