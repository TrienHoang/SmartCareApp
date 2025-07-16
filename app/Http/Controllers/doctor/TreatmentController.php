<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\TreatmentPlan;
use App\Models\TreatmentHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TreatmentController extends Controller
{
    // Danh sách bệnh nhân đã điều trị
    public function index()
    {
        $doctorId = Auth::user()->doctor->id;
        $plans = TreatmentPlan::with('patient')->where('doctor_id', $doctorId)->get();
        return view('doctor.treatment.index', compact('plans'));
    }

    // Form tạo hoặc cập nhật kế hoạch điều trị
    public function createOrEditPlan($patientId)
    {
        $doctorId = Auth::user()->doctor->id;
        $plan = TreatmentPlan::firstOrNew([
            'patient_id' => $patientId,
            'doctor_id' => $doctorId
        ]);

        $patient = User::findOrFail($patientId);
        return view('doctor.treatment.plan-form', compact('plan', 'patient'));
    }

    public function storePlan(Request $request, $patientId)
    {
        $request->validate([
            'plan_title' => 'nullable|string',
            'total_estimated_cost' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        TreatmentPlan::updateOrCreate(
            [
                'patient_id' => $patientId,
                'doctor_id' => Auth::user()->doctor->id,
            ],
            [
                'plan_title' => $request->plan_title,
                'total_estimated_cost' => $request->total_estimated_cost,
                'notes' => $request->notes,
                'created_at' => now(),
            ]
        );

        return redirect()->route('doctor.treatment.index')->with('success', 'Đã lưu kế hoạch điều trị.');
    }

    // Form ghi lịch sử điều trị
    public function createHistory($patientId)
    {
        $patient = User::findOrFail($patientId);
        return view('doctor.treatment.history-form', compact('patient'));
    }

    public function storeHistory(Request $request, $patientId)
    {
        $request->validate([
            'treatment_description' => 'required|string',
            'treatment_date' => 'required|date',
        ]);

        TreatmentHistory::create([
            'patient_id' => $patientId,
            'doctor_id' => Auth::user()->doctor->id,
            'treatment_description' => $request->treatment_description,
            'treatment_date' => $request->treatment_date,
            'created_at' => now(),
        ]);

        return redirect()->route('doctor.treatment.index')->with('success', 'Đã ghi lịch sử điều trị.');
    }
}
