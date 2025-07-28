<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WorkingSchedule;
use App\Models\Shift;

class DoctorWorkingScheduleController extends Controller
{
    // Danh sách lịch làm việc của bác sĩ
    public function index(Request $request)
    {
        $doctorId = Auth::user()->doctor->id;

        $shifts = Shift::all();

        $query = WorkingSchedule::with('shift')
            ->where('doctor_id', $doctorId)
            ->orderBy('day', 'asc');

        // Lọc theo ngày bắt đầu
        if ($request->filled('from_date')) {
            $query->whereDate('day', '>=', $request->from_date);
        }

        // Lọc theo ngày kết thúc
        if ($request->filled('to_date')) {
            $query->whereDate('day', '<=', $request->to_date);
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo ca trực
        if ($request->filled('shift_id')) {
            $query->where('shift_id', $request->shift_id);
        }

        $workingSchedules = $query->paginate(10)->withQueryString(); // Giữ lại query khi phân trang

        return view('doctor.working-schedules.index', compact('workingSchedules', 'shifts'));
    }
    // Form tạo mới
    public function create()
    {
        $doctor = Auth::user()?->doctor;

        if (!$doctor) {
            return redirect()->back()->withErrors(['error' => 'Không thể xác định bác sĩ đăng nhập.']);
        }

        $shifts = Shift::all();
        $rooms = Room::all();

        return view('doctor.working-schedules.create', compact('shifts', 'doctor','rooms'));
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
            'room_id' => [
                'required',
                'exists:rooms,id',
            ],
        ], [
            'day.required' => 'Vui lòng chọn ngày làm việc.',
            'day.date' => 'Ngày không hợp lệ.',
            'day.after_or_equal' => 'Ngày làm việc phải là hôm nay hoặc trong tương lai.',
            'shift_ids.required' => 'Vui lòng chọn ít nhất một ca làm việc.',
            'shift_ids.*.exists' => 'Một hoặc nhiều ca làm việc không hợp lệ.',
            'room_id.required' => 'Vui lòng chọn phòng làm việc.',
            'room_id.exists' => 'Phòng làm việc không hợp lệ.',
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
                'room_id' => $validated['room_id']
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
            ->with('room')
            ->firstOrFail();

        // Lấy danh sách ca làm việc
        $shifts = Shift::all();
        $rooms = Room::all();   

        return view('doctor.working-schedules.edit', compact('schedule', 'shifts','rooms'));
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
            'room_id' => 'required|exists:rooms,id',
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
            'room_id' => $request->room_id,
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
