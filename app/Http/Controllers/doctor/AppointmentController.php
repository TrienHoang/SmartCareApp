<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Hiển thị danh sách lịch hẹn của bác sĩ hiện tại.
     */
    public function index(Request $request)
    {
        $doctorId = auth()->id();

        $query = Appointment::with(['patient', 'service'])
            ->where('doctor_id', $doctorId);

        // Lọc theo trạng thái (nếu có), nếu không thì lấy tất cả trừ 'cancelled'
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', '!=', 'cancelled');
        }

        // Lọc theo tên bệnh nhân (nếu có)
        if ($request->filled('patient_name')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->patient_name . '%');
            });
        }

        // Phân trang kết quả
        $appointments = $query->orderByDesc('created_at')->paginate(10);

        // Thống kê số lượng theo trạng thái (loại trừ cancelled)
        $statuses = ['pending', 'confirmed', 'completed'];
        $counts = Appointment::where('doctor_id', $doctorId)
            ->whereIn('status', $statuses)
            ->selectRaw("
                SUM(status = 'pending') as pending,
                SUM(status = 'confirmed') as confirmed,
                SUM(status = 'completed') as completed
            ")->first();

        return view('doctor.appointments.index', [
            'appointments' => $appointments,
            'appointments_pending' => $counts->pending ?? 0,
            'appointments_confirmed' => $counts->confirmed ?? 0,
            'appointments_completed' => $counts->completed ?? 0,
        ]);
    }

    /**
     * Hiển thị chi tiết một lịch hẹn.
     */
    public function show(Appointment $appointment)
    {
        // Chỉ bác sĩ chủ lịch hẹn mới được xem
        if ($appointment->doctor_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền xem lịch hẹn này.');
        }

        return view('doctor.appointments.show', compact('appointment'));
    }
}
