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
    public function index()
    {
        $appointments = Appointment::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.Appointment.index', compact('appointments'));
    }

    public function create()
    {
        return view('admin.Appointment.create', [
            'patients' => User::where('role_id', 3)->get(),
            'doctors' => Doctor::with('user')->get(),
            'services' => Service::all(),
        ]);
    }

    public function store(Request $request)
    {
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

    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);
        return view('admin.Appointment.edit', [
            'appointment' => $appointment,
            'patients' => User::where('role_id', 3)->get(),
            'doctors' => Doctor::with('user')->get(),
            'services' => Service::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'appointment_time' => 'required|date|after:now',
            'status' => 'required|in:pending,confirmed,completed,cancelled'
        ]);

        $appointment = Appointment::findOrFail($id);

        if ($appointment->status === 'completed' && $request->status !== 'completed') {
            return redirect()->back()->withErrors(['status' => 'Không thể thay đổi trạng thái khi lịch hẹn đã hoàn thành.']);
        }

        $appointment->update($request->all());

        return redirect()->route('admin.appointments.index')->with('success', 'Cập nhật lịch hẹn thành công');
    }

    public function cancel($id)
    {
        $appointment = Appointment::findOrFail($id);

        if (in_array($appointment->status, ['pending', 'confirmed'])) {
            $appointment->status = 'cancelled';
            $appointment->save();
            return redirect()->route('admin.appointments.index')->with('success', 'Hủy lịch hẹn thành công');
        }

        return redirect()->route('admin.appointments.index')->withErrors(['status' => 'Không thể hủy lịch hẹn đã hoàn thành hoặc đã hủy.']);
    }
    public function show($id)
    {
        $appointment = Appointment::findOrFail($id);
        return view('admin.Appointment.show', compact('appointment'));
    }
}
