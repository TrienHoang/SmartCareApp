<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\DoctorLeave;
use App\Models\WorkingSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WorkingScheduleController extends Controller
{
    public function index()
    {
        return view('reception.schedules.index');
    }

    // Trang hiển thị lịch theo ngày
    public function show($date)
    {
        // Chuẩn hóa format ngày
        $selectedDate = Carbon::parse($date)->format('Y-m-d');

        // Lấy lịch làm việc kèm thông tin bác sĩ, ca trực, phòng
        $schedules = WorkingSchedule::with(['doctor.user', 'shift', 'room'])
            ->whereDate('day', $selectedDate)
            ->get();

        // Lấy danh sách bác sĩ đang xin nghỉ (đã duyệt) trong ngày này
        $leaves = DoctorLeave::where('approved', 1)
            ->whereDate('start_date', '<=', $selectedDate)
            ->whereDate('end_date', '>=', $selectedDate)
            ->pluck('doctor_id')
            ->toArray();

        return view('reception.schedules.show', compact('schedules', 'selectedDate', 'leaves'));
    }
}
