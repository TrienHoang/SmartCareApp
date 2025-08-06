<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    /**
     * Hiển thị danh sách lịch hẹn của bác sĩ hiện tại.
     */
    public function index(Request $request)
    {
        $doctorId = Auth::user()->doctor->id;

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

        // Thống kê số lượng theo trạng thái
        $counts = Appointment::where('doctor_id', $doctorId)
            ->whereIn('status', ['pending', 'confirmed', 'check_in', 'completed'])
            ->selectRaw("
                SUM(status = 'pending') as pending,
                SUM(status = 'confirmed') as confirmed,
                SUM(status = 'check_in') as check_in,
                SUM(status = 'completed') as completed
            ")->first();

        return view('doctor.appointments.index', [
            'appointments' => $appointments,
            'appointments_pending' => $counts->pending ?? 0,
            'appointments_confirmed' => $counts->confirmed ?? 0,
            'appointments_check_in' => $counts->check_in ?? 0,
            'appointments_completed' => $counts->completed ?? 0,
        ]);
    }

    /**
     * Hiển thị chi tiết một lịch hẹn.
     */
    public function show($id)
    {
        $doctorId = Auth::user()->doctor->id;

        $appointment = Appointment::with(['patient', 'doctor', 'service'])
            ->where('id', $id)
            ->where('doctor_id', $doctorId)
            ->firstOrFail();

        return view('doctor.appointments.show', compact('appointment'));
    }

    /**
     * Cập nhật trạng thái lịch hẹn.
     */
    public function updateStatus(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        // Chỉ cho phép cập nhật nếu hiện tại là completed hoặc cancelled
        if (in_array($appointment->status, ['completed', 'cancelled'])) {
            return redirect()->back()->with('error', 'Lịch hẹn đã hoàn thành hoặc bị hủy, không thể cập nhật trạng thái.');
        }

        $request->validate([
            'status' => 'required|in:pending,confirmed,check_in,completed,cancelled',
        ]);

        $appointment->status = $request->status;
        $appointment->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công.');
    }
}
