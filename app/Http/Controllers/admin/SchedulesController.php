<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Room;
use App\Models\DoctorLeave;
use App\Models\WorkingSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class SchedulesController extends Controller
{

    public function index(Request $request)
    {
        $query = WorkingSchedule::with('shift', 'doctor.user');

        // Lọc theo ngày làm việc (khoảng từ start_date đến end_date)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('day', [$request->start_date, $request->end_date]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('day', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('day', '<=', $request->end_date);
        }

        // Lọc theo tên bác sĩ hoặc ca làm việc
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('doctor.user', function ($q) use ($search) {
                    $q->where('full_name', 'like', '%' . $search . '%');
                })->orWhereHas('shift', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lấy danh sách lịch làm việc với phân trang
        $workingSchedules = $query->orderByDesc('id')->paginate(10);

        // Tính toán số liệu thống kê (không phân trang)
        $approvedCount = WorkingSchedule::where('status', 'Đã xét duyệt')->count();
        $pendingCount = WorkingSchedule::where('status', 'Chờ xét duyệt')->count();
        $thisWeekCount = WorkingSchedule::where('day', '>=', now()->startOfWeek())
            ->where('day', '<=', now()->endOfWeek())
            ->count();
        $overdueCount = WorkingSchedule::where('day', '<', now())
            ->where('status', 'Chờ xét duyệt')
            ->count();

        return view('admin.schedules.index', compact(
            'workingSchedules',
            'approvedCount',
            'pendingCount',
            'thisWeekCount',
            'overdueCount'
        ));
    }

    public function show($id)
    {
        $schedule = WorkingSchedule::with(['doctor.user', 'room', 'shift'])->findOrFail($id);
        return view('admin.schedules.show', compact('schedule'));
    }
    public function status($id)
    {
        try {
            $schedule = WorkingSchedule::findOrFail($id);
            Log::info("ID: $id, Current Status: {$schedule->status}");

            if ($schedule->status === 'Chờ xét duyệt') {
                $schedule->status = 'Đã xét duyệt';
                if ($schedule->save()) {
                    Log::info("Updated status to Đã xét duyệt for ID: $id");
                    return redirect()->route('admin.schedules.index')->with('success', 'Đã xét duyệt lịch làm việc.');
                }
                Log::error("Failed to save status Đã xét duyệt for ID: $id");
                return redirect()->route('admin.schedules.index')->with('error', 'Không thể lưu trạng thái Đã xét duyệt.');
            }

            Log::info("Status already Đã xét duyệt for ID: $id");
            return redirect()->route('admin.schedules.index')->with('info', 'Lịch làm việc đã được xét duyệt trước đó.');
        } catch (\Exception $e) {
            Log::error("Error updating schedule $id: " . $e->getMessage());
            return redirect()->route('admin.schedules.index')->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
