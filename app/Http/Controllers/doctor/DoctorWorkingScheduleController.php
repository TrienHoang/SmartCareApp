<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Shift;
use App\Models\User;
use App\Models\WorkingSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NotifyAdminWorkingSchedule;
use Illuminate\Support\Facades\Mail;

class DoctorWorkingScheduleController extends Controller
{
    // Danh sách lịch làm việc
    public function index(Request $request)
    {
        $doctorId = Auth::user()->doctor->id;
        $shifts = Shift::all();

        $query = WorkingSchedule::with('shift')
            ->where('doctor_id', $doctorId)
            ->orderBy('day', 'asc');

        if ($request->filled('from_date')) {
            $query->whereDate('day', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('day', '<=', $request->to_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('shift_id')) {
            $query->where('shift_id', $request->shift_id);
        }

        $workingSchedules = $query->paginate(10)->withQueryString();

        return view('doctor.working-schedules.index', compact('workingSchedules', 'shifts'));
    }

    // Form tạo lịch
    public function create()
    {
        $doctor = Auth::user()?->doctor;

        if (!$doctor) {
            return redirect()->back()->withErrors(['error' => 'Không thể xác định bác sĩ đăng nhập.']);
        }

        $shifts = Shift::all();
        $rooms = Room::where('department_id', $doctor->department_id)->get();

        return view('doctor.working-schedules.create', compact('shifts', 'doctor', 'rooms'));
    }

    // Lưu lịch mới
    public function store(Request $request)
    {
        $doctor = Auth::user()?->doctor;

        if (!$doctor) {
            return response()->json(['message' => 'Không thể xác định bác sĩ đăng nhập.'], 401);
        }

        $validated = $request->validate([
            'day' => ['required', 'date', 'after_or_equal:' . now()->toDateString()],
            'shift_ids' => ['required', 'array'],
            'shift_ids.*' => ['integer', 'exists:shifts,id'],
            'room_id' => [
                'required',
                'exists:rooms,id',
                function ($attribute, $value, $fail) use ($doctor) {
                    $room = Room::find($value);
                    if (!$room || $room->department_id !== $doctor->department_id) {
                        $fail('Phòng làm việc không hợp lệ với khoa của bạn.');
                    }
                },
            ],
        ]);

        $created = 0;
        $duplicates = [];
        $room_conflicts = [];
        $newSchedules = [];

        foreach ($validated['shift_ids'] as $shiftId) {
            $exists = WorkingSchedule::where('doctor_id', $doctor->id)
                ->where('day', $validated['day'])
                ->where('shift_id', $shiftId)
                ->exists();

            if ($exists) {
                $duplicates[] = $shiftId;
                continue;
            }

            $roomUsed = WorkingSchedule::where('day', $validated['day'])
                ->where('shift_id', $shiftId)
                ->where('room_id', $validated['room_id'])
                ->exists();

            if ($roomUsed) {
                $room_conflicts[] = $shiftId;
                continue;
            }

            $schedule = WorkingSchedule::create([
                'doctor_id' => $doctor->id,
                'day' => $validated['day'],
                'shift_id' => $shiftId,
                'status' => 'Chờ xét duyệt',
                'room_id' => $validated['room_id'],
            ]);

            $newSchedules[] = $schedule;
            $created++;
        }

        if ($created > 0) {
            // Gửi thông báo đến admin
            $admins = User::where('role_id', 'admin')->get();
            Notification::send($admins, new NotifyAdminWorkingSchedule($doctor->user, $newSchedules));
        }

        if ($created === 0) {
            return response()->json([
                'message' => 'Tạo lịch làm việc không thành công. Tất cả các ca đã bị trùng hoặc phòng đã bị sử dụng.',
                'duplicates' => $duplicates,
                'room_conflicts' => $room_conflicts,
            ], 422);
        }

        return response()->json([
            'message' => "Tạo $created lịch làm việc thành công.",
            'duplicates' => $duplicates,
            'room_conflicts' => $room_conflicts,
        ], 200);
    }

    // Form chỉnh sửa
    public function edit($id)
    {
        $doctor = Auth::user()->doctor;

        $schedule = WorkingSchedule::where('id', $id)
            ->where('status', 'Chờ xét duyệt')
            ->where('doctor_id', $doctor->id)
            ->with('room')
            ->firstOrFail();

        $shifts = Shift::all();
        $rooms = Room::where('department_id', $doctor->department_id)->get();

        return view('doctor.working-schedules.edit', compact('schedule', 'shifts', 'rooms'));
    }

    // Cập nhật
    public function update(Request $request, $id)
    {
        $doctor = Auth::user()->doctor;

        $schedule = WorkingSchedule::where('id', $id)
            ->where('status', 'Chờ xét duyệt')
            ->where('doctor_id', $doctor->id)
            ->firstOrFail();

        $request->validate([
            'day' => ['required', 'date', 'after_or_equal:' . now()->toDateString()],
            'shift_id' => ['required', 'exists:shifts,id'],
            'room_id' => [
                'required',
                'exists:rooms,id',
                function ($attribute, $value, $fail) use ($doctor) {
                    $room = Room::find($value);
                    if (!$room || $room->department_id !== $doctor->department_id) {
                        $fail('Phòng làm việc không hợp lệ với khoa của bạn.');
                    }
                },
            ],
        ]);

        $exists = WorkingSchedule::where('doctor_id', $schedule->doctor_id)
            ->where('day', $request->day)
            ->where('shift_id', $request->shift_id)
            ->where('id', '!=', $schedule->id)
            ->exists();

        if ($exists) {
            return response()->json(['error' => 'Bạn đã có ca làm việc trùng lịch vào ngày này.'], 422);
        }

        $schedule->update([
            'day' => $request->day,
            'shift_id' => $request->shift_id,
            'room_id' => $request->room_id,
        ]);

        return response()->json(['message' => 'Cập nhật lịch làm việc thành công.']);
    }

    // Xoá
    public function destroy($id)
    {
        $schedule = WorkingSchedule::where('id', $id)
            ->where('status', 'Chờ xét duyệt')
            ->where('doctor_id', Auth::user()?->doctor->id)
            ->firstOrFail();

        $schedule->delete();

        return redirect()->route('doctor.working_schedules.index')->with('success', 'Xóa lịch làm việc thành công.');
    }
}
