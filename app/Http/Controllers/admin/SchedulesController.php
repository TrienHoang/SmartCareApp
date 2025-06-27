<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\WorkingSchedule;
use Illuminate\Http\Request;

class SchedulesController extends Controller
{
    public function index(Request $request)
    {
        $query = WorkingSchedule::with('doctor.user');

        // Tìm kiếm theo tên bác sĩ
        if ($request->filled('keyword')) {
            $keyword = $request->get('keyword');
            $query->whereHas('doctor.user', function ($q) use ($keyword) {
                $q->where('full_name', 'like', '%' . $keyword . '%');
            });
        }

        // Lọc theo thứ
        if ($request->filled('day_of_week')) {
            $query->where('day_of_week', $request->get('day_of_week'));
        }

        // Sắp xếp
        $sortField = $request->get('field', 'id');
        $sortDirection = $request->get('sort', 'asc');

        if ($sortField === 'doctor_name') {
            $query->join('doctors', 'working_schedules.doctor_id', '=', 'doctors.id')
                ->join('users', 'doctors.user_id', '=', 'users.id')
                ->orderBy('users.full_name', $sortDirection)
                ->select('working_schedules.*');
        } else {
            $allowedSorts = ['id', 'day', 'day_of_week', 'start_time', 'end_time'];
            if (in_array($sortField, $allowedSorts)) {
                $query->orderBy($sortField, $sortDirection);
            }
        }

        $schedules = $query->paginate(10)->withQueryString();
        $doctors = Doctor::all();
        $keyword = $request->keyword;

        return view('admin.schedules.index', compact('schedules', 'doctors', 'keyword'));
    }


    public function create()
    {
        $doctors = Doctor::all();
        return view("admin.schedules.create", compact("doctors"));
    }
    public function store(Request $request)
    {
        $request->validate([
            "doctor_id" => "required|exists:doctors,id",
            'day' => 'required|date',
            'day_of_week' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ], [
            'doctor_id.required' => 'Vui lòng chọn bác sĩ.',
            'doctor_id.exists' => 'Bác sĩ không tồn tại.',
            'day.required' => 'Vui lòng chọn ngày làm việc.',
            'day.date' => 'Ngày không hợp lệ.',
            'day_of_week.required' => 'Vui lòng chọn thứ.',
            'start_time.required' => 'Vui lòng nhập giờ bắt đầu.',
            'start_time.date_format' => 'Định dạng giờ bắt đầu không hợp lệ (HH:mm).',
            'end_time.required' => 'Vui lòng nhập giờ kết thúc.',
            'end_time.date_format' => 'Định dạng giờ kết thúc không hợp lệ (HH:mm).',
            'end_time.after' => 'Giờ kết thúc phải sau giờ bắt đầu.',
        ]);


        WorkingSchedule::create($request->all());
        return redirect()->route('admin.schedules.index')->with('success', 'Schedule created successfully.');
    }

    public function edit($id)
    {
        $schedule = WorkingSchedule::findOrFail($id);
        $doctors = Doctor::all();
        return view("admin.schedules.edit", compact("schedule", "doctors"));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "doctor_id" => "required|exists:doctors,id",
            'day' => 'required|date',
            'day_of_week' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ], [
            'doctor_id.required' => 'Vui lòng chọn bác sĩ.',
            'doctor_id.exists' => 'Bác sĩ không tồn tại.',
            'day.required' => 'Vui lòng chọn ngày làm việc.',
            'day.date' => 'Ngày không hợp lệ.',
            'day_of_week.required' => 'Vui lòng chọn thứ.',
            'start_time.required' => 'Vui lòng nhập giờ bắt đầu.',
            'start_time.date_format' => 'Định dạng giờ bắt đầu không hợp lệ (HH:mm).',
            'end_time.required' => 'Vui lòng nhập giờ kết thúc.',
            'end_time.date_format' => 'Định dạng giờ kết thúc không hợp lệ (HH:mm).',
            'end_time.after' => 'Giờ kết thúc phải sau giờ bắt đầu.',
        ]);

        $schedule = WorkingSchedule::findOrFail($id);
        $schedule->update($request->all());
        return redirect()->route('admin.schedules.index')->with('success', 'Schedule updated successfully.');
    }

    public function destroy($id)
    {
        $schedule = WorkingSchedule::findOrFail($id);
        $schedule->delete();
        return redirect()->route('admin.schedules.index')->with('success', 'Schedule deleted successfully.');
    }
    public function show($id)
    {
        $schedule = WorkingSchedule::findOrFail($id);
        return view("admin.schedules.show", compact("schedule"));
    }
}
