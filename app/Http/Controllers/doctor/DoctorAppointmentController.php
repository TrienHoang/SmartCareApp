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
            abort(403, 'Bạn không có quyền truy cập chức năng này.');
        }

        // Query cơ bản
        $query = Appointment::with(['patient', 'service'])
            ->where('doctor_id', $doctor->id);

        // Lọc theo tên bệnh nhân
        if ($request->filled('patient_name')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->patient_name . '%');
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Danh sách lịch khám (kèm phân trang và giữ filter)
        $appointments = $query->orderByDesc('appointment_time')
            ->paginate(10)
            ->withQueryString();

        // Thống kê lịch hẹn theo trạng thái (dùng 1 truy vấn duy nhất)
        $statusCounts = Appointment::selectRaw('status, COUNT(*) as count')
            ->where('doctor_id', $doctor->id)
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Gán giá trị cho từng biến, nếu không có thì gán 0
        $appointments_pending    = $statusCounts['pending'] ?? 0;
        $appointments_confirmed  = $statusCounts['confirmed'] ?? 0;
        $appointments_completed  = $statusCounts['completed'] ?? 0;
        $appointments_cancelled  = $statusCounts['cancelled'] ?? 0;

        return view('doctor.appointments.index', compact(
            'appointments',
            'appointments_pending',
            'appointments_confirmed',
            'appointments_completed',
            'appointments_cancelled'
        ));
    }
}
