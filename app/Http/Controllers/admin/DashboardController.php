<?php

namespace App\Http\Controllers\Admin;

use App\Exports\StatisticExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Statistic;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Payment;
use App\Models\Role;
use App\Models\Service;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $doctorStats = Doctor::with('user')
            ->withCount([
                'appointments as completedAppointmentsCount' => function ($q) {
                    $q->where('status', 'completed');
                },
                'appointments as totalAppointmentsCount'
            ])
            ->withAvg('reviews as average_rating', 'rating')
            ->orderByDesc('totalAppointmentsCount') // 👈 Sắp xếp giảm dần theo số lịch
            // 👈 Lấy top 5 bác sĩ
            ->get()
            ->map(function ($doctor) {
                return [
                    'name' => $doctor->user->full_name ?? 'Không có tên',
                    'avatar' => $doctor->user->avatar ?? 'default.png',
                    'specialization' => $doctor->specialization ?? 'Chưa cập nhật',
                    'completed_appointments' => $doctor->completedAppointmentsCount ?? 0,
                    'total_appointments' => $doctor->totalAppointmentsCount ?? 0,
                    'average_rating' => number_format($doctor->average_rating ?? 0, 1)
                ];
            });



        $serviceStats = Appointment::join('services', 'appointments.service_id', '=', 'services.id')
            ->select('services.name', DB::raw('COUNT(*) as bookings'))
            ->whereYear('appointment_time', now()->year)
            ->groupBy('services.name')
            ->orderByDesc('bookings')
            ->get();

        $topService = $serviceStats->sortByDesc('bookings')->first();

        $today = Carbon::today();
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        // 1. Thống kê hôm nay
        $patientRole = Role::where('name', 'patient')->first();

        // Thống kê hôm nay
        $dailyStat = (object)[
            'total_revenue' => Appointment::whereDate('appointment_time', $today)
                ->where('appointments.status', 'completed')
                ->join('services', 'appointments.service_id', '=', 'services.id')
                ->sum('services.price'),

            'total_doctors' => Doctor::count(),

            'total_patients' => $patientRole
                ? User::where('role_id', $patientRole->id)->count()
                : 0,

            'total_appointments' => Appointment::whereDate('appointment_time', $today)->count(),

            'appointments_pending' => Appointment::whereDate('appointment_time', $today)
                ->where('appointments.status', 'pending')->count(),

            'appointments_completed' => Appointment::whereDate('appointment_time', $today)
                ->where('appointments.status', 'completed')->count(),

            'appointments_cancelled' => Appointment::whereDate('appointment_time', $today)
                ->where('appointments.status', 'cancelled')->count(),
            'appointments_confirmed' => Appointment::whereDate('appointment_time', $today)
                ->where('appointments.status', 'confirmed')->count(),
        ];

        // Thống kê toàn bộ hệ thống
        $globalStat = (object)[
            'total_revenue' => Payment::where('status', 'paid')->sum('amount'),



            'total_doctors' => Doctor::count(),

            'total_patients' => $patientRole
                ? User::where('role_id', $patientRole->id)->count()
                : 0,

            'total_appointments' => Appointment::count(),

            'appointments_pending' => Appointment::where('status', 'pending')->count(),
            'appointments_confirmed' => Appointment::where('status', 'confirmed')->count(),

            'appointments_completed' => Appointment::where('status', 'completed')->count(),
            'appointments_cancelled' => Appointment::where('status', 'cancelled')->count(),
        ];





        // 2. Thống kê tháng hiện tại & tháng trước
        $monthlyStat = Statistic::where('type', 'monthly')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->first();

        $prevMonth = Carbon::create($year, $month)->subMonth();
        $prevMonthlyStat = Statistic::where('type', 'monthly')
            ->whereMonth('date', $prevMonth->month)
            ->whereYear('date', $prevMonth->year)
            ->first();

        $bookingCurrent = $monthlyStat->total_appointments ?? 0;
        $bookingPrevious = $prevMonthlyStat->total_appointments ?? 0;
        $bookingGrowthValue = $bookingPrevious > 0
            ? round((($bookingCurrent - $bookingPrevious) / $bookingPrevious) * 100)
            : 0;
        $bookingGrowthLabel = 'so với tháng trước';

        // 3. Thống kê năm
        $yearlyStat = Statistic::where('type', 'yearly')
            ->whereYear('date', $year)
            ->first();

        // 4. Biểu đồ 7 ngày gần nhất (line theo ngày)
        $dates = collect(range(0, 6))->map(fn($i) => now()->copy()->subDays($i))->reverse();
        $dailyLabels = [];
        $dailyData = [];

        foreach ($dates as $date) {
            $dailyLabels[] = $date->format('d/m');

            $dailyData[] = Appointment::whereDate('appointment_time', $date)
                ->where('status', 'completed') // ❗ lọc trạng thái nếu cần
                ->count();
        }



        // ✅ 5. Biểu đồ 2a - tính động theo bảng appointments
        $months = range(1, 12);
        $timeLabels = [];
        $timeBookings = [];
        $timeRevenues = [];

        foreach ($months as $m) {
            $timeLabels[] = 'Tháng ' . $m;

            $bookingCount = Appointment::whereYear('appointment_time', $year)
                ->whereMonth('appointment_time', $m)
                ->count();

            $revenue = Appointment::whereYear('appointment_time', $year)
                ->whereMonth('appointment_time', $m)
                ->where('appointments.status', 'completed') // 👈 đây là phần quan trọng
                ->join('services', 'appointments.service_id', '=', 'services.id')
                ->sum('services.price');

            $timeBookings[] = $bookingCount;
            $timeRevenues[] = $revenue;
        }
        $type = $request->query('type', 'month');

        $timeLabels = [];
        $timeBookings = [];
        $timeRevenues = [];

        switch ($type) {
            case 'day':
                $dates = collect(range(0, 6))->map(fn($i) => now()->subDays($i))->reverse();
                foreach ($dates as $date) {
                    $timeLabels[] = $date->format('d/m');
                    $timeBookings[] = Appointment::whereDate('appointment_time', $date)->count();
                    $timeRevenues[] = Appointment::whereDate('appointment_time', $date)
                        ->where('appointments.status', 'completed')
                        ->join('services', 'appointments.service_id', '=', 'services.id')
                        ->sum('services.price');
                }
                break;

            case 'week':
                // 4 tuần gần nhất
                for ($i = 3; $i >= 0; $i--) {
                    $start = now()->copy()->subWeeks($i)->startOfWeek();
                    $end = now()->copy()->subWeeks($i)->endOfWeek();

                    $timeLabels[] = 'Tuần ' . $start->format('d/m') . ' - ' . $end->format('d/m');
                    $timeBookings[] = Appointment::whereBetween('appointment_time', [$start, $end])->count();
                    $timeRevenues[] = Appointment::whereBetween('appointment_time', [$start, $end])
                        ->where('appointments.status', 'completed')
                        ->join('services', 'appointments.service_id', '=', 'services.id')
                        ->sum('services.price');
                }
                break;

            case 'month':
                for ($i = 1; $i <= 12; $i++) {
                    $timeLabels[] = 'Tháng ' . $i;
                    $timeBookings[] = Appointment::whereYear('appointment_time', now()->year)
                        ->whereMonth('appointment_time', $i)
                        ->count();
                    $timeRevenues[] = Appointment::whereYear('appointment_time', now()->year)
                        ->whereMonth('appointment_time', $i)
                        ->where('appointments.status', 'completed')
                        ->join('services', 'appointments.service_id', '=', 'services.id')
                        ->sum('services.price');
                }
                break;

            case 'year':
                $startYear = now()->year - 4;
                for ($i = $startYear; $i <= now()->year; $i++) {
                    $timeLabels[] = 'Năm ' . $i;
                    $timeBookings[] = Appointment::whereYear('appointment_time', $i)->count();
                    $timeRevenues[] = Appointment::whereYear('appointment_time', $i)
                        ->where('appointments.status', 'completed')
                        ->join('services', 'appointments.service_id', '=', 'services.id')
                        ->sum('services.price');
                }
                break;
        }

        $statTable = collect([]);

        foreach ($timeLabels as $i => $label) {
            $statTable->push([
                'label' => $label,
                'bookings' => $timeBookings[$i] ?? 0,
                'revenue' => $timeRevenues[$i] ?? 0,
            ]);
        }



        // 6. Thống kê lịch hẹn hôm nay
        $todayAppointmentsTotal = Appointment::whereDate('appointment_time', $today)->count();
        $todayAppointmentsCompleted = Appointment::whereDate('appointment_time', $today)->where('status', 'completed')->count();
        $todayAppointmentsCancelled = Appointment::whereDate('appointment_time', $today)->where('status', 'cancelled')->count();
        $todayAppointmentsPending = Appointment::whereDate('appointment_time', $today)->where('status', 'pending')->count();
        $todayAppointmentsConfirmed = Appointment::whereDate('appointment_time', $today)->where('status', 'confirmed')->count();

        // Lấy role bệnh nhân
        // Lấy role bệnh nhân
        $patientRole = Role::where('name', 'patient')->first();
        $patientRoleId = $patientRole?->id;

        // Bệnh nhân mới trong tuần
        $newThisWeek = User::where('role_id', $patientRoleId)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        // Tỷ lệ quay lại khám (dựa vào patient_id)
        $returningPatients = Appointment::select('patient_id')
            ->whereNotNull('patient_id')
            ->groupBy('patient_id')
            ->havingRaw('COUNT(*) >= 2')
            ->pluck('patient_id');

        $returnCount = User::whereIn('id', $returningPatients)
            ->where('role_id', $patientRoleId)
            ->count();

        $totalPatients = User::where('role_id', $patientRoleId)->count();

        $returnRate = $totalPatients > 0 ? round(($returnCount / $totalPatients) * 100, 1) : 0;

        // Thống kê theo khu vực (lấy tỉnh/thành từ cuối chuỗi address, nếu có)
        $areaStatsRaw = User::where('role_id', $patientRoleId)
            ->whereNotNull('address')
            ->select(DB::raw("TRIM(SUBSTRING_INDEX(address, ',', -1)) as region"), DB::raw('COUNT(*) as total'))
            ->groupBy('region')
            ->pluck('total', 'region')
            ->toArray();

        // Tổng hợp tất cả
        $patientStats = [
            'new_this_week' => $newThisWeek,
            'return_rate' => $returnRate,
            'area' => $areaStatsRaw
        ];
        $patientStatType = $request->query('patient_type', 'week'); // mặc định là tuần
        $patientStatLabels = [];
        $patientStatData = [];

        switch ($patientStatType) {

            case 'week':
                $start = now()->startOfWeek();
                $end = now()->endOfWeek();

                $patientStatLabels[] = 'Tuần ' . $start->format('d/m') . ' - ' . $end->format('d/m');

                $count = User::where('role_id', $patientRoleId)
                    ->whereBetween('created_at', [$start, $end])
                    ->count();
                $patientStatData[] = $count;
                break;

            case 'month':
                $label = 'Tháng ' . now()->month;
                $patientStatLabels[] = $label;

                $count = User::where('role_id', $patientRoleId)
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count();
                $patientStatData[] = $count;
                break;
        }


        $appointments = Appointment::whereYear('appointment_time', now()->year)->get();
        $totalAppointments = $appointments->count();

        // ✅ Tỷ lệ hủy lịch
        $cancelRate = $totalAppointments > 0
            ? round($appointments->where('status', 'cancelled')->count() / $totalAppointments * 100, 1)
            : 0;

        // ✅ Tỷ lệ khám đúng hẹn
        $appointments = Appointment::where('status', 'completed')
            ->whereNotNull('check_in_time')
            ->where('appointment_time', '<=', now()) // ❗ chỉ tính quá khứ hoặc hiện tại
            ->get();

        $total = $appointments->count();

        // Cho phép đến muộn tối đa 5 phút vẫn tính đúng hẹn (tuỳ chọn)
        $onTimeAppointments = $appointments->filter(function ($a) {
            return Carbon::parse($a->check_in_time)
                ->diffInMinutes(Carbon::parse($a->appointment_time), false) <= 5;
        });

        $onTimeRate = $total > 0
            ? round($onTimeAppointments->count() / $total * 100, 1)
            : 0;
        // ✅ Thời gian chờ trung bình (phút)
        $waitingTimes = $appointments->filter(fn($a) => $a->check_in_time)
            ->map(
                fn($a) =>
                abs(Carbon::parse($a->check_in_time)->diffInMinutes(Carbon::parse($a->appointment_time)))
            );

        $avgWaiting = $waitingTimes->count() > 0
            ? round($waitingTimes->avg(), 1)
            : null;


        // ✅ Gán vào mảng xuất ra view
        $performanceStats = [
            'cancel_rate' => $cancelRate,
            'on_time_rate' => $onTimeRate,
            'avg_waiting_time' => $avgWaiting,
        ];





        return view('admin.dashboard.index', [
            'dailyStat' => $dailyStat,
            'globalStat' => $globalStat,
            'monthlyStat' => $monthlyStat,
            'yearlyStat' => $yearlyStat,
            'selectedMonth' => $month,
            'selectedYear' => $year,
            // 'chartLabels' => $chartLabels,
            // 'chartData' => $chartData,
            'dailyLabels' => $dailyLabels,
            'dailyData' => $dailyData,
            'timeLabels' => $timeLabels,
            'timeBookings' => $timeBookings,
            'timeRevenues' => $timeRevenues,
            'bookingGrowthValue' => $bookingGrowthValue,
            'bookingGrowthLabel' => $bookingGrowthLabel,
            'todayAppointmentsTotal' => $todayAppointmentsTotal,
            'todayAppointmentsCompleted' => $todayAppointmentsCompleted,
            'todayAppointmentsCancelled' => $todayAppointmentsCancelled,
            'todayAppointmentsPending' => $todayAppointmentsPending,
            'todayAppointmentsConfirmed' => $todayAppointmentsConfirmed,

            'serviceStats' => $serviceStats,
            'topService' => $topService,
            'doctorStats' => $doctorStats,
            'selectedType' => $type,
            'statTable' => $statTable,
            'patientStats' => $patientStats,
            'patientStatLabels' => $patientStatLabels,
            'patientStatData' => $patientStatData,
            'patientStatType' => $patientStatType,
            'performanceStats' => $performanceStats

        ]);
    }

    public function export(Request $request)
    {
        $type = $request->input('type', 'excel');

        $statistics = Statistic::where('type', 'monthly')
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->get();

        if ($type === 'pdf') {
            $pdf = Pdf::loadView('exports.monthly', ['statistics' => $statistics]);
            return $pdf->download('bao_cao_thang.pdf');
        }

        return Excel::download(new StatisticExport($statistics), 'bao_cao_thang.xlsx');
    }
}
