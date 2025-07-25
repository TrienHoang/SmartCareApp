<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WorkingSchedule;
use App\Models\Shift;

class DoctorWorkingScheduleController extends Controller
{
    // Danh sách lịch làm việc của bác sĩ
    public function index()
    {
        $doctorId = Auth::id();

        // Lấy danh sách lịch làm việc và phân trang
        $workingSchedules = WorkingSchedule::with('shift')
            ->where('doctor_id', $doctorId)
            ->orderBy('day', 'asc')
            ->paginate(10); // Bạn có thể điều chỉnh số lượng phân trang

        return view('doctor.working-schedules.index', compact('workingSchedules'));
    }
    // Form tạo mới
    public function create()
    {
        $doctor = Auth::user()?->doctor;

        if (!$doctor) {
            return redirect()->back()->withErrors(['error' => 'Không thể xác định bác sĩ đăng nhập.']);
        }

        $shifts = Shift::all();

        return view('doctor.working-schedules.create', compact('shifts', 'doctor'));
    }


    // Lưu lịch mới
    public function store(Request $request)
    {
        $doctor = Auth::user()?->doctor;

        if (!$doctor) {
            return back()->withErrors(['error' => 'Không thể xác định bác sĩ đăng nhập.']);
        }

        $validated = $request->validate([
            'day' => [
                'required',
                'date',
                'after_or_equal:' . now()->toDateString(),
            ],
            'shift_ids' => [
                'required',
                'array',
            ],
            'shift_ids.*' => [
                'integer',
                'exists:shifts,id',
            ],
        ], [
            'day.required' => 'Vui lòng chọn ngày làm việc.',
            'day.date' => 'Ngày không hợp lệ.',
            'day.after_or_equal' => 'Ngày làm việc phải là hôm nay hoặc trong tương lai.',
            'shift_ids.required' => 'Vui lòng chọn ít nhất một ca làm việc.',
            'shift_ids.*.exists' => 'Một hoặc nhiều ca làm việc không hợp lệ.',
        ]);

        $created = 0;
        $duplicates = [];

        foreach ($validated['shift_ids'] as $shiftId) {
            $exists = WorkingSchedule::where('doctor_id', $doctor->id)
                ->where('day', $validated['day'])
                ->where('shift_id', $shiftId)
                ->exists();

            if ($exists) {
                $duplicates[] = $shiftId;
                continue;
            }

            WorkingSchedule::create([
                'doctor_id' => $doctor->id,
                'day' => $validated['day'],
                'shift_id' => $shiftId,
                'status' => 'Chờ xét duyệt',
            ]);

            $created++;
        }

        if ($created === 0) {
            return back()->withErrors(['error' => 'Tất cả các ca đã chọn đã tồn tại.']);
        }

        return redirect()->route('doctor.working_schedules.index')
            ->with('success', "Tạo $created lịch làm việc thành công. " . (count($duplicates) ? 'Một số ca đã tồn tại.' : ''));
    }



    // Form chỉnh sửa
    public function edit($id)
    {
        // Lấy lịch làm việc đang chờ xét duyệt của bác sĩ hiện tại
        $schedule = WorkingSchedule::where('id', $id)
            ->where('status', 'Chờ xét duyệt')
            ->where('doctor_id', Auth::user()->doctor->id ?? null)
            ->firstOrFail();

        // Lấy danh sách ca làm việc
        $shifts = Shift::all();

        return view('doctor.working-schedules.edit', compact('schedule', 'shifts'));
    }


    // Cập nhật lịch
    public function update(Request $request, $id)
    {
        // Lấy lịch làm việc đang chờ xét duyệt của bác sĩ hiện tại
        $schedule = WorkingSchedule::where('id', $id)
            ->where('status', 'Chờ xét duyệt')
            ->where('doctor_id', Auth::user()->doctor->id ?? null)
            ->firstOrFail();

        // Validate dữ liệu
        $request->validate([
            'day' => 'required|date|after_or_equal:' . now()->toDateString(),
            'shift_id' => 'required|exists:shifts,id',
        ]);

        // Kiểm tra trùng lịch (cùng ngày, cùng ca làm)
        $exists = WorkingSchedule::where('doctor_id', $schedule->doctor_id)
            ->where('day', $request->day)
            ->where('shift_id', $request->shift_id)
            ->where('id', '!=', $schedule->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['error' => 'Bạn đã có ca làm việc trùng lịch vào ngày này.']);
        }

        // Cập nhật lịch làm việc
        $schedule->update([
            'day' => $request->day,
            'shift_id' => $request->shift_id,
        ]);

        return redirect()->route('doctor.working_schedules.index')->with('success', 'Cập nhật lịch làm việc thành công.');
    }


    // Xóa lịch
    public function destroy($id)
    {
        $schedule = WorkingSchedule::where('id', $id)
            ->where('status', 'Chờ xét duyệt')
            ->where('doctor_id', Auth::user()?->doctor->id)
            ->firstOrFail();

        $schedule->delete();

        return redirect()->route('doctor.working-schedules.index')->with('success', 'Xóa lịch làm việc thành công.');
    }
}
