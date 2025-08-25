<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Prescription;
use Illuminate\Support\Facades\Auth;

class PrescriptionClientController extends Controller
{
    public function index(Request $request)
    {
           $user = Auth::user();
        $search = $request->input('search');

        $appointments = Appointment::with(['medicalRecord', 'doctor.user'])
            ->where('patient_id', Auth::id())
            ->where('status', 'completed')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('medicalRecord', function ($qr) use ($search) {
                        $qr->where('symptoms', 'like', '%' . $search . '%');
                    })->orWhereHas('doctor.user', function ($qr) use ($search) {
                        $qr->where('full_name', 'like', '%' . $search . '%');
                    });
                });
            })
            ->orderBy('appointment_time', 'desc')
            ->get();

        return view('client.prescriptions.index', compact('appointments', 'search','user'));
    }



    public function show($id)
    {
          $user = Auth::user();
        $prescription = Prescription::with(['prescriptionItems.medicine', 'doctor.user:id,full_name'])
            ->findOrFail($id);

        // Check quyền xem
        if (Auth::id() !== $prescription->medicalRecord->appointment->patient_id) {
            abort(403, 'Không có quyền truy cập đơn thuốc này.');
        }

        return view('client.prescriptions.show', compact('prescription','user'));
    }
}
