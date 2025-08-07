<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
            ->whereIn('status', ['pending', 'confirmed', 'checked_in', 'completed'])
            ->selectRaw("
                SUM(status = 'pending') as pending,
                SUM(status = 'confirmed') as confirmed,
                SUM(status = 'checked_in') as checked_in,
                SUM(status = 'completed') as completed
            ")->first();

        return view('doctor.appointments.index', [
            'appointments' => $appointments,
            'appointments_pending' => $counts->pending ?? 0,
            'appointments_confirmed' => $counts->confirmed ?? 0,
            'appointments_checked_in' => $counts->checked_in ?? 0,
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
        $appointment = Appointment::with('doctor')->findOrFail($id);

        $validTransitions = [];

        if ($appointment->status === 'checked_in') {
            $validTransitions = ['completed', 'cancelled'];
        } elseif ($appointment->status === 'confirmed') {
            $validTransitions = ['cancelled'];
        } else {
            return redirect()->back()->with('error', 'Không thể cập nhật trạng thái ở trạng thái hiện tại.');
        }

        $request->validate([
            'status' => ['required', Rule::in($validTransitions)],
        ], [
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ]);

        $newStatus = $request->status;
        $now = Carbon::now();
        $appointmentTime = Carbon::parse($appointment->appointment_time);

        if (
            in_array($appointment->status, ['confirmed', 'checked_in']) &&
            $newStatus === 'cancelled'
        ) {
            if (!$now->isSameDay($appointmentTime)) {
                return redirect()->back()->with('error', 'Chỉ được phép hủy lịch hẹn vào đúng ngày hẹn.');
            }
        }

        // 3. Không cho hoàn thành (completed) nếu chưa đến giờ khám
        if ($newStatus === 'completed' && $now->lt($appointmentTime)) {
            return redirect()->back()->with('error', 'Không thể hoàn thành lịch hẹn trước thời gian khám.');
        }

        // 4. Nếu chưa checked_in và đã quá giờ -> không cho completed
        if ($newStatus === 'completed' && $appointment->status !== 'checked_in' && $now->gt($appointmentTime)) {
            return redirect()->back()->with('error', 'Không thể hoàn thành lịch hẹn vì bệnh nhân chưa đến và đã quá giờ hẹn.');
        }

        $appointment->status = $newStatus;
        $appointment->save();

        if ($newStatus === 'completed') {
            $existing = MedicalRecord::where('appointment_id', $appointment->id)->exists();

            if (!$existing) {
                MedicalRecord::create([
                    'appointment_id' => $appointment->id,
                    'code' => 'MR' . now()->format('YmdHis') . $appointment->id,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công.');
    }
}
