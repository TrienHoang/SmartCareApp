<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use Illuminate\Support\Facades\Auth;

class MedicalRecordController extends Controller
{
    // Hiển thị form ghi nhận
    public function createOrEdit($id)
    {
        $appointment = Appointment::with('medicalRecord')->findOrFail($id);

        if ($appointment->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }

        return view('doctor.medical_records.form', compact('appointment'));
    }

    // Lưu hồ sơ điều trị
    public function storeOrUpdate(Request $request, $id)
    {
        $request->validate([
            'symptoms' => 'required|string',
            'diagnosis' => 'required|string',
            'treatment' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $appointment = Appointment::findOrFail($id);

        if ($appointment->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }

        MedicalRecord::updateOrCreate(
            ['appointment_id' => $appointment->id],
            [
                'symptoms' => $request->symptoms,
                'diagnosis' => $request->diagnosis,
                'treatment' => $request->treatment,
                'notes' => $request->notes,
                'created_at' => now(),
            ]
        );

        return redirect()->back()->with('success', 'Đã lưu hồ sơ điều trị.');
    }

    // Danh sách bệnh nhân đã từng khám
    public function patientsList()
    {
        $doctorId = Auth::user()->doctor->id;

        $appointments = Appointment::with(['patient', 'medicalRecord'])
            ->where('doctor_id', $doctorId)
            ->orderByDesc('date')
            ->get()
            ->groupBy('patient_id');

        return view('doctor.medical_records.patients', compact('appointments'));
    }
}

