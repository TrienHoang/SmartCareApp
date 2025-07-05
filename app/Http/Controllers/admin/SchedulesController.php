<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Room;
use App\Models\DoctorLeave;
use App\Models\WorkingSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SchedulesController extends Controller
{
    public function index(Request $request)
    {
        $query = WorkingSchedule::with(['doctor.user', 'room']);

        // Lọc theo từ khóa (tên bác sĩ)
        if ($request->filled('keyword')) {
            $keyword = $request->get('keyword');
            $query->whereHas('doctor.user', function ($q) use ($keyword) {
                $q->where('full_name', 'like', "%$keyword%");
            });
        }

        // Lọc theo ngày trong tuần
        if ($request->filled('day_of_week')) {
            $query->where('day_of_week', $request->get('day_of_week'));
        }

        // Lọc theo phòng
        if ($request->filled('room_id')) {
            $query->where('room_id', $request->get('room_id'));
        }

        // Sắp xếp
        $sortField = $request->get('field', 'day');
        $sortDirection = $request->get('sort', 'desc'); // Mặc định mới nhất lên đầu

        if ($sortField === 'doctor_name') {
            $query->join('doctors', 'working_schedules.doctor_id', '=', 'doctors.id')
                ->join('users', 'doctors.user_id', '=', 'users.id')
                ->orderBy('users.full_name', $sortDirection)
                ->select('working_schedules.*');
        } elseif ($sortField === 'room_name') {
            $query->join('rooms', 'working_schedules.room_id', '=', 'rooms.id')
                ->orderBy('rooms.name', $sortDirection)
                ->select('working_schedules.*');
        } else {
            $allowedSorts = ['id', 'day', 'day_of_week', 'start_time', 'end_time'];
            if (in_array($sortField, $allowedSorts)) {
                $query->orderBy($sortField, $sortDirection);
            }
        }

        $schedules = $query->paginate(10)->withQueryString();
        $doctors = Doctor::all();
        $rooms = Room::all();

        // Thống kê
        $stats = [
            'total_doctors' => Doctor::count(),
            'total_schedules' => WorkingSchedule::count(),
            'today_schedules' => WorkingSchedule::where('day', date('Y-m-d'))->count(),
            'no_schedule_doctors' => Doctor::count() - $schedules->pluck('doctor_id')->unique()->count(),
        ];

        return view('admin.schedules.index', compact('schedules', 'doctors', 'rooms', 'stats'));
    }


    public function create()
    {
        $doctors = Doctor::with('user')
            ->whereHas('user', fn($q) => $q->where('role_id', 2))
            ->get();

        // Lấy toàn bộ lịch đã có theo bác sĩ
        $existingSchedules = WorkingSchedule::select('doctor_id', 'day')
            ->get()
            ->groupBy('doctor_id')
            ->map(fn($items) => $items->pluck('day')->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d')));

        return view("admin.schedules.create", compact("doctors", "existingSchedules"));
    }

    public function store(Request $request)
    {
        $this->validateSchedule($request);

        WorkingSchedule::create($request->only([
            'doctor_id',
            'day',
            'day_of_week',
            'start_time',
            'end_time'
        ]));

        return redirect()->route('admin.schedules.index')->with('success', 'Tạo lịch làm việc thành công.');
    }

    public function edit($id)
    {
        $schedule = WorkingSchedule::findOrFail($id);
        $doctors = Doctor::with('user')
            ->whereHas('user', fn($q) => $q->where('role_id', 2))
            ->get();

        // Lấy toàn bộ lịch đã có theo bác sĩ
        $existingSchedules = WorkingSchedule::select('doctor_id', 'day')
            ->get()
            ->groupBy('doctor_id')
            ->map(fn($items) => $items->pluck('day')->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d')));

        return view("admin.schedules.edit", compact("schedule", "doctors", "existingSchedules"));
    }

    public function update(Request $request, $id)
    {
        $schedule = WorkingSchedule::findOrFail($id);

        $this->validateSchedule($request, $schedule->id);

        $schedule->update($request->only([
            'doctor_id',
            'day',
            'day_of_week',
            'start_time',
            'end_time'
        ]));

        return redirect()->route('admin.schedules.index')->with('success', 'Cập nhật lịch thành công.');
    }
    public function destroy($id)
    {
        $schedule = WorkingSchedule::findOrFail($id);
        $schedule->delete();
        return redirect()->route('admin.schedules.index')->with('success', 'Xóa lịch thành công.');
    }

    public function show($id)
    {
        $schedule = WorkingSchedule::with(['doctor.user', 'room'])->findOrFail($id);
        return view('admin.schedules.show', compact('schedule'));
    }

    private function validateSchedule(Request $request, $excludeId = null)
    {
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:doctors,id',
            'day' => 'required|date|after:' . now()->format('Y-m-d'),
            'day_of_week' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ], [
            'doctor_id.required' => 'Vui lòng chọn bác sĩ.',
            'day.required' => 'Vui lòng chọn ngày làm việc.',
            'day.after' => 'Ngày làm việc phải sau hôm nay.',
            'day_of_week.required' => 'Vui lòng chọn thứ.',
            'start_time.required' => 'Vui lòng nhập giờ bắt đầu.',
            'end_time.required' => 'Vui lòng nhập giờ kết thúc.',
            'end_time.after' => 'Giờ kết thúc phải sau giờ bắt đầu.',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $doctorId = $request->doctor_id;
        $roomId = $request->room_id;
        $day = $request->day;
        $start = $request->start_time;
        $end = $request->end_time;

        // Kiểm tra đơn nghỉ đã được duyệt
        $hasApprovedLeave = DoctorLeave::where('doctor_id', $doctorId)
            ->where('approved', 1)
            ->whereDate('start_date', '<=', $day)
            ->whereDate('end_date', '>=', $day)
            ->exists();

        if ($hasApprovedLeave) {
            throw ValidationException::withMessages([
                'day' => 'Bác sĩ đang có lịch nghỉ đã được duyệt vào ngày này.'
            ]);
        }

        // Kiểm tra đơn nghỉ CHƯA duyệt
        $hasPendingLeave = DoctorLeave::where('doctor_id', $doctorId)
            ->where('approved', 0)
            ->whereDate('start_date', '<=', $day)
            ->whereDate('end_date', '>=', $day)
            ->exists();

        if ($hasPendingLeave) {
            throw ValidationException::withMessages([
                'day' => 'Bác sĩ này có đơn xin nghỉ phép vào thời gian này, xin mời liên hệ lại với bác sĩ.'
            ]);
        }

        // Kiểm tra trạng thái "offline" dựa trên last_login
        $ninetyDaysAgo = now()->subDays(90);
        $doctorUser = Doctor::with('user')->find($doctorId)->user;
        if ($doctorUser && $doctorUser->last_login && $doctorUser->last_login < $ninetyDaysAgo && $doctorUser->status == 'online') {
            throw ValidationException::withMessages([
                'doctor_id' => 'Bác sĩ này chưa hoạt động trong 90 ngày và đã bị chuyển sang trạng thái offline.'
            ]);
        }

        // Check trùng ngày với bác sĩ (bất kể giờ)
        $dateConflict = WorkingSchedule::where('doctor_id', $doctorId)
            ->whereDate('day', $day)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists();

        if ($dateConflict) {
            throw ValidationException::withMessages([
                'day' => 'Bác sĩ đã có lịch làm việc vào ngày này.'
            ]);
        }

        // Check trùng giờ phòng
        $roomConflict = WorkingSchedule::where('room_id', $roomId)
            ->whereDate('day', $day)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->where(function ($q) use ($start, $end) {
                $q->where(function ($q2) use ($start, $end) {
                    $q2->where('start_time', '<', $end)
                        ->where('end_time', '>', $start);
                });
            })->exists();

        if ($roomConflict) {
            throw ValidationException::withMessages([
                'room_id' => 'Phòng đã có người sử dụng trong thời gian này.'
            ]);
        }

        // Check trùng giờ với lịch bác sĩ
        $doctorConflict = WorkingSchedule::where('doctor_id', $doctorId)
            ->whereDate('day', $day)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->where(function ($q) use ($start, $end) {
                $q->where(function ($q2) use ($start, $end) {
                    $q2->where('start_time', '<', $end)
                        ->where('end_time', '>', $start);
                });
            })->exists();

        if ($doctorConflict) {
            throw ValidationException::withMessages([
                'start_time' => 'Bác sĩ đã có ca làm trùng giờ.'
            ]);
        }
    }
}
