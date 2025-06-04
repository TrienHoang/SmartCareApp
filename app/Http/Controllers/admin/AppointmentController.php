<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(){
        $appointments = Appointment::paginate(10);
        return view('admin.Appointment.index', compact('appointments'));
    }
    public function create(){
        return view('admin.Appointment.create', [
            'patients' => User::where('role_id', 3)->get(),
            'doctors' => Doctor::with('user')->get(),
            'services' => Service::all(),
        ]);
    }
    public function store(Request $request){
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:doctors,id',
            'service_id' => 'required|exists:services,id',
            'appointment_time' => 'required|date',
            'status' => 'required|in:pending,confirmed,cancelled',
            'reason' => 'nullable|string|max:255',
        ]);

        Appointment::create($request->all());

        return redirect()->route('admin.appointments.index')->with('success', 'Tạo lịch hẹn thành công');
    }
    public function edit($id){
        $appointment = Appointment::findOrFail($id);
        return view('admin.Appointment.edit', [
            'appointment' => $appointment,
            'patients' => User::where('role_id', 3)->get(),
            'doctors' => Doctor::with('user')->get(),
            'services' => Service::all(),
        ]);
    }
}
