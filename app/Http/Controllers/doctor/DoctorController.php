<?php

namespace App\Http\Controllers\doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Department;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;



class DoctorController extends Controller
{

    public function history(Request $request)
    {
        $user = Auth::user();
        $doctor = $user->doctor;

        if (!$doctor) {
            return redirect()->route('doctor.dashboard')
                ->with('error', 'Tài khoản của bạn chưa được liên kết với bác sĩ nào trong hệ thống.');
        }

        $query = Appointment::with(['patient', 'service', 'medicalRecord'])
            ->where('doctor_id', $doctor->id)
            ->where('status', 'completed')
            ->orderByDesc('appointment_time');

        // 🔍 Tìm theo tên bệnh nhân
        if ($request->filled('patient_name')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->patient_name . '%');
            });
        }

        // 🔍 Tìm theo email bệnh nhân
        if ($request->filled('email')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('email', 'like', '%' . $request->email . '%');
            });
        }

        // 🔍 Tìm theo số điện thoại bệnh nhân
        if ($request->filled('phone')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('phone', 'like', '%' . $request->phone . '%');
            });
        }

        // 🔍 Tìm theo tên dịch vụ
        if ($request->filled('service_name')) {
            $query->whereHas('service', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->service_name . '%');
            });
        }

        // 🗓 Kiểm tra ngày hợp lệ
        if ($request->filled('from_date') && $request->filled('to_date')) {
            if ($request->from_date > $request->to_date) {
                return redirect()->route('doctor.history.index')
                    ->withInput()
                    ->with('error', 'Ngày bắt đầu không được lớn hơn ngày kết thúc.');
            }
        }

        // 🗓 Lọc từ ngày
        if ($request->filled('from_date')) {
            $query->whereDate('appointment_time', '>=', $request->from_date);
        }

        // 🗓 Lọc đến ngày
        if ($request->filled('to_date')) {
            $query->whereDate('appointment_time', '<=', $request->to_date);
        }

        $appointments = $query->paginate(10)->withQueryString();

        return view('doctor.history.index', compact('appointments'));
    }



    public function historyShow($id)
    {
        $appointment = Appointment::with([
            'patient',
            'service',
            'medicalRecord.prescription.items'
        ])
            ->where('status', 'completed')
            ->findOrFail($id);

        return view('doctor.history.show', compact('appointment'));
    }
}
