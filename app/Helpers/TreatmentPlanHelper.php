<?php

namespace App\Helpers;

use App\Models\Appointment;
use App\Models\TreatmentPlan;

class TreatmentPlanHelper
{
    public static function updatePlanStatus($planId)
    {
        $plan = TreatmentPlan::find($planId);
        if (!$plan) return;

        $totalAppointments = Appointment::where('treatment_plan_id', $planId)->count();

        $completedAppointments = Appointment::where('treatment_plan_id', $planId)
            ->where('status', 'completed')
            ->count();

        $confirmedOrCompletedAppointments = Appointment::where('treatment_plan_id', $planId)
            ->whereIn('status', ['confirmed', 'completed'])
            ->count();

        if ($totalAppointments > 0 && $completedAppointments === $totalAppointments) {
            $plan->update(['status' => 'hoan_thanh']);
        } elseif ($confirmedOrCompletedAppointments > 0) {
            $plan->update(['status' => 'dang_tien_hanh']);
        } else {
            $plan->update(['status' => 'chua_tien_hanh']);
        }
    }
}
