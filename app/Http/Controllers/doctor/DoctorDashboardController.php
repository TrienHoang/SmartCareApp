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
use App\Models\DoctorLeave;

class DoctorDashboardController extends Controller
{
    public function index(Request $request)
    {
        $doctor = Doctor::where('user_id', Auth::id())->firstOrFail();
        $now = Carbon::now();

        $appointments = Appointment::where('doctor_id', $doctor->id);
        $totalAppointments = $appointments->count();
        $todayAppointments = (clone $appointments)->whereDate('appointment_time', $now)->count();
        $weekAppointments = (clone $appointments)->whereBetween('appointment_time', [$now->startOfWeek(), $now->endOfWeek()])->count();
        $monthAppointments = (clone $appointments)->whereMonth('appointment_time', $now->month)->whereYear('appointment_time', $now->year)->count();

        $completed = (clone $appointments)->where('status', 'completed')->count();
        $cancelled = (clone $appointments)->where('status', 'cancelled')->count();
        $successRate = $totalAppointments ? round($completed / $totalAppointments * 100, 1) : 0;
        $cancelRate = $totalAppointments ? round($cancelled / $totalAppointments * 100, 1) : 0;

        $successAppointments = $completed;
        $cancelAppointments = $cancelled;

        $totalPatients = Appointment::where('doctor_id', $doctor->id)->distinct('patient_id')->count('patient_id');
        $totalRevenue = (clone $appointments)
            ->where('appointments.status', 'completed')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->sum('services.price');

        $appointments_pending = (clone $appointments)->whereDate('appointment_time', $now)->where('status', 'pending')->count();
        $appointments_confirmed = (clone $appointments)->whereDate('appointment_time', $now)->where('status', 'confirmed')->count();
        $appointments_completed = (clone $appointments)->whereDate('appointment_time', $now)->where('status', 'completed')->count();
        $appointments_cancelled = (clone $appointments)->whereDate('appointment_time', $now)->where('status', 'cancelled')->count();

        $totalPrescriptions = Prescription::where('doctor_id', $doctor->id)->count();
        $todayPrescriptions = Prescription::where('doctor_id', $doctor->id)->whereDate('created_at', $now)->count();

        $topPrescribed = Prescription::join('prescription_items', 'prescriptions.id', '=', 'prescription_items.prescription_id')
            ->join('medicines', 'prescription_items.medicine_id', '=', 'medicines.id')
            ->where('prescriptions.doctor_id', $doctor->id)
            ->whereNull('prescriptions.deleted_at')
            ->selectRaw('medicines.name as medicine_name, COUNT(*) as total')
            ->groupBy('medicines.name')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        $leaveRequests = DoctorLeave::where('doctor_id', $doctor->id);
        $totalLeaves = $leaveRequests->count();
        $approvedLeaves = (clone $leaveRequests)->where('approved', true)->count();

        $totalHoursWorked = WorkingSchedule::where('doctor_id', $doctor->id)->count();

        // Thống kê biểu đồ
        $type = $request->get('type', 'month');
        $year = (int) $request->get('year', now()->year);
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Validate inputs
        $request->validate([
            'type' => 'in:month,year,custom',
            'year' => 'nullable|integer|min:2000|max:' . now()->year,
            'start_date' => 'nullable|date|before_or_equal:end_date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $statLabels = [];
        $statBookings = [];
        $statRevenue = [];

        // If specific date range is provided, use it for all filter types
        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);
            // Ensure dates are within selected year
            if ($start->year == $year && $end->year == $year) {
                // Check if date range exceeds 62 days
                $diffDays = $start->diffInDays($end);
                if ($diffDays > 62) {
                    return redirect()->back()->withErrors(['end_date' => 'Khoảng thời gian tối đa là 62 ngày.']);
                }
                while ($start <= $end) {
                    $label = $start->format('d/m/Y');
                    $statLabels[] = $label;
                    $statBookings[] = Appointment::where('doctor_id', $doctor->id)
                        ->whereDate('appointment_time', $start)
                        ->count();
                    $statRevenue[] = Appointment::where('doctor_id', $doctor->id)
                        ->whereDate('appointment_time', $start)
                        ->where('appointments.status', 'completed')
                        ->join('services', 'appointments.service_id', '=', 'services.id')
                        ->sum('services.price');
                    $start->addDay();
                }
            } else {
                return redirect()->back()->withErrors(['start_date' => 'Ngày phải nằm trong năm đã chọn.']);
            }
        } else {
            // No specific date range provided, use default ranges
            if ($type === 'month') {
                // Default to current month of selected year
                $start = Carbon::create($year, now()->month, 1)->startOfMonth();
                $end = $start->copy()->endOfMonth();
                while ($start <= $end) {
                    $label = $start->format('d/m/Y');
                    $statLabels[] = $label;
                    $statBookings[] = Appointment::where('doctor_id', $doctor->id)
                        ->whereDate('appointment_time', $start)
                        ->count();
                    $statRevenue[] = Appointment::where('doctor_id', $doctor->id)
                        ->whereDate('appointment_time', $start)
                        ->where('appointments.status', 'completed')
                        ->join('services', 'appointments.service_id', '=', 'services.id')
                        ->sum('services.price');
                    $start->addDay();
                }
            } elseif ($type === 'year') {
                // Default to entire year
                for ($i = 1; $i <= 12; $i++) {
                    $statLabels[] = 'Tháng ' . $i;
                    $start = Carbon::create($year, $i, 1)->startOfMonth();
                    $end = $start->copy()->endOfMonth();
                    $statBookings[] = Appointment::where('doctor_id', $doctor->id)
                        ->whereBetween('appointment_time', [$start, $end])
                        ->count();
                    $statRevenue[] = Appointment::where('doctor_id', $doctor->id)
                        ->whereBetween('appointment_time', [$start, $end])
                        ->where('appointments.status', 'completed')
                        ->join('services', 'appointments.service_id', '=', 'services.id')
                        ->sum('services.price');
                }
            } elseif ($type === 'custom') {
                // For custom without dates, fall back to entire year
                for ($i = 1; $i <= 12; $i++) {
                    $statLabels[] = 'Tháng ' . $i;
                    $start = Carbon::create($year, $i, 1)->startOfMonth();
                    $end = $start->copy()->endOfMonth();
                    $statBookings[] = Appointment::where('doctor_id', $doctor->id)
                        ->whereBetween('appointment_time', [$start, $end])
                        ->count();
                    $statRevenue[] = Appointment::where('doctor_id', $doctor->id)
                        ->whereBetween('appointment_time', [$start, $end])
                        ->where('appointments.status', 'completed')
                        ->join('services', 'appointments.service_id', '=', 'services.id')
                        ->sum('services.price');
                }
            }
        }

        // Tăng trưởng
        $bookingGrowth = $monthAppointments - $weekAppointments;
        $growthValue = $weekAppointments ? round($bookingGrowth / $weekAppointments * 100, 1) : 0;
        $growthLabel = 'So với tuần trước';

        $revenueGrowth = $totalRevenue ? round(($totalRevenue - ($totalRevenue * 0.8)) / ($totalRevenue * 0.8) * 100, 1) : 0;
        $revenueLabel = 'So với tháng trước';

        return view('doctor.dashboard.index', [
            'doctor' => $doctor,
            'totalAppointments' => $totalAppointments,
            'todayAppointments' => $todayAppointments,
            'weekAppointments' => $weekAppointments,
            'monthAppointments' => $monthAppointments,
            'successRate' => $successRate,
            'cancelRate' => $cancelRate,
            'successAppointments' => $successAppointments ?? 0,
            'cancelAppointments' => $cancelAppointments ?? 0,
            'totalPatients' => $totalPatients,
            'totalRevenue' => $totalRevenue,
            'appointments_pending' => $appointments_pending,
            'appointments_confirmed' => $appointments_confirmed,
            'appointments_completed' => $appointments_completed,
            'appointments_cancelled' => $appointments_cancelled,
            'totalPrescriptions' => $totalPrescriptions,
            'todayPrescriptions' => $todayPrescriptions,
            'topPrescribed' => $topPrescribed,
            'totalLeaves' => $totalLeaves,
            'approvedLeaves' => $approvedLeaves,
            'totalHoursWorked' => $totalHoursWorked,
            'type' => $type,
            'year' => $year,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'statLabels' => $statLabels,
            'statBookings' => $statBookings,
            'statRevenue' => $statRevenue,
            'growthValue' => $growthValue,
            'growthLabel' => $growthLabel,
            'revenueGrowth' => $revenueGrowth,
            'revenueLabel' => $revenueLabel,
        ]);
    }

    public function exportExcel(Request $request, $doctorId)
    {
        $doctor = Doctor::findOrFail($doctorId);
        return Excel::download(new DoctorStatsExport($doctor, $request->all()), 'doctor_stats_' . $doctorId . '.xlsx');
    }

    public function exportPdf(Request $request, $doctorId)
    {
        try {
            $doctor = Doctor::findOrFail($doctorId);
            $now = Carbon::now();

            // Lấy input filter
            $type = $request->input('type', 'month');
            $year = $request->input('year'); // KHÔNG ép kiểu int vì có thể null
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            // Validate: year là optional
            $request->validate([
                'type' => 'in:month,year,custom',
                'year' => 'nullable|integer|min:2000|max:' . $now->year,
                'start_date' => 'nullable|date|before_or_equal:end_date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
            ]);

            $appointmentsQuery = Appointment::with('service')
                ->where('doctor_id', $doctor->id)
                ->select('id', 'patient_id', 'doctor_id', 'service_id', 'appointment_time', 'status');

            // Áp dụng filter thời gian
            if ($startDate && $endDate) {
                $start = Carbon::parse($startDate);
                $end = Carbon::parse($endDate);

                // Nếu có year thì mới kiểm tra năm
                if ($year && ($start->year != $year || $end->year != $year)) {
                    return redirect()->back()->withErrors(['start_date' => 'Ngày phải nằm trong năm đã chọn.']);
                }

                // Giới hạn 62 ngày
                if ($start->diffInDays($end) > 62) {
                    return redirect()->back()->withErrors(['end_date' => 'Khoảng thời gian tối đa là 62 ngày.']);
                }

                $appointmentsQuery->whereBetween('appointment_time', [$start, $end]);
            } else {
                // Không có custom date → tự động dùng theo tháng/năm
                if ($type === 'month') {
                    $month = $now->month;
                    $usedYear = $year ?? $now->year;
                    $start = Carbon::create($usedYear, $month, 1)->startOfMonth();
                    $end = $start->copy()->endOfMonth();
                } else {
                    $usedYear = $year ?? $now->year;
                    $start = Carbon::create($usedYear, 1, 1)->startOfYear();
                    $end = $start->copy()->endOfYear();
                }
                $appointmentsQuery->whereBetween('appointment_time', [$start, $end]);
            }

            // Lấy dữ liệu
            $appointments = $appointmentsQuery->get();

            // Tính toán thống kê
            $totalAppointments = $appointments->count();
            $totalRevenue = $appointments->where('status', 'completed')->sum(fn($a) => $a->service->price ?? 0);
            $totalPatients = $appointments->unique('patient_id')->count();

            $todayAppointments = Appointment::where('doctor_id', $doctor->id)
                ->whereDate('appointment_time', $now->toDateString())->count();

            $weekAppointments = Appointment::where('doctor_id', $doctor->id)
                ->whereBetween('appointment_time', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()])->count();

            $monthAppointments = Appointment::where('doctor_id', $doctor->id)
                ->whereMonth('appointment_time', $now->month)
                ->whereYear('appointment_time', $now->year)->count();

            $successAppointments = $appointments->where('status', 'completed')->count();
            $cancelAppointments = $appointments->where('status', 'cancelled')->count();

            $successRate = $totalAppointments > 0 ? round($successAppointments / $totalAppointments * 100, 1) : 0;
            $cancelRate = $totalAppointments > 0 ? round($cancelAppointments / $totalAppointments * 100, 1) : 0;

            $appointments_pending = $appointments->where('status', 'pending')->count();
            $appointments_confirmed = $appointments->where('status', 'confirmed')->count();
            $appointments_completed = $appointments->where('status', 'completed')->count();
            $appointments_cancelled = $appointments->where('status', 'cancelled')->count();

            $totalPrescriptions = Prescription::where('doctor_id', $doctor->id)->count();
            $todayPrescriptions = Prescription::where('doctor_id', $doctor->id)
                ->whereDate('created_at', $now)->count();

            $topPrescribed = Prescription::join('prescription_items', 'prescriptions.id', '=', 'prescription_items.prescription_id')
                ->join('medicines', 'prescription_items.medicine_id', '=', 'medicines.id')
                ->where('prescriptions.doctor_id', $doctor->id)
                ->selectRaw('medicines.name as medicine_name, COUNT(*) as total')
                ->groupBy('medicines.name')
                ->orderByDesc('total')
                ->take(5)
                ->get();

            $leaveRequests = DoctorLeave::where('doctor_id', $doctor->id);
            $totalLeaves = $leaveRequests->count();
            $approvedLeaves = $leaveRequests->clone()->where('approved', true)->count();
            $totalHoursWorked = WorkingSchedule::where('doctor_id', $doctor->id)->count();

            // Tạo dữ liệu biểu đồ
            $statLabels = [];
            $statBookings = [];
            $statRevenue = [];

            $periodStart = $start ?? $appointments->min('appointment_time') ?? now();
            $periodEnd = $end ?? $appointments->max('appointment_time') ?? now();

            if ($type === 'month' || ($startDate && $endDate)) {
                $current = $periodStart->copy();
                while ($current <= $periodEnd) {
                    $dateLabel = $current->format('d/m/Y');
                    $statLabels[] = $dateLabel;
                    $statBookings[] = $appointments->filter(fn($a) => $a->appointment_time->format('Y-m-d') === $current->format('Y-m-d'))->count();
                    $statRevenue[] = $appointments->filter(fn($a) =>
                    $a->appointment_time->format('Y-m-d') === $current->format('Y-m-d') &&
                        $a->status === 'completed')->sum(fn($a) => $a->service->price ?? 0);
                    $current->addDay();
                }
            } else {
                for ($m = 1; $m <= 12; $m++) {
                    $label = 'Tháng ' . $m;
                    $monthStart = Carbon::create($usedYear, $m, 1)->startOfMonth();
                    $monthEnd = $monthStart->copy()->endOfMonth();

                    $statLabels[] = $label;
                    $statBookings[] = $appointments->whereBetween('appointment_time', [$monthStart, $monthEnd])->count();
                    $statRevenue[] = $appointments->whereBetween('appointment_time', [$monthStart, $monthEnd])
                        ->where('status', 'completed')->sum(fn($a) => $a->service->price ?? 0);
                }
            }

            // Tăng trưởng
            $bookingGrowth = $monthAppointments - $weekAppointments;
            $growthValue = $weekAppointments > 0 ? round($bookingGrowth / $weekAppointments * 100, 1) : 0;
            $growthLabel = 'So với tuần trước';

            $revenueGrowth = $totalRevenue > 0 ? round(($totalRevenue - $totalRevenue * 0.8) / ($totalRevenue * 0.8) * 100, 1) : 0;
            $revenueLabel = 'So với tháng trước';

            $data = compact(
                'doctor',
                'totalAppointments',
                'todayAppointments',
                'weekAppointments',
                'monthAppointments',
                'successRate',
                'cancelRate',
                'successAppointments',
                'cancelAppointments',
                'totalPatients',
                'totalRevenue',
                'appointments_pending',
                'appointments_confirmed',
                'appointments_completed',
                'appointments_cancelled',
                'totalPrescriptions',
                'todayPrescriptions',
                'topPrescribed',
                'totalLeaves',
                'approvedLeaves',
                'totalHoursWorked',
                'type',
                'year',
                'startDate',
                'endDate',
                'statLabels',
                'statBookings',
                'statRevenue',
                'growthValue',
                'growthLabel',
                'revenueGrowth',
                'revenueLabel'
            );

            return Pdf::loadView('doctor.dashboard.stats-pdf', $data)
                ->download("doctor_stats_{$doctorId}.pdf");
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Lỗi khi tạo PDF: ' . $e->getMessage()]);
        }
    }
}
