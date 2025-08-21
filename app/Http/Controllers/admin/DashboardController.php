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
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Barryvdh\DomPDF\Facade\Pdf;


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
            ->orderByDesc('totalAppointmentsCount')
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
            ->limit(10)   // 👉 chỉ lấy 10 dịch vụ có số bookings nhiều nhất
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
                ->whereIn('appointments.status', ['completed', 'pending', 'confirmed'])
                ->join('payments', 'appointments.id', '=', 'payments.appointment_id')
                ->sum('payments.amount'),


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

            'total_revenue' => Payment::where('status', 'paid')
                ->where(function ($q) {
                    $q->where('refund_status', 'none')
                        ->orWhere('refund_status', 'failed')
                        ->orWhereNull('refund_status');
                })
                ->sum('amount'),
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

        // Lấy tháng/năm từ request, mặc định = hiện tại
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        // Tháng trước
        $prevMonth = Carbon::create($year, $month, 1)->subMonth();

        // ===============================
        // 📊 1. Lượt đặt lịch
        // ===============================
        $bookingCurrent = Appointment::whereMonth('appointment_time', $month)
            ->whereYear('appointment_time', $year)
            ->where('status', '!=', 'cancelled')
            ->count();

        $bookingPrevious = Appointment::whereMonth('appointment_time', $prevMonth->month)
            ->whereYear('appointment_time', $prevMonth->year)
            ->where('status', '!=', 'cancelled')
            ->count();

        if ($bookingPrevious > 0) {
            $bookingGrowthValue = round((($bookingCurrent - $bookingPrevious) / $bookingPrevious) * 100);
        } else {
            $bookingGrowthValue = $bookingCurrent > 0 ? 100 : 0;
        }
        $bookingGrowthLabel = "So với {$prevMonth->month}/{$prevMonth->year}";

        // ===============================
        // 💰 2. Doanh thu
        // ===============================


        $revenueCurrent = Payment::whereMonth('paid_at', $month)
            ->whereYear('paid_at', $year)
            ->where('status', 'paid')
            ->where('refund_status', 'none')
            ->sum('amount');

        $revenuePrevious = Payment::whereMonth('paid_at', $prevMonth->month)
            ->whereYear('paid_at', $prevMonth->year)
            ->where('status', 'paid')
            ->where('refund_status', 'none')
            ->sum('amount');

        $revenueGrowthValue = $revenuePrevious > 0
            ? round((($revenueCurrent - $revenuePrevious) / $revenuePrevious) * 100)
            : ($revenueCurrent > 0 ? 100 : 0);

        $revenueGrowthLabel = "So với {$prevMonth->month}/{$prevMonth->year}";




        // Doanh thu
        // $revenueCurrent = $monthlyStat->total_revenue ?? 0;
        // $revenuePrevious = $prevMonthlyStat->total_revenue ?? 0;
        // $revenueGrowthValue = $revenuePrevious > 0
        //     ? round((($revenueCurrent - $revenuePrevious) / $revenuePrevious) * 100)
        //     : 0;
        // $revenueGrowthLabel = "So với {$prevMonth->month}/{$prevMonth->year}";


        // 3. Thống kê năm
        $yearlyStat = Statistic::where('type', 'yearly')
            ->whereYear('date', $year)
            ->first();

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($request->query('type') === 'custom' && $startDate && $endDate) {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);

            // Kiểm tra: nếu start sau end hoặc cách nhau quá 2 tháng (~62 ngày)
            if ($start->gt($end)) {
                return back()->with('error', 'Ngày bắt đầu không được sau ngày kết thúc.');
            }

            if ($start->diffInDays($end) > 62) {
                return back()->with('error', 'Vui lòng chọn khoảng thời gian không vượt quá 2 tháng.');
            }
        }

        // 4. Biểu đồ 7 ngày gần nhất (line theo ngày)
        $dates = collect(range(0, 6))->map(fn($i) => now()->copy()->subDays($i))->reverse();
        $dailyLabels = [];
        $dailyData = [];

        foreach ($dates as $date) {
            $dailyLabels[] = $date->format('d/m');
            $dailyData[] = Appointment::whereDate('appointment_time', $date)
                ->whereIn('status', ['completed', 'pending', 'confirmed'])
                ->count();
        }

        // ✅ 5. Biểu đồ với bộ lọc ngày tùy chọn
        $type = $request->query('type', 'month');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $timeLabels = [];
        $timeBookings = [];
        $timeRevenues = [];

        // Nếu có custom date range
        if ($type === 'custom' && $startDate && $endDate) {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);

            // Tính số ngày giữa start và end
            $daysDiff = $start->diffInDays($end);

            if ($daysDiff <= 31) {
                // Nếu <= 31 ngày thì hiển thị theo ngày
                $currentDate = $start->copy();
                while ($currentDate <= $end) {
                    $timeLabels[] = $currentDate->format('d/m');

                    $timeBookings[] = Appointment::whereDate('appointment_time', $currentDate)
                        ->whereIn('status', ['completed', 'pending', 'confirmed'])
                        ->count();


                    $timeRevenues[] = Appointment::whereDate('appointment_time', $currentDate)
                        ->whereIn('appointments.status', ['completed', 'pending', 'confirmed'])
                        ->join('payments', 'appointments.id', '=', 'payments.appointment_id')
                        ->sum('payments.amount');


                    $currentDate->addDay();
                }
            } else {
                // Nếu > 31 ngày thì nhóm theo tuần
                $currentStart = $start->copy()->startOfWeek();
                while ($currentStart <= $end) {
                    $weekEnd = $currentStart->copy()->endOfWeek();
                    if ($weekEnd > $end) {
                        $weekEnd = $end->copy();
                    }

                    $timeLabels[] = $currentStart->format('d/m') . ' - ' . $weekEnd->format('d/m');

                    $timeBookings[] = Appointment::whereBetween('appointment_time', [$currentStart, $weekEnd])
                        ->whereIn('status', ['completed', 'pending', 'confirmed'])
                        ->count();


                    $timeRevenues[] = Appointment::whereBetween('appointment_time', [$currentStart, $weekEnd])
                        ->whereIn('appointments.status', ['completed', 'pending', 'confirmed'])
                        ->join('payments', 'appointments.id', '=', 'payments.appointment_id')
                        ->sum('payments.amount');


                    $currentStart->addWeek();
                }
            }
        } else {
            // Logic cũ cho các filter type khác
            switch ($type) {
                case 'day':
                    $dates = collect(range(0, 6))->map(fn($i) => now()->subDays($i))->reverse();
                    foreach ($dates as $date) {
                        $timeLabels[] = $date->format('d/m');
                        $timeBookings[] = Appointment::whereDate('appointment_time', $date)
                            ->whereIn('status', ['completed', 'pending', 'confirmed'])
                            ->count();

                        $timeRevenues[] = Appointment::whereDate('appointment_time', $date)
                            ->whereIn('appointments.status', ['completed', 'pending', 'confirmed'])
                            ->join('payments', 'appointments.id', '=', 'payments.appointment_id')
                            ->sum('payments.amount');
                    }
                    break;

                case 'week':
                    for ($i = 3; $i >= 0; $i--) {
                        $start = now()->copy()->subWeeks($i)->startOfWeek();
                        $end = now()->copy()->subWeeks($i)->endOfWeek();

                        $timeLabels[] = 'Tuần ' . $start->format('d/m') . ' - ' . $end->format('d/m');
                        $timeBookings[] = Appointment::whereBetween('appointment_time', [$start, $end])
                            ->whereIn('status', ['completed', 'pending', 'confirmed'])
                            ->count();

                        $timeRevenues[] = Appointment::whereBetween('appointment_time', [$start, $end])
                            ->whereIn('appointments.status', ['completed', 'pending', 'confirmed'])
                            ->join('payments', 'appointments.id', '=', 'payments.appointment_id')
                            ->sum('payments.amount');
                    }
                    break;

                case 'month':
                    for ($i = 1; $i <= 12; $i++) {
                        $timeLabels[] = 'Tháng ' . $i;
                        $timeBookings[] = Appointment::whereYear('appointment_time', now()->year)
                            ->whereMonth('appointment_time', $i)
                            ->whereIn('status', ['completed', 'pending', 'confirmed'])
                            ->count();

                        $timeRevenues[] = Appointment::whereYear('appointment_time', now()->year)
                            ->whereMonth('appointment_time', $i)
                            ->whereIn('appointments.status', ['completed', 'pending', 'confirmed'])
                            ->join('payments', 'appointments.id', '=', 'payments.appointment_id')
                            ->sum('payments.amount');
                    }
                    break;

                case 'year':
                    $startYear = now()->year - 4;
                    for ($i = $startYear; $i <= now()->year; $i++) {
                        $timeLabels[] = 'Năm ' . $i;
                        $timeBookings[] = Appointment::whereYear('appointment_time', $i)
                            ->whereIn('status', ['completed', 'pending', 'confirmed'])
                            ->count();

                        $timeRevenues[] = Appointment::whereYear('appointment_time', $i)
                            ->whereIn('appointments.status', ['completed', 'pending', 'confirmed'])
                            ->join('payments', 'appointments.id', '=', 'payments.appointment_id')
                            ->sum('payments.amount');
                    }
                    break;
            }
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

        // Thống kê bệnh nhân
        $patientRole = Role::where('name', 'nurse')->first();
        $patientRoleId = $patientRole?->id;

        $newThisWeek = User::where('role_id', $patientRoleId)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

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

        $areaStatsRaw = User::where('role_id', $patientRoleId)
            ->whereNotNull('address')
            ->select(DB::raw("TRIM(SUBSTRING_INDEX(address, ',', -1)) as region"), DB::raw('COUNT(*) as total'))
            ->groupBy('region')
            ->pluck('total', 'region')
            ->toArray();

        $patientStats = [
            'new_this_week' => $newThisWeek,
            'return_rate' => $returnRate,
            'area' => $areaStatsRaw
        ];

        $patientStatType = $request->query('patient_type', 'week');
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

        // Thống kê hiệu suất
        $appointments = Appointment::whereYear('appointment_time', now()->year)->get();
        $totalAppointments = $appointments->count();

        $cancelRate = $totalAppointments > 0
            ? round($appointments->where('status', 'cancelled')->count() / $totalAppointments * 100, 1)
            : 0;
        $completedRate = $totalAppointments > 0
            ? round($appointments->where('status', 'completed')->count() / $totalAppointments * 100, 1)
            : 0;

        $appointments = Appointment::where('status', 'completed')
            ->whereNotNull('check_in_time')
            ->where('appointment_time', '<=', now())
            ->get();

        $total = $appointments->count();

        $onTimeAppointments = $appointments->filter(function ($a) {
            return Carbon::parse($a->check_in_time)
                ->diffInMinutes(Carbon::parse($a->appointment_time), false) <= 5;
        });

        $onTimeRate = $total > 0
            ? round($onTimeAppointments->count() / $total * 100, 1)
            : 0;

        $waitingTimes = $appointments->filter(fn($a) => $a->check_in_time)
            ->map(
                fn($a) =>
                abs(Carbon::parse($a->check_in_time)->diffInMinutes(Carbon::parse($a->appointment_time)))
            );

        $avgWaiting = $waitingTimes->count() > 0
            ? round($waitingTimes->avg(), 1)
            : null;

        $performanceStats = [
            'cancel_rate' => $cancelRate,
            'completed_rate' => $completedRate,
            'avg_waiting_time' => $avgWaiting,
        ];

        return view('admin.dashboard.index', [
            'dailyStat' => $dailyStat,
            'globalStat' => $globalStat,
            'yearlyStat' => $yearlyStat,
            'selectedMonth' => $month,
            'selectedYear' => $year,
            'dailyLabels' => $dailyLabels,
            'dailyData' => $dailyData,
            'timeLabels' => $timeLabels,
            'timeBookings' => $timeBookings,
            'timeRevenues' => $timeRevenues,
            'month' => $month,
            'year' => $year,
            'bookingCurrent' => $bookingCurrent,
            'bookingPrevious' => $bookingPrevious,
            'bookingGrowthValue' => $bookingGrowthValue,
            'bookingGrowthLabel' => $bookingGrowthLabel,
            'revenueCurrent' => $revenueCurrent,
            'revenuePrevious' => $revenuePrevious,
            'revenueGrowthValue' => $revenueGrowthValue,
            'revenueGrowthLabel' => $revenueGrowthLabel,
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
            'performanceStats' => $performanceStats,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    public function exportExcel(Request $request)
    {
        $data = $this->getDashboardData($request);

        $spreadsheet = new Spreadsheet();

        // Sheet 1: Tổng quan
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Tổng quan');

        $headerStyle = [
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ];
        $subHeaderStyle = [
            'font' => ['bold' => true, 'size' => 12],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E7E6E6']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ];
        $cellStyle = [
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ];

        $sheet->setCellValue('A1', 'BÁO CÁO DASHBOARD BỆNH VIỆN');
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        $sheet->setCellValue('A2', 'Ngày xuất: ' . Carbon::now()->format('d/m/Y H:i:s'));
        $sheet->mergeCells('A2:F2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $row = 4;
        $sheet->setCellValue("A{$row}", 'THỐNG KÊ HÔM NAY');
        $sheet->mergeCells("A{$row}:F{$row}");
        $sheet->getStyle("A{$row}")->applyFromArray($headerStyle);
        $row++;

        $todayStats = [
            ['Chỉ số', 'Giá trị'],
            ['Tổng doanh thu', number_format($data['dailyStat']->total_revenue) . ' VND'],
            ['Tổng bác sĩ', $data['dailyStat']->total_doctors],
            ['Tổng bệnh nhân', $data['dailyStat']->total_patients],
            ['Tổng lịch hẹn', $data['dailyStat']->total_appointments],
            ['Lịch hẹn chờ xử lý', $data['dailyStat']->appointments_pending],
            ['Lịch hẹn đã xác nhận', $data['dailyStat']->appointments_confirmed],
            ['Lịch hẹn hoàn thành', $data['dailyStat']->appointments_completed],
            ['Lịch hẹn đã hủy', $data['dailyStat']->appointments_cancelled]
        ];

        foreach ($todayStats as $index => $stat) {
            $sheet->setCellValue("A{$row}", $stat[0]);
            $sheet->setCellValue("B{$row}", $stat[1]);
            $style = $index == 0 ? $subHeaderStyle : $cellStyle;
            $sheet->getStyle("A{$row}:B{$row}")->applyFromArray($style);
            $row++;
        }

        $row += 2;
        $sheet->setCellValue("A{$row}", 'THỐNG KÊ TOÀN HỆ THỐNG');
        $sheet->mergeCells("A{$row}:F{$row}");
        $sheet->getStyle("A{$row}")->applyFromArray($headerStyle);
        $row++;

        $globalStats = [
            ['Chỉ số', 'Giá trị'],
            ['Tổng doanh thu', number_format($data['globalStat']->total_revenue) . ' VND'],
            ['Tổng bác sĩ', $data['globalStat']->total_doctors],
            ['Tổng bệnh nhân', $data['globalStat']->total_patients],
            ['Tổng lịch hẹn', $data['globalStat']->total_appointments],
            ['Lịch hẹn chờ xử lý', $data['globalStat']->appointments_pending],
            ['Lịch hẹn đã xác nhận', $data['globalStat']->appointments_confirmed],
            ['Lịch hẹn hoàn thành', $data['globalStat']->appointments_completed],
            ['Lịch hẹn đã hủy', $data['globalStat']->appointments_cancelled]
        ];

        foreach ($globalStats as $index => $stat) {
            $sheet->setCellValue("A{$row}", $stat[0]);
            $sheet->setCellValue("B{$row}", $stat[1]);
            $style = $index == 0 ? $subHeaderStyle : $cellStyle;
            $sheet->getStyle("A{$row}:B{$row}")->applyFromArray($style);
            $row++;
        }
        // Sheet: Thống kê lịch hẹn toàn hệ thống
        $appointmentSheet = $spreadsheet->createSheet();
        $appointmentSheet->setTitle('Lịch hẹn tổng');

        $appointmentSheet->setCellValue('A1', 'THỐNG KÊ LỊCH HẸN TOÀN HỆ THỐNG');
        $appointmentSheet->mergeCells('A1:E1');
        $appointmentSheet->getStyle('A1')->applyFromArray($headerStyle);

        $appointmentSheet->setCellValue('A3', 'Trạng thái');
        $appointmentSheet->setCellValue('B3', 'Số lượng');
        $appointmentSheet->getStyle('A3:B3')->applyFromArray($subHeaderStyle);

        $row = 4;
        $appointmentSheet->setCellValue("A{$row}", 'Tổng lịch hẹn');
        $appointmentSheet->setCellValue("B{$row}", $data['globalStat']->total_appointments);
        $row++;

        $appointmentSheet->setCellValue("A{$row}", 'Chờ xử lý');
        $appointmentSheet->setCellValue("B{$row}", $data['globalStat']->appointments_pending);
        $row++;

        $appointmentSheet->setCellValue("A{$row}", 'Đã xác nhận');
        $appointmentSheet->setCellValue("B{$row}", $data['globalStat']->appointments_confirmed);
        $row++;

        $appointmentSheet->setCellValue("A{$row}", 'Đã hoàn thành');
        $appointmentSheet->setCellValue("B{$row}", $data['globalStat']->appointments_completed);
        $row++;

        $appointmentSheet->setCellValue("A{$row}", 'Đã hủy');
        $appointmentSheet->setCellValue("B{$row}", $data['globalStat']->appointments_cancelled);

        // Styling
        $appointmentSheet->getStyle("A4:B{$row}")->applyFromArray($cellStyle);

        // Auto size
        foreach (range('A', 'B') as $col) {
            $appointmentSheet->getColumnDimension($col)->setAutoSize(true);
        }


        // Sheet 2: Thống kê theo thời gian
        $timeSheet = $spreadsheet->createSheet();
        $timeSheet->setTitle('Thống kê thời gian');
        $timeSheet->setCellValue('A1', 'THỐNG KÊ THEO THỜI GIAN');
        $timeSheet->mergeCells('A1:C1');
        $timeSheet->getStyle('A1')->applyFromArray($headerStyle);

        $timeSheet->setCellValue('A3', 'Thời gian');
        $timeSheet->setCellValue('B3', 'Lượt đặt lịch');
        $timeSheet->setCellValue('C3', 'Doanh thu (VND)');
        $timeSheet->getStyle('A3:C3')->applyFromArray($subHeaderStyle);

        $row = 4;
        foreach ($data['statTable'] as $stat) {
            $timeSheet->setCellValue("A{$row}", $stat['label']);
            $timeSheet->setCellValue("B{$row}", $stat['bookings']);
            $timeSheet->setCellValue("C{$row}", number_format($stat['revenue']));
            $timeSheet->getStyle("A{$row}:C{$row}")->applyFromArray($cellStyle);
            $row++;
        }

        // Sheet 3: Thống kê bác sĩ
        $doctorSheet = $spreadsheet->createSheet();
        $doctorSheet->setTitle('Thống kê bác sĩ');
        $doctorSheet->setCellValue('A1', 'THỐNG KÊ BÁC SĨ');
        $doctorSheet->mergeCells('A1:F1');
        $doctorSheet->getStyle('A1')->applyFromArray($headerStyle);

        $doctorHeaders = ['Tên bác sĩ', 'Chuyên khoa', 'Lịch hoàn thành', 'Tổng lịch hẹn', 'Đánh giá TB'];
        $col = 'A';
        foreach ($doctorHeaders as $header) {
            $doctorSheet->setCellValue($col . '3', $header);
            $col++;
        }
        $doctorSheet->getStyle('A3:E3')->applyFromArray($subHeaderStyle);

        $row = 4;
        foreach ($data['doctorStats'] as $doctor) {
            $doctorSheet->setCellValue("A{$row}", $doctor['name']);
            $doctorSheet->setCellValue("B{$row}", $doctor['specialization']);
            $doctorSheet->setCellValue("C{$row}", $doctor['completed_appointments']);
            $doctorSheet->setCellValue("D{$row}", $doctor['total_appointments']);
            $doctorSheet->setCellValue("E{$row}", $doctor['average_rating']);
            $doctorSheet->getStyle("A{$row}:E{$row}")->applyFromArray($cellStyle);
            $row++;
        }

        // Auto-size tất cả sheet
        foreach ($spreadsheet->getAllSheets() as $worksheet) {
            foreach (range('A', $worksheet->getHighestColumn()) as $col) {
                $worksheet->getColumnDimension($col)->setAutoSize(true);
            }
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'dashboard_report_' . Carbon::now()->format('Y_m_d_H_i_s') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
    public function exportPdf(Request $request)
    {
        // Lấy các thống kê giống hàm index
        $doctorStats = Doctor::with('user')
            ->withCount([
                'appointments as completedAppointmentsCount' => function ($q) {
                    $q->where('status', 'completed');
                },
                'appointments as totalAppointmentsCount'
            ])
            ->withAvg('reviews as average_rating', 'rating')
            ->orderByDesc('totalAppointmentsCount')
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
            ->limit(10)
            ->get();
        $topService = $serviceStats->sortByDesc('bookings')->first();

        $today = Carbon::today();
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        // Thống kê hôm nay
        $patientRole = Role::where('name', 'patient')->first();

        $dailyStat = (object)[
            'total_revenue' => Appointment::whereDate('appointment_time', $today)
                ->whereIn('appointments.status', ['completed', 'pending', 'confirmed'])
                ->join('payments', 'appointments.id', '=', 'payments.appointment_id')
                ->sum('payments.amount'),
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
            'total_revenue' => Payment::where('status', 'paid')
                ->where(function ($q) {
                    $q->where('refund_status', 'none')
                        ->orWhere('refund_status', 'failed')
                        ->orWhereNull('refund_status');
                })
                ->sum('amount'),
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

        // Thống kê tăng trưởng
        $prevMonth = Carbon::create($year, $month, 1)->subMonth();

        // Lượt đặt lịch
        $bookingCurrent = Appointment::whereMonth('appointment_time', $month)
            ->whereYear('appointment_time', $year)
            ->where('status', '!=', 'cancelled')
            ->count();

        $bookingPrevious = Appointment::whereMonth('appointment_time', $prevMonth->month)
            ->whereYear('appointment_time', $prevMonth->year)
            ->where('status', '!=', 'cancelled')
            ->count();

        $bookingGrowthValue = $bookingPrevious > 0
            ? round((($bookingCurrent - $bookingPrevious) / $bookingPrevious) * 100)
            : ($bookingCurrent > 0 ? 100 : 0);
        $bookingGrowthLabel = "So với {$prevMonth->month}/{$prevMonth->year}";

        // Doanh thu
        $revenueCurrent = Payment::whereMonth('paid_at', $month)
            ->whereYear('paid_at', $year)
            ->where('status', 'paid')
            ->where('refund_status', 'none')
            ->sum('amount');

        $revenuePrevious = Payment::whereMonth('paid_at', $prevMonth->month)
            ->whereYear('paid_at', $prevMonth->year)
            ->where('status', 'paid')
            ->where('refund_status', 'none')
            ->sum('amount');

        $revenueGrowthValue = $revenuePrevious > 0
            ? round((($revenueCurrent - $revenuePrevious) / $revenuePrevious) * 100)
            : ($revenueCurrent > 0 ? 100 : 0);
        $revenueGrowthLabel = "So với {$prevMonth->month}/{$prevMonth->year}";

        // Thống kê hiệu suất
        $appointments = Appointment::whereYear('appointment_time', now()->year)->get();
        $totalAppointments = $appointments->count();

        $cancelRate = $totalAppointments > 0
            ? round($appointments->where('status', 'cancelled')->count() / $totalAppointments * 100, 1)
            : 0;
        $completedRate = $totalAppointments > 0
            ? round($appointments->where('status', 'completed')->count() / $totalAppointments * 100, 1)
            : 0;

        $onTimeAppointments = Appointment::where('status', 'completed')
            ->whereNotNull('check_in_time')
            ->where('appointment_time', '<=', now())
            ->get();

        $total = $onTimeAppointments->count();
        $onTimeCount = $onTimeAppointments->filter(function ($a) {
            return Carbon::parse($a->check_in_time)
                ->diffInMinutes(Carbon::parse($a->appointment_time), false) <= 5;
        })->count();

        $onTimeRate = $total > 0 ? round($onTimeCount / $total * 100, 1) : 0;

        $waitingTimes = $onTimeAppointments->filter(fn($a) => $a->check_in_time)
            ->map(fn($a) => abs(Carbon::parse($a->check_in_time)->diffInMinutes(Carbon::parse($a->appointment_time))));

        $avgWaiting = $waitingTimes->count() > 0 ? round($waitingTimes->avg(), 1) : null;

        $performanceStats = [
            'cancel_rate' => $cancelRate,
            'completed_rate' => $completedRate,
            'on_time_rate' => $onTimeRate,
            'avg_waiting_time' => $avgWaiting,
        ];

        // Thống kê bệnh nhân
        $patientRole = Role::where('name', 'patient')->first();
        $patientRoleId = $patientRole?->id;

        $newThisWeek = User::where('role_id', $patientRoleId)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

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

        $patientStats = [
            'new_this_week' => $newThisWeek,
            'return_rate' => $returnRate,
            'total_patients' => $totalPatients
        ];

        // Chuẩn bị dữ liệu cho PDF
        $data = [
            'title' => 'Báo cáo thống kê hệ thống',
            'generated_at' => now()->format('d/m/Y H:i:s'),
            'period' => "Tháng {$month}/{$year}",
            'dailyStat' => $dailyStat,
            'globalStat' => $globalStat,
            'bookingCurrent' => $bookingCurrent,
            'bookingGrowthValue' => $bookingGrowthValue,
            'bookingGrowthLabel' => $bookingGrowthLabel,
            'revenueCurrent' => $revenueCurrent,
            'revenueGrowthValue' => $revenueGrowthValue,
            'revenueGrowthLabel' => $revenueGrowthLabel,
            'serviceStats' => $serviceStats->take(5), // Chỉ lấy top 5 cho PDF
            'topService' => $topService,
            'doctorStats' => $doctorStats->take(10), // Top 10 bác sĩ
            'performanceStats' => $performanceStats,
            'patientStats' => $patientStats,
        ];

        // Tạo PDF
        $pdf = PDF::loadView('admin.dashboard.pdf_export', $data);
    
    // Cấu hình PDF để hỗ trợ UTF-8
    $pdf->getDomPDF()->set_option('isPhpEnabled', true);
    $pdf->getDomPDF()->set_option('isRemoteEnabled', true);
    $pdf->getDomPDF()->set_option('isHtml5ParserEnabled', true);
    $pdf->getDomPDF()->set_option('isFontSubsettingEnabled', true);
    
    // Cấu hình paper và DPI
    $pdf->setPaper('A4', 'portrait');
    $pdf->setOptions([
        'dpi' => 150,
        'defaultFont' => 'Arial',
        'defaultMediaType' => 'screen',
        'isFontSubsettingEnabled' => true,
    ]);

    // Tạo tên file không dấu
    $filename = 'bao-cao-thong-ke-' . $month . '-' . $year . '-' . now()->format('YmdHis') . '.pdf';

    return $pdf->download($filename);
}

// Hàm helper để chuyển đổi tiếng Việt có dấu thành không dấu
private function removeAccents($str) {
    $accents = array(
        'à','á','ạ','ả','ã','â','ầ','ấ','ậ','ẩ','ẫ','ă','ằ','ắ','ặ','ẳ','ẵ',
        'è','é','ẹ','ẻ','ẽ','ê','ề','ế','ệ','ể','ễ',
        'ì','í','ị','ỉ','ĩ',
        'ò','ó','ọ','ỏ','õ','ô','ồ','ố','ộ','ổ','ỗ','ơ','ờ','ớ','ợ','ở','ỡ',
        'ù','ú','ụ','ủ','ũ','ư','ừ','ứ','ự','ử','ữ',
        'ỳ','ý','ỵ','ỷ','ỹ',
        'đ',
        'À','Á','Ạ','Ả','Ã','Â','Ầ','Ấ','Ậ','Ẩ','Ẫ','Ă','Ằ','Ắ','Ặ','Ẳ','Ẵ',
        'È','É','Ẹ','Ẻ','Ẽ','Ê','Ề','Ế','Ệ','Ể','Ễ',
        'Ì','Í','Ị','Ỉ','Ĩ',
        'Ò','Ó','Ọ','Ỏ','Õ','Ô','Ồ','Ố','Ộ','Ổ','Ỗ','Ơ','Ờ','Ớ','Ợ','Ở','Ỡ',
        'Ù','Ú','Ụ','Ủ','Ũ','Ư','Ừ','Ứ','Ự','Ử','Ữ',
        'Ỳ','Ý','Ỵ','Ỷ','Ỹ',
        'Đ'
    );
    
    $noAccents = array(
        'a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a',
        'e','e','e','e','e','e','e','e','e','e','e',
        'i','i','i','i','i',
        'o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o',
        'u','u','u','u','u','u','u','u','u','u','u',
        'y','y','y','y','y',
        'd',
        'A','A','A','A','A','A','A','A','A','A','A','A','A','A','A','A','A',
        'E','E','E','E','E','E','E','E','E','E','E',
        'I','I','I','I','I',
        'O','O','O','O','O','O','O','O','O','O','O','O','O','O','O','O','O',
        'U','U','U','U','U','U','U','U','U','U','U',
        'Y','Y','Y','Y','Y',
        'D'
    );
    
    return str_replace($accents, $noAccents, $str);
}

    private function getDashboardData(Request $request)
    {
        $today = Carbon::today();
        $patientRole = Role::where('name', 'nurse')->first();

        // Thống kê hôm nay
        $dailyStat = (object)[
            'total_revenue' => Appointment::whereDate('appointment_time', $today)
                ->whereIn('appointments.status', 'completed')
                ->join('services', 'appointments.service_id', '=', 'services.id')
                ->sum('services.price'),
            'total_doctors' => Doctor::count(),
            'total_patients' => $patientRole ? User::where('role_id', $patientRole->id)->count() : 0,
            'total_appointments' => Appointment::whereDate('appointment_time', $today)->count(),
            'appointments_pending' => Appointment::whereDate('appointment_time', $today)->where('appointments.status', 'pending')->count(),
            'appointments_completed' => Appointment::whereDate('appointment_time', $today)->where('appointments.status', 'completed')->count(),
            'appointments_cancelled' => Appointment::whereDate('appointment_time', $today)->where('appointments.status', 'cancelled')->count(),
            'appointments_confirmed' => Appointment::whereDate('appointment_time', $today)->where('appointments.status', 'confirmed')->count(),
        ];

        // Thống kê toàn hệ thống
        $globalStat = (object)[
            'total_revenue' => Appointment::whereIn('appointments.status', 'completed')
                ->join('services', 'appointments.service_id', '=', 'services.id')
                ->sum('services.price'),

            'total_doctors' => Doctor::count(),
            'total_patients' => $patientRole ? User::where('role_id', $patientRole->id)->count() : 0,
            'total_appointments' => Appointment::count(),
            'appointments_pending' => Appointment::where('status', 'pending')->count(),
            'appointments_confirmed' => Appointment::where('status', 'confirmed')->count(),
            'appointments_completed' => Appointment::where('status', 'completed')->count(),
            'appointments_cancelled' => Appointment::where('status', 'cancelled')->count(),
        ];

        // Thống kê bác sĩ
        $doctorStats = Doctor::with('user')
            ->withCount([
                'appointments as completedAppointmentsCount' => function ($q) {
                    $q->where('status', 'completed');
                },
                'appointments as totalAppointmentsCount'
            ])
            ->withAvg('reviews as average_rating', 'rating')
            ->orderByDesc('totalAppointmentsCount')
            ->get()
            ->map(function ($doctor) {
                return [
                    'name' => $doctor->user->full_name ?? 'Không có tên',
                    'specialization' => $doctor->specialization ?? 'Chưa cập nhật',
                    'completed_appointments' => $doctor->completedAppointmentsCount ?? 0,
                    'total_appointments' => $doctor->totalAppointmentsCount ?? 0,
                    'average_rating' => number_format($doctor->average_rating ?? 0, 1)
                ];
            });

        // Thống kê theo thời gian
        $type = $request->query('type', 'month');
        $timeLabels = [];
        $timeBookings = [];
        $timeRevenues = [];

        if ($type === 'day') {
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('d/m');
                $timeLabels[] = $date;
                $timeBookings[] = Appointment::whereDate('appointment_time', now()->subDays($i))->count();
                $timeRevenues[] = Appointment::whereDate('appointment_time', now()->subDays($i))
                    ->whereIn('appointments.status', ['completed', 'pending', 'confirmed'])
                    ->join('services', 'appointments.service_id', '=', 'services.id')
                    ->sum('services.price');
            }
        } elseif ($type === 'week') {
            for ($i = 4; $i >= 0; $i--) {
                $week = now()->subWeeks($i)->format('W');
                $timeLabels[] = 'Tuần ' . $week;
                $timeBookings[] = Appointment::whereBetween('appointment_time', [
                    now()->subWeeks($i)->startOfWeek(),
                    now()->subWeeks($i)->endOfWeek()
                ])->count();
                $timeRevenues[] = Appointment::whereBetween('appointment_time', [
                    now()->subWeeks($i)->startOfWeek(),
                    now()->subWeeks($i)->endOfWeek()
                ])->whereIn('appointments.status', ['completed', 'pending', 'confirmed'])
                    ->join('services', 'appointments.service_id', '=', 'services.id')
                    ->sum('services.price');
            }
        } elseif ($type === 'year') {
            for ($i = 4; $i >= 0; $i--) {
                $year = now()->subYears($i)->year;
                $timeLabels[] = 'Năm ' . $year;
                $timeBookings[] = Appointment::whereYear('appointment_time', $year)->count();
                $timeRevenues[] = Appointment::whereYear('appointment_time', $year)
                    ->whereIn('appointments.status', ['completed', 'pending', 'confirmed'])
                    ->join('services', 'appointments.service_id', '=', 'services.id')
                    ->sum('services.price');
            }
        } elseif ($type === 'custom') {
            $start = $request->query('start_date');
            $end = $request->query('end_date');
            $startDate = Carbon::parse($start);
            $endDate = Carbon::parse($end);

            while ($startDate->lte($endDate)) {
                $label = $startDate->format('d/m');
                $timeLabels[] = $label;

                $timeBookings[] = Appointment::whereDate('appointment_time', $startDate)->count();
                $timeRevenues[] = Appointment::whereDate('appointment_time', $startDate)
                    ->whereIn('appointments.status', ['completed', 'pending', 'confirmed'])
                    ->join('services', 'appointments.service_id', '=', 'services.id')
                    ->sum('services.price');

                $startDate->addDay();
            }
        } else {
            for ($i = 1; $i <= 12; $i++) {
                $timeLabels[] = 'Tháng ' . $i;
                $timeBookings[] = Appointment::whereYear('appointment_time', now()->year)
                    ->whereMonth('appointment_time', $i)
                    ->count();
                $timeRevenues[] = Appointment::whereYear('appointment_time', now()->year)
                    ->whereMonth('appointment_time', $i)
                    ->whereIn('appointments.status', ['completed', 'pending', 'confirmed'])
                    ->join('services', 'appointments.service_id', '=', 'services.id')
                    ->sum('services.price');
            }
        }

        $statTable = collect([]);
        foreach ($timeLabels as $i => $label) {
            $statTable->push([
                'label' => $label,
                'bookings' => $timeBookings[$i] ?? 0,
                'revenue' => $timeRevenues[$i] ?? 0,
            ]);
        }

        // Thống kê bệnh nhân
        $newPatientCount = User::where('role_id', $patientRole->id)
            ->whereDate('created_at', '>=', now()->startOfWeek())
            ->count();

        $totalPatients = User::where('role_id', $patientRole->id)->count();
        $returnRate = 0;

        if ($totalPatients > 0) {
            $repeatAppointments = Appointment::select('patient_id')
                ->whereIn('patient_id', function ($query) use ($patientRole) {
                    $query->select('id')->from('users')->where('role_id', $patientRole->id);
                })
                ->groupBy('patient_id')
                ->havingRaw('COUNT(*) > 1')
                ->get()
                ->count();

            $returnRate = round(($repeatAppointments / $totalPatients) * 100, 1);
        }

        $areaStats = User::where('role_id', $patientRole->id)
            ->selectRaw('address, COUNT(*) as total')
            ->groupBy('address')
            ->pluck('total', 'address')
            ->toArray();

        return [
            'dailyStat' => $dailyStat,
            'globalStat' => $globalStat,
            'doctorStats' => $doctorStats,
            'statTable' => $statTable,
            'patientStats' => [
                'new_this_week' => $newPatientCount,
                'return_rate'   => $returnRate,
                'area'          => $areaStats
            ],
            'exportDate' => Carbon::now()->format('d/m/Y H:i:s')
        ];
    }
}
