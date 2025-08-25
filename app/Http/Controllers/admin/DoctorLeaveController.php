<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorLeave;
use App\Models\Room;
use App\Notifications\DoctorLeaveApproved;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DoctorLeaveController extends Controller
{


    public function index(Request $request)
    {
        $query = DoctorLeave::with(['doctor.user'])->orderBy('id', 'desc');

        // Validate ngày bắt đầu <= ngày kết thúc
        if ($request->filled('start_date') && $request->filled('end_date')) {
            if ($request->start_date > $request->end_date) {
                return back()->withInput()->withErrors([
                    'start_date' => 'Ngày bắt đầu không được lớn hơn ngày kết thúc.',
                    'end_date' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.'
                ]);
            }

            // Tính số ngày nghỉ
            $start = Carbon::parse($request->start_date);
            $end = Carbon::parse($request->end_date);
            $days = $start->diffInDays($end) + 1;

            if ($days > 3) {
                return back()->withInput()->withErrors([
                    'end_date' => 'Bác sĩ chỉ được nghỉ tối đa 3 ngày. Khoảng bạn chọn là ' . $days . ' ngày.'
                ]);
            }
        }

        // Lọc theo tên bác sĩ
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->whereHas('doctor.user', function ($q) use ($keyword) {
                $q->where('full_name', 'like', '%' . $keyword . '%');
            });
        }

        // Lọc theo ngày bắt đầu nghỉ
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }

        // Lọc theo ngày kết thúc nghỉ
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }

        // Lọc theo trạng thái duyệt
        if ($request->filled('approved') && in_array($request->approved, ['0', '1'])) {
            $query->where('approved', $request->approved);
        }

        $doctorLeaves = $query->paginate(10)->withQueryString();

        return view('admin.doctor_leaves.index', compact('doctorLeaves'));
    }
    public function edit($id)
    {
        $leave = DoctorLeave::with(['doctor.user', 'replacementDoctor.user'])->findOrFail($id);
    
        if ((int) $leave->approved === 1) {
            return redirect()->route('admin.doctor_leaves.index')
                ->with('error', 'Lịch nghỉ đã được duyệt và không thể chỉnh sửa.');
        }
    
        $start = Carbon::parse($leave->start_date)->startOfDay();
        $end   = Carbon::parse($leave->end_date)->endOfDay();
    
        // 1. Lấy tất cả lịch hẹn của bác sĩ nghỉ trong khoảng thời gian nghỉ
        $leaveAppointments = Appointment::where('doctor_id', $leave->doctor_id)
            ->whereBetween('appointment_time', [$start, $end])
            ->get();
    
        // 2. Lấy danh sách bác sĩ cùng khoa (trừ người nghỉ)
        $replacementDoctors = Doctor::with(['user', 'workingSchedules.shift'])
            ->where('department_id', $leave->doctor->department_id)
            ->where('id', '!=', $leave->doctor_id)
            ->get();
    
        // 3. Lọc bác sĩ có thể cover
        $availableDoctors = $replacementDoctors->filter(function ($doctor) use ($leaveAppointments) {
            foreach ($leaveAppointments as $appointment) {
                $day  = $appointment->appointment_time->toDateString();
                $time = $appointment->appointment_time->format('H:i:s');
    
                // Tìm ca làm của bác sĩ cover trong ngày đó
                $schedule = $doctor->workingSchedules()
                    ->whereDate('day', $day)
                    ->whereNotNull('shift_id')
                    ->with('shift')
                    ->first();
    
                if ($schedule && $schedule->shift) {
                    $shift = $schedule->shift;
    
                    // Check nếu giờ hẹn trùng khung giờ ca trực
                    if ($time >= $shift->start_time && $time <= $shift->end_time) {
                        // Kiểm tra xem bác sĩ cover có appointment khác trong khoảng đó không
                        $conflict = $doctor->appointments()
                            ->whereBetween('appointment_time', [
                                $schedule->day . ' ' . $shift->start_time,
                                $schedule->day . ' ' . $shift->end_time,
                            ])
                            ->exists();
    
                        if ($conflict) {
                            return false; // bác sĩ cover bận, không nhận ca này
                        }
                    }
                } else {
                    return false; // không có lịch trực trong ngày này → không thể cover
                }
            }
            return true; // nếu qua hết vòng lặp mà không conflict → có thể cover
        });
    
        return view('admin.doctor_leaves.edit', [
            'leave' => $leave,
            'replacementDoctors' => $availableDoctors
        ]);
    }
public function update(Request $request, $id)
{
    $request->validate([
        'approved' => 'required|in:0,1',
        'replacement_doctor_id' => 'nullable|exists:doctors,id',
    ]);

    $leave = DoctorLeave::with('doctor')->findOrFail($id);

    // Nếu đã duyệt thì không được chỉnh sửa nữa
    if ($leave->approved == 1) {
        return redirect()->route('admin.doctor_leaves.index')
            ->with('error', 'Lịch nghỉ đã được duyệt và không thể chỉnh sửa.');
    }

    $oldApproved = $leave->approved;

    // Cập nhật trạng thái & bác sĩ thay thế
    $leave->approved = $request->approved;
    $leave->replacement_doctor_id = $request->replacement_doctor_id;
    $leave->save();

    // Nếu đơn mới được duyệt
    if ($oldApproved == 0 && $leave->approved == 1) {
        DB::transaction(function () use ($leave) {
            if ($leave->replacement_doctor_id) {
                // Có bác sĩ thay thế → chuyển lịch hẹn
                $replacement = Doctor::find($leave->replacement_doctor_id);

                Appointment::where('doctor_id', $leave->doctor_id)
                    ->whereBetween('appointment_time', [
                        Carbon::parse($leave->start_date)->startOfDay(),
                        Carbon::parse($leave->end_date)->endOfDay(),
                    ])
                    ->where('status', '!=', 'cancelled')
                    ->update([
                        'doctor_id' => $replacement->id
                    ]);
            } else {
                // ❌ Không có bác sĩ thay thế → hủy lịch & refund vào ví
                $appointments = Appointment::with('payment')
                    ->where('doctor_id', $leave->doctor_id)
                    ->whereBetween('appointment_time', [
                        Carbon::parse($leave->start_date)->startOfDay(),
                        Carbon::parse($leave->end_date)->endOfDay(),
                    ])
                    ->where('status', '!=', 'cancelled')
                    ->get();

                foreach ($appointments as $appointment) {
                    // update status = cancelled
                    $appointment->status = 'cancelled';
                    $appointment->cancel_reason = 'Bác sĩ có lịch nghỉ đột xuất. Xin lỗi vì sự bất tiện này.';
                    $appointment->save();

                    // refund nếu có thanh toán
                    if ($appointment->payment && $appointment->payment->status === 'paid') {
                        // 🔥 tái sử dụng hàm refund từ AppointmentController
                        $appointmentController = app(AppointmentController::class);
                        $result = $appointmentController->refund($appointment->id);

                        // log kết quả refund
                        $appointmentController->logRefundResult($appointment->payment, [
                            'success' => session('success') ? true : false,
                            'message' => session('error') ?? ''
                        ], 'Hủy tự động do bác sĩ nghỉ đột xuất');
                    }
                }
            }
        });
    }

    return redirect()->route('admin.doctor_leaves.index')
        ->with('success', 'Cập nhật trạng thái duyệt lịch nghỉ thành công.');
}


}
