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

class SchedulesController extends Controller
{
    public function index(Request $request)
    {
        $workingSchedules = WorkingSchedule::with('shift')
            ->paginate(10); // Bạn có thể điều chỉnh số lượng phân trang

        return view('admin.schedules.index', compact('workingSchedules'));
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
