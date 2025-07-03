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
                ->where('status', 'completed')
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

                    $timeBookings[] = Appointment::whereDate('appointment_time', $currentDate)->count();

                    $timeRevenues[] = Appointment::whereDate('appointment_time', $currentDate)
                        ->where('appointments.status', 'completed')
                        ->join('services', 'appointments.service_id', '=', 'services.id')
                        ->sum('services.price');

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

                    $timeBookings[] = Appointment::whereBetween('appointment_time', [$currentStart, $weekEnd])->count();

                    $timeRevenues[] = Appointment::whereBetween('appointment_time', [$currentStart, $weekEnd])
                        ->where('appointments.status', 'completed')
                        ->join('services', 'appointments.service_id', '=', 'services.id')
                        ->sum('services.price');

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
                        $timeBookings[] = Appointment::whereDate('appointment_time', $date)->count();
                        $timeRevenues[] = Appointment::whereDate('appointment_time', $date)
                            ->where('appointments.status', 'completed')
                            ->join('services', 'appointments.service_id', '=', 'services.id')
                            ->sum('services.price');
                    }
                    break;

                case 'week':
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
        $data = $this->getDashboardData($request);

        $pdf = Pdf::loadView('admin.dashboard.pdf_export', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'dpi' => 150,
                'defaultFont' => 'dejavusans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);


        $fileName = 'dashboard_report_' . Carbon::now()->format('Y_m_d_H_i_s') . '.pdf';

        return $pdf->download($fileName);
    }

    private function getDashboardData(Request $request)
    {
        $today = Carbon::today();
        $patientRole = Role::where('name', 'patient')->first();

        // Thống kê hôm nay
        $dailyStat = (object)[
            'total_revenue' => Appointment::whereDate('appointment_time', $today)
                ->where('appointments.status', 'completed')
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
            'total_revenue' => Appointment::where('appointments.status', 'completed')
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
                    ->where('appointments.status', 'completed')
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
                ])->where('appointments.status', 'completed')
                    ->join('services', 'appointments.service_id', '=', 'services.id')
                    ->sum('services.price');
            }
        } elseif ($type === 'year') {
            for ($i = 4; $i >= 0; $i--) {
                $year = now()->subYears($i)->year;
                $timeLabels[] = 'Năm ' . $year;
                $timeBookings[] = Appointment::whereYear('appointment_time', $year)->count();
                $timeRevenues[] = Appointment::whereYear('appointment_time', $year)
                    ->where('appointments.status', 'completed')
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
                    ->where('appointments.status', 'completed')
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
                    ->where('appointments.status', 'completed')
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
