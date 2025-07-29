<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\DoctorLeave;
use App\Models\WorkingSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WorkingScheduleController extends Controller
{
    public function index(Request $request)
    {
        $departments = Department::where('is_active', 1)->orderBy('name')->get();
        $doctors = Doctor::with('user')->whereHas('user', function ($query) {
            $query->whereNotNull('full_name');
        })->get();

        $date = $request->input('date');
        $departmentId = $request->input('department');
        $doctorId = $request->input('doctor');
        $period = $request->input('period', 'week'); // Mặc định là tuần này

        $schedules = collect();
        $stats = [];
        $dayStats = [];
        $carbonDate = null;
        $leaves = [];
        $startDate = null;
        $endDate = null;

        // Xử lý thời gian dựa trên input
        if ($date) {
            // Nếu có ngày cụ thể, ưu tiên ngày đó
            $selectedDate = Carbon::parse($date)->format('Y-m-d');
            $carbonDate = Carbon::parse($selectedDate);
            $startDate = $selectedDate;
            $endDate = $selectedDate;
        } elseif ($doctorId || $departmentId) {
            // Nếu chọn bác sĩ hoặc chuyên khoa, hiển thị theo khoảng thời gian
            $now = Carbon::now();

            switch ($period) {
                case 'week':
                    $startDate = $now->startOfWeek()->format('Y-m-d');
                    $endDate = $now->endOfWeek()->format('Y-m-d');
                    break;
                case 'month':
                    $startDate = $now->startOfMonth()->format('Y-m-d');
                    $endDate = $now->endOfMonth()->format('Y-m-d');
                    break;
                case 'next_week':
                    $nextWeek = $now->addWeek();
                    $startDate = $nextWeek->startOfWeek()->format('Y-m-d');
                    $endDate = $nextWeek->endOfWeek()->format('Y-m-d');
                    break;
                case 'next_month':
                    $nextMonth = $now->addMonth();
                    $startDate = $nextMonth->startOfMonth()->format('Y-m-d');
                    $endDate = $nextMonth->endOfMonth()->format('Y-m-d');
                    break;
                default:
                    $startDate = $now->startOfWeek()->format('Y-m-d');
                    $endDate = $now->endOfWeek()->format('Y-m-d');
            }
        }

        // Lấy dữ liệu lịch làm việc nếu có điều kiện lọc
        if ($startDate && $endDate) {
            $query = WorkingSchedule::with(['doctor.user', 'doctor.department', 'shift', 'room'])
                ->where('status', 'Đã xét duyệt')
                ->whereBetween('day', [$startDate, $endDate]);

            if ($departmentId) {
                $query->whereHas('doctor', function ($q) use ($departmentId) {
                    $q->where('department_id', $departmentId);
                });
            }

            if ($doctorId) {
                $query->where('doctor_id', $doctorId);
            }

            $schedules = $query->orderBy('day', 'desc')
                ->orderBy('shift_id')
                ->paginate(10)
                ->withQueryString();

            // Lấy thông tin nghỉ phép cho khoảng thời gian
            $leaves = DoctorLeave::where('approved', 1)
                ->where(function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('start_date', [$startDate, $endDate])
                        ->orWhereBetween('end_date', [$startDate, $endDate])
                        ->orWhere(function ($q2) use ($startDate, $endDate) {
                            $q2->where('start_date', '<=', $startDate)
                                ->where('end_date', '>=', $endDate);
                        });
                })
                ->pluck('doctor_id')
                ->toArray();

            // Thống kê cho khoảng thời gian được chọn
            $dayStats = [
                'total_schedules' => $schedules->total(),
                'doctors_working' => $schedules->whereNotIn('doctor_id', $leaves)->count(),
                'doctors_on_leave' => count($leaves),
                'departments_active' => $schedules->pluck('doctor.department_id')->unique()->count(),
                'period_info' => [
                    'start' => Carbon::parse($startDate)->format('d/m/Y'),
                    'end' => Carbon::parse($endDate)->format('d/m/Y'),
                    'period_name' => $this->getPeriodName($period)
                ]
            ];
        }

        // Thống kê tổng (không thay đổi)
        $stats = [
            'total_doctors' => Doctor::count(),
            'working_today' => WorkingSchedule::whereDate('day', today())->distinct('doctor_id')->count(),
            'on_leave_today' => DoctorLeave::where('approved', 1)
                ->whereDate('start_date', '<=', today())
                ->whereDate('end_date', '>=', today())
                ->count(),
            'departments_count' => $departments->count()
        ];

        return view('reception.schedules.index', compact(
            'departments',
            'doctors',
            'schedules',
            'stats',
            'date',
            'departmentId',
            'doctorId',
            'period',
            'leaves',
            'dayStats',
            'carbonDate',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Lấy tên khoảng thời gian bằng tiếng Việt
     */
    private function getPeriodName($period)
    {
        $periodNames = [
            'week' => 'Tuần này',
            'month' => 'Tháng này',
            'next_week' => 'Tuần sau',
            'next_month' => 'Tháng sau'
        ];

        return $periodNames[$period] ?? 'Tuần này';
    }
}
