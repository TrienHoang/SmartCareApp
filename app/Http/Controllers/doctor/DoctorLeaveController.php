<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorLeave;
use App\Models\User;
use App\Models\WorkingSchedule;
use App\Notifications\DoctorLeaveCreated;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DoctorLeaveController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user || !$user->doctor) {
            return redirect()->route('home')->withErrors(['error' => 'Bạn không phải bác sĩ hoặc không có quyền truy cập.']);
        }

        $doctorId = $user->doctor->id;
        $leaves = DoctorLeave::where('doctor_id', $doctorId)
            ->orderByDesc('start_date')
            ->get();

        return view('doctor.doctor_leaves.index', compact('leaves'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user || !$user->doctor) {
            return redirect()->route('home')->withErrors(['error' => 'Bạn không phải bác sĩ hoặc không có quyền truy cập.']);
        }

        $currentDoctor = $user->doctor;
        $sameDepartmentDoctors = Doctor::where('department_id', $currentDoctor->department_id)
            ->where('id', '!=', $currentDoctor->id)
            ->get();

        return view('doctor.doctor_leaves.create', [
            'doctors' => $sameDepartmentDoctors
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->doctor) {
            return redirect()->back()->withErrors(['error' => 'Bạn không có quyền tạo đơn nghỉ hoặc không phải bác sĩ.'])->withInput();
        }

        $doctor = $user->doctor;
        $doctorId = $doctor->id;
        $today = Carbon::today();
        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $days = $start->diffInDays($end) + 1;
        $leaveType = $request->input('leave_type'); // 'emergency', 'vacation', 'normal'

        // Validate inputs
        $rules = [
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
            'reason'     => ['required', 'string', 'max:1000'],
            'leave_type' => ['required', Rule::in(['emergency', 'vacation', 'normal'])],
        ];

        // Add validation for replacement doctor if provided for emergency leave
        if ($leaveType === 'emergency' && $request->filled('replacement_doctor_id')) {
            $rules['replacement_doctor_id'] = [
                'exists:doctors,id',
                function ($attribute, $value, $fail) use ($doctor, $start, $end) {
                    $replacementDoctor = Doctor::find($value);
                    if (!$replacementDoctor) {
                        $fail('Bác sĩ thay thế không tồn tại.');
                    } elseif ($replacementDoctor->department_id !== $doctor->department_id) {
                        $fail('Bác sĩ thay thế phải cùng khoa.');
                    } elseif (DoctorLeave::where('doctor_id', $value)
                        ->where('approved', true)
                        ->whereDate('start_date', '<=', $end)
                        ->whereDate('end_date', '>=', $start)
                        ->exists()
                    ) {
                        $fail('Bác sĩ thay thế đã có lịch nghỉ trong khoảng thời gian này.');
                    }
                },
            ];
        }

        // Additional validation based on leave type
        if ($leaveType === 'normal') {
            $minDate = $today->copy()->addDays(2)->format('Y-m-d');
            $rules['start_date'][] = 'after_or_equal:' . $minDate;
        }

        if ($leaveType === 'vacation') {
            $minVacation = $today->copy()->addDays(15)->format('Y-m-d');
            $rules['start_date'][] = 'after_or_equal:' . $minVacation;
        }

        $request->validate($rules, [
            'start_date.required'       => 'Vui lòng chọn ngày bắt đầu.',
            'start_date.after_or_equal' => 'Ngày bắt đầu không hợp lệ theo loại nghỉ.',
            'end_date.required'         => 'Vui lòng chọn ngày kết thúc.',
            'end_date.after_or_equal'   => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
            'reason.required'           => 'Vui lòng nhập lý do nghỉ.',
            'leave_type.required'       => 'Vui lòng chọn loại nghỉ.',
            'replacement_doctor_id.exists' => 'Bác sĩ thay thế không hợp lệ.',
        ]);

        // Check maximum 3 days
        if ($days > 3) {
            return back()->withErrors(['end_date' => 'Không được nghỉ quá 3 ngày.'])->withInput();
        }

        // Check one leave per month (including emergency)
        $hasAnyLeave = DoctorLeave::where('doctor_id', $doctorId)
            ->whereMonth('start_date', $start->month)
            ->whereYear('start_date', $start->year)
            ->exists();

        if ($hasAnyLeave) {
            return back()->withErrors(['start_date' => 'Bạn chỉ được nghỉ 1 lần mỗi tháng (bao gồm cả nghỉ đột xuất).'])->withInput();
        }

        // Check one emergency leave per month
        if ($leaveType === 'emergency') {
            $hasEmergencyLeave = DoctorLeave::where('doctor_id', $doctorId)
                ->whereMonth('start_date', $start->month)
                ->whereYear('start_date', $start->year)
                ->where('urgent', true)
                ->exists();

            if ($hasEmergencyLeave) {
                return back()->withErrors(['start_date' => 'Bạn chỉ được nghỉ đột xuất 1 lần mỗi tháng.'])->withInput();
            }
        }

        // Check work schedule conflict
        $conflictWork = WorkingSchedule::where('doctor_id', $doctorId)
            ->whereBetween('day', [$start, $end])
            ->exists();

        if ($conflictWork && $leaveType !== 'emergency') {
            return back()->withErrors(['start_date' => 'Bạn đã có lịch làm trong khoảng thời gian này!'])->withInput();
        }

        // Create leave
        $leave = DoctorLeave::create([
            'doctor_id'            => $doctorId,
            'start_date'           => $start,
            'end_date'             => $end,
            'reason'               => $request->reason,
            'urgent'               => $leaveType === 'emergency' ? 1 : 0,
            'approved'             => 0,
            'replacement_doctor_id' => $leaveType === 'emergency' ? $request->replacement_doctor_id : null,
        ]);

        // Notify admins
        $admins = User::where('role_id', 1)->get();
        foreach ($admins as $admin) {
            $admin->notify(new DoctorLeaveCreated($leave, false));
        }

        // Handle emergency leave
        if ($leaveType === 'emergency') {
            $period = Carbon::parse($request->start_date)->daysUntil(Carbon::parse($request->end_date)->copy()->addDay());
            foreach ($period as $date) {
                $this->handleUrgentReplacementOrCancel($doctor, $date, $request->replacement_doctor_id);
            }
        }

        return redirect()->route('doctor.leaves.index')->with('success', 'Đơn nghỉ đã gửi đến quản trị viên.');
    }

    public function show($id)
    {
        $user = Auth::user();
        if (!$user || !$user->doctor) {
            return redirect()->route('home')->withErrors(['error' => 'Bạn không phải bác sĩ hoặc không có quyền truy cập.']);
        }

        $leave = DoctorLeave::where('id', $id)
            ->where('doctor_id', $user->doctor->id)
            ->firstOrFail();

        return view('doctor.doctor_leaves.show', compact('leave'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        if (!$user || !$user->doctor) {
            return redirect()->route('home')->withErrors(['error' => 'Bạn không phải bác sĩ hoặc không có quyền truy cập.']);
        }

        $leave = DoctorLeave::where('id', $id)
            ->where('doctor_id', $user->doctor->id)
            ->firstOrFail();

        if ($leave->approved) {
            return redirect()->route('doctor.leaves.index')
                ->with('error', 'Lịch nghỉ đã được duyệt. Không thể chỉnh sửa.');
        }

        return view('doctor.doctor_leaves.edit', compact('leave'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || !$user->doctor) {
            return redirect()->route('home')->withErrors(['error' => 'Bạn không phải bác sĩ hoặc không có quyền truy cập.']);
        }

        $leave = DoctorLeave::where('id', $id)
            ->where('doctor_id', $user->doctor->id)
            ->firstOrFail();

        if ($leave->approved) {
            return redirect()->route('doctor.leaves.index')->with('error', 'Không thể cập nhật khi đã được duyệt.');
        }

        $doctor = $user->doctor;
        $doctorId = $doctor->id;
        $isEmergency = $request->has('is_emergency');
        $isVacation = $request->has('is_vacation');
        $today = Carbon::today();
        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $days = $start->diffInDays($end) + 1;

        // Validate inputs
        $rules = [
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string', 'max:1000'],
        ];

        // Add validation for replacement doctor if emergency leave
        if ($isEmergency && $request->filled('replacement_doctor_id')) {
            $rules['replacement_doctor_id'] = [
                'exists:doctors,id',
                function ($attribute, $value, $fail) use ($doctor, $start, $end) {
                    $replacementDoctor = Doctor::find($value);
                    if (!$replacementDoctor) {
                        $fail('Bác sĩ thay thế không tồn tại.');
                    } elseif ($replacementDoctor->department_id !== $doctor->department_id) {
                        $fail('Bác sĩ thay thế phải cùng khoa.');
                    } elseif (DoctorLeave::where('doctor_id', $value)
                        ->where('approved', true)
                        ->whereDate('start_date', '<=', $end)
                        ->whereDate('end_date', '>=', $start)
                        ->exists()
                    ) {
                        $fail('Bác sĩ thay thế đã có lịch nghỉ trong khoảng thời gian này.');
                    }
                },
            ];
        }

        if (!$isEmergency && !$isVacation) {
            $minDate = $today->copy()->addDays(2)->format('Y-m-d');
            $rules['start_date'][] = 'after_or_equal:' . $minDate;
        }

        $request->validate($rules, [
            'start_date.required' => 'Vui lòng chọn ngày bắt đầu.',
            'start_date.after_or_equal' => 'Ngày bắt đầu phải sau ít nhất 2 ngày kể từ hôm nay (nếu không phải đột xuất hoặc du lịch).',
            'end_date.required' => 'Vui lòng chọn ngày kết thúc.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
            'reason.required' => 'Vui lòng nhập lý do nghỉ.',
            'replacement_doctor_id.exists' => 'Bác sĩ thay thế không hợp lệ.',
        ]);

        // Check vacation leave rules
        if ($isVacation) {
            $minVacationDate = $today->copy()->addDays(15);
            if ($start->lt($minVacationDate)) {
                return redirect()->back()->withErrors([
                    'start_date' => 'Ngày nghỉ du lịch phải được đăng ký trước ít nhất 15 ngày.'
                ])->withInput();
            }
        }

        // Check maximum 3 days
        if ($days > 3) {
            return redirect()->back()->withErrors([
                'end_date' => 'Không được nghỉ quá 3 ngày.'
            ])->withInput();
        }

        // Check one leave per month (including emergency)
        $hasAnyLeave = DoctorLeave::where('doctor_id', $doctorId)
            ->whereMonth('start_date', $start->month)
            ->whereYear('start_date', $start->year)
            ->where('id', '!=', $id)
            ->exists();

        if ($hasAnyLeave) {
            return redirect()->back()->withErrors([
                'start_date' => 'Bạn chỉ được nghỉ 1 lần mỗi tháng (bao gồm cả nghỉ đột xuất).'
            ])->withInput();
        }

        // Check one emergency leave per month
        if ($isEmergency) {
            $hasEmergencyLeave = DoctorLeave::where('doctor_id', $doctorId)
                ->whereMonth('start_date', $start->month)
                ->whereYear('start_date', $start->year)
                ->where('urgent', true)
                ->where('id', '!=', $id)
                ->exists();

            if ($hasEmergencyLeave) {
                return redirect()->back()->withErrors([
                    'start_date' => 'Bạn chỉ được nghỉ đột xuất 1 lần mỗi tháng.'
                ])->withInput();
            }
        }

        // Check work schedule conflict
        $conflictWork = WorkingSchedule::where('doctor_id', $doctorId)
            ->whereDate('day', '>=', $start)
            ->whereDate('day', '<=', $end)
            ->exists();

        if ($conflictWork && !$isEmergency) {
            return redirect()->back()->withErrors([
                'start_date' => 'Bạn đã có lịch làm trong khoảng thời gian này!'
            ])->withInput();
        }

        // Handle emergency leave reassignment or cancellation before updating
        if ($isEmergency && !$leave->urgent) {
            $period = Carbon::parse($request->start_date)->daysUntil(Carbon::parse($request->end_date)->copy()->addDay());
            foreach ($period as $date) {
                $this->handleUrgentReplacementOrCancel($doctor, $date, $request->replacement_doctor_id);
            }
        }

        // Update leave
        $leave->update([
            'start_date'           => $request->start_date,
            'end_date'             => $request->end_date,
            'reason'               => $request->reason,
            'urgent'               => $isEmergency,
            'is_vacation'          => $isVacation,
            'replacement_doctor_id' => $isEmergency ? $request->replacement_doctor_id : null,
        ]);

        // Notify admins
        $admins = User::where('role_id', 1)->get();
        foreach ($admins as $admin) {
            $admin->notify(new DoctorLeaveCreated($leave, true));
        }

        return redirect()->route('doctor.leaves.index')->with('success', 'Cập nhật lịch nghỉ thành công.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        if (!$user || !$user->doctor) {
            return redirect()->route('home')->withErrors(['error' => 'Bạn không phải bác sĩ hoặc không có quyền truy cập.']);
        }

        $leave = DoctorLeave::where('id', $id)
            ->where('doctor_id', $user->doctor->id)
            ->firstOrFail();

        if ($leave->approved) {
            return redirect()->route('doctor.leaves.index')->with('error', 'Không thể xóa khi đã được duyệt.');
        }
        $leave->delete();

        return redirect()->route('doctor.leaves.index')->with('success', 'Xóa lịch nghỉ thành công.');
    }

    protected function handleUrgentReplacementOrCancel($doctor, Carbon $leaveDay, $replacementDoctorId = null)
    {
        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_time', $leaveDay->toDateString())
            ->where('status', '!=', 'cancelled')
            ->get();

        if ($appointments->isEmpty()) {
            return;
        }

        $replacement = null;
        if ($replacementDoctorId) {
            $replacement = Doctor::find($replacementDoctorId);
            if (
                $replacement &&
                $replacement->department_id === $doctor->department_id &&
                !DoctorLeave::where('doctor_id', $replacement->id)
                    ->where('approved', true)
                    ->whereDate('start_date', '<=', $leaveDay)
                    ->whereDate('end_date', '>=', $leaveDay)
                    ->exists()
            ) {
                // Valid replacement doctor
            } else {
                $replacement = null; // Invalid or unavailable
            }
        }

        foreach ($appointments as $appointment) {
            if ($replacement) {
                $appointment->doctor_id = $replacement->id;
                $appointment->save();

                // Gửi thông báo cho bệnh nhân
                $message = "Cuộc hẹn được chuyển sang bác sĩ {$replacement->name} do bác sĩ {$doctor->name} nghỉ đột xuất.";
                $appointment->user->notify(new \App\Notifications\AppointmentReassigned($appointment, $message));
            } else {
                $appointment->status = 'cancelled';
                $appointment->save();

                $message = "Cuộc hẹn bị hủy do bác sĩ {$doctor->name} nghỉ đột xuất và không có bác sĩ thay thế. Bạn có thể đặt lịch lại hoặc hủy lịch tại: ...";
                $appointment->user->notify(new \App\Notifications\AppointmentCancelledWithSuggestion($appointment, $message));
            }
        }
    }
}
