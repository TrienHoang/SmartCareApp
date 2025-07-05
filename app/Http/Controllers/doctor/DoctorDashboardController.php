<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Prescription;
use App\Models\Review;
use App\Models\Room;
use App\Models\WorkingSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\DoctorStatsExport;

class DoctorDashboardController extends Controller
{
    public function index(Request $request)
    {
        $doctor = Doctor::where('user_id', Auth::id())->firstOrFail();

        $type = $request->input('type', 'month');
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $request->validate([
            'type' => 'in:month,year,custom',
            'year' => 'required_if:type,month,year|integer|min:2000|max:' . now()->year,
            'month' => 'required_if:type,month|integer|min:1|max:12',
            'start_date' => 'required_if:type,custom|date',
            'end_date' => 'required_if:type,custom|date|after_or_equal:start_date',
        ]);

        // Query lịch hẹn
        $appointmentsQuery = Appointment::with(['service'])
            ->where('doctor_id', $doctor->id)
            ->select('id', 'patient_id', 'doctor_id', 'service_id', 'appointment_time', 'status');

        if ($type === 'month') {
            $appointmentsQuery->whereYear('appointment_time', $year)
                ->whereMonth('appointment_time', $month);
        } elseif ($type === 'year') {
            $appointmentsQuery->whereYear('appointment_time', $year);
        } elseif ($type === 'custom' && $startDate && $endDate) {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);
            if ($end->diffInDays($start) > 62) {
                return redirect()->back()->withErrors(['end_date' => 'Khoảng thời gian tối đa là 62 ngày.']);
            }
            $appointmentsQuery->whereBetween('appointment_time', [$startDate, $endDate]);
        }

        // Tổng hợp lịch hẹn
        $totalAppointments = $appointmentsQuery->count();
        $todayAppointments = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_time', now()->toDateString())
            ->count();

        $appointments_pending = $appointmentsQuery->clone()->where('status', 'pending')->count();
        $appointments_confirmed = $appointmentsQuery->clone()->where('status', 'confirmed')->count();
        $appointments_completed = $appointmentsQuery->clone()->where('status', 'completed')->count();
        $appointments_cancelled = $appointmentsQuery->clone()->where('status', 'cancelled')->count();

        $totalRevenue = $appointmentsQuery->clone()
            ->where('status', 'completed')
            ->with('service')
            ->get()
            ->sum(fn($a) => $a->service->price ?? 0);

        $totalPatients = $appointmentsQuery->clone()->distinct('patient_id')->count('patient_id');

        $successRate = $totalAppointments > 0 ? round(($appointments_completed / $totalAppointments) * 100, 1) : 0;
        $cancelRate = $totalAppointments > 0 ? round(($appointments_cancelled / $totalAppointments) * 100, 1) : 0;

        $statLabels = [];
        $statBookings = [];
        $statRevenue = [];

        if ($type === 'month') {
            $daysInMonth = Carbon::create($year, $month)->daysInMonth;
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = Carbon::create($year, $month, $day)->toDateString();
                $statLabels[] = "Ngày $day";
                $statBookings[] = Appointment::where('doctor_id', $doctor->id)
                    ->whereDate('appointment_time', $date)
                    ->count();
                $statRevenue[] = Appointment::where('doctor_id', $doctor->id)
                    ->whereDate('appointment_time', $date)
                    ->where('status', 'completed')
                    ->with('service')
                    ->get()
                    ->sum(fn($a) => $a->service->price ?? 0);
            }
        } elseif ($type === 'year') {
            for ($m = 1; $m <= 12; $m++) {
                $statLabels[] = "Tháng $m";
                $statBookings[] = Appointment::where('doctor_id', $doctor->id)
                    ->whereYear('appointment_time', $year)
                    ->whereMonth('appointment_time', $m)
                    ->count();
                $statRevenue[] = Appointment::where('doctor_id', $doctor->id)
                    ->whereYear('appointment_time', $year)
                    ->whereMonth('appointment_time', $m)
                    ->where('status', 'completed')
                    ->with('service')
                    ->get()
                    ->sum(fn($a) => $a->service->price ?? 0);
            }
        } elseif ($type === 'custom') {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);
            $days = $start->diffInDays($end) + 1;
            for ($i = 0; $i < $days; $i++) {
                $date = $start->copy()->addDays($i)->toDateString();
                $statLabels[] = $date;
                $statBookings[] = Appointment::where('doctor_id', $doctor->id)
                    ->whereDate('appointment_time', $date)
                    ->count();
                $statRevenue[] = Appointment::where('doctor_id', $doctor->id)
                    ->whereDate('appointment_time', $date)
                    ->where('status', 'completed')
                    ->with('service')
                    ->get()
                    ->sum(fn($a) => $a->service->price ?? 0);
            }
        }

        // Tăng trưởng
        $previousPeriod = Appointment::where('doctor_id', $doctor->id);

        if ($type === 'month') {
            $previousPeriod->whereYear('appointment_time', $month == 1 ? $year - 1 : $year)
                ->whereMonth('appointment_time', $month == 1 ? 12 : $month - 1);
            $growthLabel = 'So với tháng trước';
        } elseif ($type === 'year') {
            $previousPeriod->whereYear('appointment_time', $year - 1);
            $growthLabel = 'So với năm trước';
        } elseif ($type === 'custom') {
            $start = Carbon::parse($startDate);
            $days = Carbon::parse($endDate)->diffInDays($start);
            $previousPeriod->whereBetween('appointment_time', [
                $start->copy()->subDays($days + 1)->toDateString(),
                $start->copy()->subDay()->toDateString()
            ]);
            $growthLabel = 'So với kỳ trước';
        }

        $currentBookings = $appointmentsQuery->count();
        $previousBookings = $previousPeriod->count();
        $growthValue = $previousBookings > 0 ? round((($currentBookings - $previousBookings) / $previousBookings) * 100, 1) : ($currentBookings > 0 ? 100 : 0);

        $currentRevenue = $appointmentsQuery->clone()
            ->where('status', 'completed')->with('service')
            ->get()->sum(fn($a) => $a->service->price ?? 0);

        $previousRevenue = $previousPeriod->clone()
            ->where('status', 'completed')->with('service')
            ->get()->sum(fn($a) => $a->service->price ?? 0);

        $revenueGrowth = $previousRevenue > 0 ? round((($currentRevenue - $previousRevenue) / $previousRevenue) * 100, 1) : ($currentRevenue > 0 ? 100 : 0);
        $revenueLabel = $growthLabel;

        // Lịch làm việc (ca trực)
        $schedulesQuery = WorkingSchedule::with('room')->where('doctor_id', $doctor->id);
        if ($request->filled('day_of_week')) {
            $schedulesQuery->where('day_of_week', $request->get('day_of_week'));
        }
        if ($request->filled('month') && $request->filled('year')) {
            $schedulesQuery->whereMonth('day', $month)->whereYear('day', $year);
        }
        $schedules = $schedulesQuery->paginate(10)->withQueryString();

        // Thống kê số ca trực
        $scheduleStats = WorkingSchedule::where('doctor_id', $doctor->id);
        if ($type === 'month') {
            $scheduleStats->whereYear('day', $year)->whereMonth('day', $month);
        } elseif ($type === 'year') {
            $scheduleStats->whereYear('day', $year);
        } elseif ($type === 'custom' && $startDate && $endDate) {
            $scheduleStats->whereBetween('day', [$startDate, $endDate]);
        }
        $totalSchedules = $scheduleStats->count();
        $todaySchedules = WorkingSchedule::where('doctor_id', $doctor->id)
            ->whereDate('day', now()->toDateString())->count();

        // Toa thuốc và đánh giá
        $stats = [
            'total_appointments' => $totalAppointments,
            'today_appointments' => $todayAppointments,
            'upcoming_appointments' => Appointment::where('doctor_id', $doctor->id)
                ->where('appointment_time', '>=', now())->count(),
            'total_prescriptions' => Prescription::where('doctor_id', $doctor->id)->count(),
            'today_prescriptions' => Prescription::where('doctor_id', $doctor->id)
                ->whereDate('created_at', now())->count(),
            'average_rating' => Review::where('doctor_id', $doctor->id)->avg('rating') ?? 0,
            'total_reviews' => Review::where('doctor_id', $doctor->id)->count(),
            'today_schedules' => $todaySchedules,
            'total_schedules' => $totalSchedules,
        ];

        $doctor->average_rating = number_format($stats['average_rating'], 1) ?: '-';
        $rooms = Room::all();

        $successAppointments = $appointments_completed;
        $cancelAppointments = $appointments_cancelled;

        return view('doctor.dashboard.index', compact(
            'doctor',
            'schedules',
            'rooms',
            'stats',
            'totalRevenue',
            'totalPatients',
            'todayAppointments',
            'appointments_pending',
            'appointments_confirmed',
            'appointments_completed',
            'appointments_cancelled',
            'statLabels',
            'statBookings',
            'statRevenue',
            'growthValue',
            'revenueGrowth',
            'successRate',
            'cancelRate',
            'growthLabel',
            'revenueLabel',
            'type',
            'successAppointments',
            'cancelAppointments',
            'successAppointments',
            'totalAppointments'
        ));
    }

    public function exportExcel(Request $request, $doctorId)
    {
        $doctor = Doctor::findOrFail($doctorId);
        return Excel::download(new DoctorStatsExport($doctor, $request->all()), 'doctor_stats_' . $doctorId . '.xlsx');
    }

    public function exportPdf(Request $request, $doctorId)
    {
        $doctor = Doctor::findOrFail($doctorId);
        $type = $request->input('type', 'month');
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $appointmentsQuery = Appointment::with(['service'])
            ->where('doctor_id', $doctor->id)
            ->select('id', 'patient_id', 'doctor_id', 'service_id', 'appointment_time', 'status');

        if ($type === 'month') {
            $appointmentsQuery->whereYear('appointment_time', $year)
                ->whereMonth('appointment_time', $month);
        } elseif ($type === 'year') {
            $appointmentsQuery->whereYear('appointment_time', $year);
        } elseif ($type === 'custom' && $startDate && $endDate) {
            $appointmentsQuery->whereBetween('appointment_time', [$startDate, $endDate]);
        }

        $totalAppointments = $appointmentsQuery->count();
        $totalRevenue = $appointmentsQuery->clone()
            ->where('status', 'completed')
            ->with(['service'])
            ->get()
            ->sum(function ($appointment) {
                return $appointment->service ? $appointment->service->price : 0;
            });
        $totalPatients = $appointmentsQuery->clone()->distinct('patient_id')->count('patient_id');
        $todayAppointments = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_time', now()->toDateString())
            ->count();
        $appointments_completed = $appointmentsQuery->clone()->where('status', 'completed')->count();
        $successRate = $totalAppointments > 0 ? round(($appointments_completed / $totalAppointments) * 100, 1) : 0;

        $statLabels = [];
        $statBookings = [];
        $statRevenue = [];

        if ($type === 'month') {
            $daysInMonth = Carbon::create($year, $month)->daysInMonth;
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = Carbon::create($year, $month, $day)->toDateString();
                $statLabels[] = "Ngày $day";
                $statBookings[] = Appointment::where('doctor_id', $doctor->id)
                    ->whereDate('appointment_time', $date)
                    ->count();
                $statRevenue[] = Appointment::where('doctor_id', $doctor->id)
                    ->whereDate('appointment_time', $date)
                    ->where('status', 'completed')
                    ->with(['service'])
                    ->get()
                    ->sum(function ($appointment) {
                        return $appointment->service ? $appointment->service->price : 0;
                    });
            }
        } elseif ($type === 'year') {
            for ($m = 1; $m <= 12; $m++) {
                $statLabels[] = "Tháng $m";
                $statBookings[] = Appointment::where('doctor_id', $doctor->id)
                    ->whereYear('appointment_time', $year)
                    ->whereMonth('appointment_time', $m)
                    ->count();
                $statRevenue[] = Appointment::where('doctor_id', $doctor->id)
                    ->whereYear('appointment_time', $year)
                    ->whereMonth('appointment_time', $m)
                    ->where('status', 'completed')
                    ->with(['service'])
                    ->get()
                    ->sum(function ($appointment) {
                        return $appointment->service ? $appointment->service->price : 0;
                    });
            }
        } elseif ($type === 'custom' && $startDate && $endDate) {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);
            $days = $start->diffInDays($end) + 1;
            for ($i = 0; $i < $days; $i++) {
                $date = $start->copy()->addDays($i)->toDateString();
                $statLabels[] = $date;
                $statBookings[] = Appointment::where('doctor_id', $doctor->id)
                    ->whereDate('appointment_time', $date)
                    ->count();
                $statRevenue[] = Appointment::where('doctor_id', $doctor->id)
                    ->whereDate('appointment_time', $date)
                    ->where('status', 'completed')
                    ->with(['service'])
                    ->get()
                    ->sum(function ($appointment) {
                        return $appointment->service ? $appointment->service->price : 0;
                    });
            }
        }

        $data = compact(
            'doctor',
            'totalAppointments',
            'totalRevenue',
            'totalPatients',
            'todayAppointments',
            'successRate',
            'statLabels',
            'statBookings',
            'statRevenue'
        );

        $pdf = Pdf::loadView('doctor.dashboard.pdf', $data);
        return $pdf->download('doctor_stats_' . $doctorId . '.pdf');
    }
}
