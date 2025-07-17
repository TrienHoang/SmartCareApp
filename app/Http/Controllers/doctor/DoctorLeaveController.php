<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorLeave;
use App\Models\User;
use App\Notifications\DoctorLeaveCreated;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorLeaveController extends Controller
{
    public function index()
    {
        $doctorId = Auth::user()->doctor->id;
        $leaves = DoctorLeave::where('doctor_id', $doctorId)
            ->orderByDesc('start_date')
            ->get();

        return view('doctor.doctor_leaves.index', compact('leaves'));
    }

    public function create()
    {
        return view('doctor.doctor_leaves.create');
    }

    public function store(Request $request)
    {
        $doctorId = Auth::user()->doctor->id;
        $isEmergency = $request->has('is_emergency'); // nghỉ đột xuất
        $isVacation = $request->has('is_vacation');   // nghỉ đi du lịch

        $today = Carbon::today();
        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $days = $start->diffInDays($end) + 1;

        // 1. Validate đầu vào
        $rules = [
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string', 'max:1000'],
        ];

        if (!$isEmergency && !$isVacation) {
            // Nếu không phải đột xuất hoặc du lịch thì bắt buộc báo trước 2 ngày
            $minDate = $today->copy()->addDays(2)->format('Y-m-d');
            $rules['start_date'][] = 'after_or_equal:' . $minDate;
        }

        $request->validate($rules, [
            'start_date.required' => 'Vui lòng chọn ngày bắt đầu.',
            'start_date.after_or_equal' => 'Ngày bắt đầu phải sau ít nhất 2 ngày kể từ hôm nay (nếu không phải đột xuất hoặc du lịch).',
            'end_date.required' => 'Vui lòng chọn ngày kết thúc.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
            'reason.required' => 'Vui lòng nhập lý do nghỉ.',
        ]);

        // 2. Kiểm tra nếu là đơn nghỉ đi du lịch
        if ($isVacation) {
            $minVacationDate = $today->copy()->addDays(15);
            if ($start->lt($minVacationDate)) {
                return redirect()->back()->withErrors([
                    'start_date' => 'Ngày nghỉ du lịch phải được đăng ký trước ít nhất 15 ngày.'
                ])->withInput();
            }

            if ($days > 3) {
                return redirect()->back()->withErrors([
                    'end_date' => 'Không được nghỉ du lịch quá 3 ngày.'
                ])->withInput();
            }
        }

        // 3. Giới hạn số ngày nghỉ (nếu không phải đột xuất hoặc du lịch)
        if (!$isEmergency && !$isVacation && $days > 3) {
            return redirect()->back()->withErrors([
                'end_date' => 'Không được nghỉ quá 3 ngày.'
            ])->withInput();
        }

        // 4. Mỗi tháng chỉ được đăng ký 1 đơn thường (trừ khi là đột xuất hoặc du lịch)
        $hasApproved = DoctorLeave::where('doctor_id', $doctorId)
            ->whereMonth('start_date', $start->month)
            ->whereYear('start_date', $start->year)
            ->where('approved', true)
            ->exists();

        if ($hasApproved && !$isEmergency && !$isVacation) {
            return redirect()->back()->withErrors([
                'start_date' => 'Bạn chỉ được nghỉ 1 lần mỗi tháng (đơn thường).'
            ])->withInput();
        }

        // 5. Kiểm tra trùng lịch làm việc (nếu không phải đơn đột xuất)
        $conflictWork = \App\Models\WorkingSchedule::where('doctor_id', $doctorId)
            ->whereDate('day', '>=', $start)
            ->whereDate('day', '<=', $end)
            ->exists();

        if ($conflictWork && !$isEmergency) {
            return redirect()->back()->withErrors([
                'start_date' => 'Bạn đã có lịch làm trong khoảng thời gian này!'
            ])->withInput();
        }

        // 6. Tạo đơn nghỉ
        $leave = DoctorLeave::create([
            'doctor_id'    => $doctorId,
            'start_date'   => $request->start_date,
            'end_date'     => $request->end_date,
            'reason'       => $request->reason,
            'approved'     => false,
            'is_emergency' => $isEmergency,
            'is_vacation'  => $isVacation,
        ]);

        // 7. Gửi thông báo đến admin
        $admins = \App\Models\User::where('role_id', 1)->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\DoctorLeaveCreated($leave, false));
        }

        return redirect()->route('doctor.leaves.index')->with('success', 'Đơn nghỉ đã gửi đến quản trị viên.');
    }

    protected function handleUrgentReplacement(Doctor $doctor, Carbon $leaveDay)
    {
        // Tìm các lịch hẹn trong ngày nghỉ
        $appointments = Appointment::whereHas('doctor', function ($query) use ($doctor) {
            $query->where('id', $doctor->id);
        })->whereDate('date', $leaveDay->toDateString())->get();

        if ($appointments->isEmpty()) return;

        $sameSpecialtyDoctors = Doctor::where('specialty_id', $doctor->specialty_id)
            ->where('id', '!=', $doctor->id)
            ->get();

        foreach ($appointments as $appointment) {
            // Gán bác sĩ thay thế đầu tiên có trong cùng khoa (nếu có)
            $replacement = $sameSpecialtyDoctors->first();
            if ($replacement) {
                $appointment->doctor_id = $replacement->id;
                $appointment->note = 'Đã chuyển sang bác sĩ thay thế do bác sĩ cũ nghỉ đột xuất.';
                $appointment->save();
            }
        }
    }

    public function show($id)
    {
        $leave = DoctorLeave::where('id', $id)
            ->where('doctor_id', Auth::user()->doctor->id)
            ->firstOrFail();

        return view('doctor.doctor_leaves.show', compact('leave'));
    }

    public function edit($id)
    {
        $leave = DoctorLeave::where('id', $id)
            ->where('doctor_id', Auth::user()->doctor->id)
            ->firstOrFail();

        if ($leave->approved) {
            return redirect()->route('doctor.leaves.index')
                ->with('error', 'Lịch nghỉ đã được duyệt. Không thể chỉnh sửa.');
        }

        return view('doctor.doctor_leaves.edit', compact('leave'));
    }

    public function update(Request $request, $id)
    {
        $leave = DoctorLeave::where('id', $id)
            ->where('doctor_id', Auth::user()->doctor->id)
            ->firstOrFail();

        if ($leave->approved) {
            return redirect()->route('doctor.leaves.index')->with('error', 'Không thể cập nhật khi đã được duyệt.');
        }

        $isEmergency = $request->has('is_emergency'); // nghỉ đột xuất
        $isVacation = $request->has('is_vacation');   // nghỉ đi du lịch
        $today = Carbon::today();
        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $days = $start->diffInDays($end) + 1;

        // 1. Validate đầu vào
        $rules = [
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string', 'max:1000'],
        ];

        if (!$isEmergency && !$isVacation) {
            // Nếu không phải đột xuất hoặc du lịch thì bắt buộc báo trước 2 ngày
            $minDate = $today->copy()->addDays(2)->format('Y-m-d');
            $rules['start_date'][] = 'after_or_equal:' . $minDate;
        }

        $request->validate($rules, [
            'start_date.required' => 'Vui lòng chọn ngày bắt đầu.',
            'start_date.after_or_equal' => 'Ngày bắt đầu phải sau ít nhất 2 ngày kể từ hôm nay (nếu không phải đột xuất hoặc du lịch).',
            'end_date.required' => 'Vui lòng chọn ngày kết thúc.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
            'reason.required' => 'Vui lòng nhập lý do nghỉ.',
        ]);

        // 2. Kiểm tra nếu là đơn nghỉ đi du lịch
        if ($isVacation) {
            $minVacationDate = $today->copy()->addDays(15);
            if ($start->lt($minVacationDate)) {
                return redirect()->back()->withErrors([
                    'start_date' => 'Ngày nghỉ du lịch phải được đăng ký trước ít nhất 15 ngày.'
                ])->withInput();
            }

            if ($days > 3) {
                return redirect()->back()->withErrors([
                    'end_date' => 'Không được nghỉ du lịch quá 3 ngày.'
                ])->withInput();
            }
        }

        // 3. Giới hạn số ngày nghỉ (nếu không phải đột xuất hoặc du lịch)
        if (!$isEmergency && !$isVacation && $days > 3) {
            return redirect()->back()->withErrors([
                'end_date' => 'Không được nghỉ quá 3 ngày.'
            ])->withInput();
        }

        // 4. Kiểm tra trùng lịch làm việc (nếu không phải đơn đột xuất)
        $conflictWork = \App\Models\WorkingSchedule::where('doctor_id', $doctorId)
            ->whereDate('day', '>=', $start)
            ->whereDate('day', '<=', $end)
            ->exists();

        if ($conflictWork && !$isEmergency) {
            return redirect()->back()->withErrors([
                'start_date' => 'Bạn đã có lịch làm trong khoảng thời gian này!'
            ])->withInput();
        }

        // 5. Cập nhật đơn nghỉ
        $leave->update([
            'start_date'   => $request->start_date,
            'end_date'     => $request->end_date,
            'reason'       => $request->reason,
            'is_emergency' => $isEmergency,
            'is_vacation'  => $isVacation,
        ]);

        // 6. Gửi thông báo đến admin
        $admins = User::where('role_id', 1)->get();
        foreach ($admins as $admin) {
            $admin->notify(new DoctorLeaveCreated($leave, true));
        }

        return redirect()->route('doctor.leaves.index')->with('success', 'Cập nhật lịch nghỉ thành công.');
    }

    public function destroy($id)
    {
        $leave = DoctorLeave::where('id', $id)
            ->where('doctor_id', Auth::user()->doctor->id)
            ->firstOrFail();

        if ($leave->approved) {
            return redirect()->route('doctor.leaves.index')->with('error', 'Không thể xóa khi đã được duyệt.');
        }
        $leave->delete();

        return redirect()->route('doctor.leaves.index')->with('success', 'Xóa lịch nghỉ thành công.');
    }
};
