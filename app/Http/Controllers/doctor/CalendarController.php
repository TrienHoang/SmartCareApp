<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Appointment;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        return view('doctor.calendar.index');
    }

    public function events(Request $request)
    {
        // Kiểm tra quyền truy cập
        if (!Auth::check() || Auth::user()->role_id != 2) {
            Log::warning('Truy cập events không hợp lệ: ' . (Auth::check() ? Auth::user()->email : 'Chưa đăng nhập'));
            return response()->json(['error' => 'Bạn không có quyền truy cập. Chỉ có bác sĩ mới xem được.'], 403);
        }

        $doctor = Auth::user()->doctor;
        if (!$doctor) {
            Log::warning('Không tìm thấy thông tin bác sĩ cho user: ' . Auth::id());
            return response()->json(['error' => 'Không tìm thấy thông tin bác sĩ.'], 403);
        }

        $doctorId = $doctor->id;

        // Xử lý thời gian
        $start = Carbon::parse($request->input('start'))->timezone('Asia/Ho_Chi_Minh');
        $end = Carbon::parse($request->input('end'))->timezone('Asia/Ho_Chi_Minh')->endOfDay();

        // Lấy các tham số tìm kiếm
        $keyword = $request->input('keyword');
        $searchDate = $request->input('date');
        $status = $request->input('status');

        Log::info('Yêu cầu lịch hẹn với bộ lọc:', [
            'doctor_id' => $doctorId,
            'start' => $start->toDateTimeString(),
            'end' => $end->toDateTimeString(),
            'keyword' => $keyword,
            'search_date' => $searchDate,
            'status' => $status
        ]);

        // Tạo query cơ bản
        $query = Appointment::with(['patient', 'service'])
            ->where('doctor_id', $doctorId);

        // Áp dụng bộ lọc theo thời gian
        if ($searchDate) {
            // Nếu có ngày tìm kiếm cụ thể, chỉ lấy lịch hẹn trong ngày đó
            $searchDateStart = Carbon::parse($searchDate)->timezone('Asia/Ho_Chi_Minh')->startOfDay();
            $searchDateEnd = Carbon::parse($searchDate)->timezone('Asia/Ho_Chi_Minh')->endOfDay();
            $query->whereBetween('appointment_time', [$searchDateStart, $searchDateEnd]);
        } else {
            // Nếu không có ngày tìm kiếm, sử dụng khoảng thời gian từ calendar
            $query->whereBetween('appointment_time', [$start, $end]);
        }

        // Áp dụng bộ lọc theo từ khóa
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->whereHas('patient', function ($patientQuery) use ($keyword) {
                    $patientQuery->where('full_name', 'like', '%' . $keyword . '%')
                               ->orWhere('phone', 'like', '%' . $keyword . '%')
                               ->orWhere('email', 'like', '%' . $keyword . '%');
                })
                ->orWhereHas('service', function ($serviceQuery) use ($keyword) {
                    $serviceQuery->where('name', 'like', '%' . $keyword . '%');
                })
                ->orWhere('reason', 'like', '%' . $keyword . '%')
                ->orWhere('notes', 'like', '%' . $keyword . '%');
            });
        }

        // Áp dụng bộ lọc theo trạng thái
        if ($status) {
            $query->where('status', $status);
        }

        // Thực hiện query
        $appointments = $query->orderBy('appointment_time', 'asc')->get();

        Log::info('Số lượng cuộc hẹn tìm thấy: ' . $appointments->count());

        // Chuyển đổi dữ liệu thành format cho FullCalendar
        $events = $appointments->map(function ($appointment) {
            $statusColor = $this->getStatusColor($appointment->status);
            $statusText = $this->getStatusText($appointment->status);

            return [
                'id' => 'appt_' . $appointment->id,
                'title' => $this->formatEventTitle($appointment),
                'start' => $appointment->appointment_time->timezone('Asia/Ho_Chi_Minh')->toIso8601String(),
                'end' => $appointment->end_time
                    ? $appointment->end_time->timezone('Asia/Ho_Chi_Minh')->toIso8601String()
                    : $appointment->appointment_time->addMinutes(30)->toIso8601String(),
                'color' => $statusColor,
                'url' => route('doctor.appointments.show', $appointment->id),
                'extendedProps' => [
                    'status' => $statusText,
                    'reason' => $appointment->reason,
                    'notes' => $appointment->notes,
                    'check_in_time' => $appointment->check_in_time,
                    'patient' => $appointment->patient->full_name ?? null,
                    'patient_phone' => $appointment->patient->phone ?? null,
                    'service' => $appointment->service->name ?? null,
                    'cancel_reason' => $appointment->cancel_reason,
                    'appointment_id' => $appointment->id,
                ],
            ];
        });

        return response()->json($events->toArray());
    }

    /**
     * Refresh calendar - alias for events method
     */
    public function refresh(Request $request)
    {
        return $this->events($request);
    }

    /**
     * Get status color based on appointment status
     */
    private function getStatusColor($status)
    {
        return match ($status) {
            'pending' => '#ffc107',      // Vàng - Chờ xử lý
            'confirmed' => '#28a745',    // Xanh lá - Đã xác nhận
            'completed' => '#007bff',    // Xanh dương - Hoàn thành
            'cancelled' => '#dc3545',    // Đỏ - Đã hủy
            'no_show' => '#6f42c1',      // Tím - Không đến
            'rescheduled' => '#fd7e14',  // Cam - Đã dời lịch
            default => '#6c757d',        // Xám - Mặc định
        };
    }

    /**
     * Get status text in Vietnamese
     */
    private function getStatusText($status)
    {
        return match ($status) {
            'pending' => 'Chờ xử lý',
            'confirmed' => 'Đã xác nhận',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
            'no_show' => 'Không đến',
            'rescheduled' => 'Đã dời lịch',
            default => ucfirst($status),
        };
    }

    /**
     * Format event title for calendar display
     */
    private function formatEventTitle($appointment)
    {
        $patientName = $appointment->patient->full_name ?? 'Bệnh nhân';
        $serviceName = $appointment->service->name ?? 'Dịch vụ';
        $statusIcon = $this->getStatusIcon($appointment->status);
        
        return $statusIcon . ' ' . $patientName . ' - ' . $serviceName;
    }

    /**
     * Get status icon
     */
    private function getStatusIcon($status)
    {
        return match ($status) {
            'pending' => '⏳',
            'confirmed' => '✅',
            'completed' => '🏁',
            'cancelled' => '❌',
            'no_show' => '👻',
            'rescheduled' => '📅',
            default => '🩺',
        };
    }

    /**
     * Test database connection and data
     */
    public function testDb()
    {
        try {
            $appointments = Appointment::with(['patient', 'service'])
                ->latest()
                ->take(5)
                ->get();

            Log::info('Kiểm tra dữ liệu lịch hẹn:', $appointments->toArray());

            return response()->json([
                'status' => 'success',
                'message' => 'Dữ liệu đã được truy xuất thành công',
                'total_appointments' => Appointment::count(),
                'recent_appointments' => $appointments->count(),
                'data' => $appointments->map(function ($appointment) {
                    return [
                        'id' => $appointment->id,
                        'patient' => $appointment->patient->full_name ?? 'N/A',
                        'service' => $appointment->service->name ?? 'N/A',
                        'appointment_time' => $appointment->appointment_time,
                        'status' => $appointment->status,
                    ];
                }),
            ]);
        } catch (\Exception $e) {
            Log::error('Lỗi khi kiểm tra database:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi khi truy xuất dữ liệu: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get appointment statistics for dashboard
     */
    public function getStatistics(Request $request)
    {
        if (!Auth::check() || Auth::user()->role_id != 2) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $doctor = Auth::user()->doctor;
        if (!$doctor) {
            return response()->json(['error' => 'Doctor not found'], 403);
        }

        $doctorId = $doctor->id;
        $today = Carbon::today('Asia/Ho_Chi_Minh');
        $thisWeek = Carbon::now('Asia/Ho_Chi_Minh')->startOfWeek();
        $thisMonth = Carbon::now('Asia/Ho_Chi_Minh')->startOfMonth();

        $stats = [
            'today' => [
                'total' => Appointment::where('doctor_id', $doctorId)
                    ->whereDate('appointment_time', $today)
                    ->count(),
                'pending' => Appointment::where('doctor_id', $doctorId)
                    ->whereDate('appointment_time', $today)
                    ->where('status', 'pending')
                    ->count(),
                'confirmed' => Appointment::where('doctor_id', $doctorId)
                    ->whereDate('appointment_time', $today)
                    ->where('status', 'confirmed')
                    ->count(),
                'completed' => Appointment::where('doctor_id', $doctorId)
                    ->whereDate('appointment_time', $today)
                    ->where('status', 'completed')
                    ->count(),
            ],
            'this_week' => [
                'total' => Appointment::where('doctor_id', $doctorId)
                    ->where('appointment_time', '>=', $thisWeek)
                    ->count(),
            ],
            'this_month' => [
                'total' => Appointment::where('doctor_id', $doctorId)
                    ->where('appointment_time', '>=', $thisMonth)
                    ->count(),
            ]
        ];

        return response()->json($stats);
    }
}