<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;

class DoctorAppointmentController extends Controller
{
    public function index(Request $request)
    {
        $doctor = Auth::user()->doctor;

        if (!$doctor) {
            abort(403, 'Không phải tài khoản bác sĩ.');
        }

        $appointments = Appointment::with(['patient', 'service'])
            ->where('doctor_id', $doctor->id)
            ->orderBy('appointment_time', 'desc')
            ->paginate(10);

        return view('doctor.appointments.index', compact('appointments'));
    }
}
