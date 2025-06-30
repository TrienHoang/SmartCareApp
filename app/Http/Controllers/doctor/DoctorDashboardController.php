<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Appointment;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\DoctorStatsExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DoctorDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Kiểm tra mối quan hệ: mỗi user là 1 bác sĩ
        $doctor = $user->doctor;

        if (!$doctor) {
            abort(403, 'Không tìm thấy thông tin bác sĩ');
        }

        $doctorId = $doctor->id;

        // Tổng số bệnh nhân
        $totalPatients = Appointment::where('doctor_id', $doctorId)
            ->whereNotNull('patient_id')
            ->distinct('patient_id')
            ->count();

        // Lịch hẹn hôm nay
        $todayAppointments = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_time', today())
            ->count();

        // Doanh thu hoàn thành
        $totalRevenue = Appointment::join('services', 'appointments.service_id', '=', 'services.id')
            ->where('appointments.doctor_id', $doctorId)
            ->where('appointments.status', 'completed')
            ->sum('services.price');

        // Biểu đồ 7 ngày
        $visitsChart = collect();
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        $dateRange = new \DatePeriod($startDate, new \DateInterval('P1D'), $endDate->copy()->addDay());

        foreach ($dateRange as $date) {
            $count = Appointment::where('doctor_id', $doctorId)
                ->where('status', 'completed')
                ->whereDate('appointment_time', $date->format('Y-m-d'))
                ->count();

            $visitsChart->push([
                'day' => $date->format('d/m'),
                'total' => $count
            ]);
        }

        // --- Phân loại thống kê ---
        $type = $request->query('type', 'month');
        $statLabels = [];
        $statBookings = [];
        $statRevenue = [];

        if ($type === 'month') {
            $year = $request->input('year', now()->year);
            for ($i = 1; $i <= 12; $i++) {
                $statLabels[] = 'Tháng ' . $i;
                $statBookings[] = Appointment::where('doctor_id', $doctorId)
                    ->whereYear('appointment_time', $year)
                    ->whereMonth('appointment_time', $i)
                    ->count();

                $statRevenue[] = Appointment::join('services', 'appointments.service_id', '=', 'services.id')
                    ->where('appointments.doctor_id', $doctorId)
                    ->where('appointments.status', 'completed')
                    ->whereYear('appointments.appointment_time', $year)
                    ->whereMonth('appointments.appointment_time', $i)
                    ->sum('services.price');
            }
        } elseif ($type === 'year') {
            $currentYear = now()->year;
            for ($i = $currentYear - 4; $i <= $currentYear; $i++) {
                $statLabels[] = 'Năm ' . $i;
                $statBookings[] = Appointment::where('doctor_id', $doctorId)
                    ->whereYear('appointment_time', $i)
                    ->count();

                $statRevenue[] = Appointment::join('services', 'appointments.service_id', '=', 'services.id')
                    ->where('appointments.doctor_id', $doctorId)
                    ->where('appointments.status', 'completed')
                    ->whereYear('appointments.appointment_time', $i)
                    ->sum('services.price');
            }
        } elseif ($type === 'custom') {
            $start = $request->input('start_date');
            $end = $request->input('end_date');

            if ($start && $end) {
                $startDate = Carbon::parse($start)->startOfDay();
                $endDate = Carbon::parse($end)->endOfDay();
                $diff = $startDate->diffInDays($endDate);

                if ($diff <= 31) {
                    $dateRange = new \DatePeriod($startDate, new \DateInterval('P1D'), $endDate->copy()->addDay());
                    foreach ($dateRange as $date) {
                        $statLabels[] = $date->format('d/m');
                        $statBookings[] = Appointment::where('doctor_id', $doctorId)
                            ->whereDate('appointment_time', $date->format('Y-m-d'))
                            ->count();
                        $statRevenue[] = Appointment::join('services', 'appointments.service_id', '=', 'services.id')
                            ->where('appointments.doctor_id', $doctorId)
                            ->where('appointments.status', 'completed')
                            ->whereDate('appointments.appointment_time', $date->format('Y-m-d'))
                            ->sum('services.price');
                    }
                } else {
                    $currentStart = $startDate->copy()->startOfWeek();
                    while ($currentStart <= $endDate) {
                        $weekEnd = $currentStart->copy()->endOfWeek()->min($endDate);
                        $statLabels[] = $currentStart->format('d/m') . ' - ' . $weekEnd->format('d/m');
                        $statBookings[] = Appointment::where('doctor_id', $doctorId)
                            ->whereBetween('appointment_time', [$currentStart, $weekEnd])
                            ->count();
                        $statRevenue[] = Appointment::join('services', 'appointments.service_id', '=', 'services.id')
                            ->where('appointments.doctor_id', $doctorId)
                            ->where('appointments.status', 'completed')
                            ->whereBetween('appointments.appointment_time', [$currentStart, $weekEnd])
                            ->sum('services.price');
                        $currentStart->addWeek();
                    }
                }
            }
        }

        return view('doctor.dashboard.index', compact(
            'doctor',
            'totalPatients',
            'todayAppointments',
            'totalRevenue',
            'visitsChart',
            'type',
            'statLabels',
            'statBookings',
            'statRevenue'
        ));
    }


    public function exportExcel($doctorId)
    {
        $doctor = Doctor::findOrFail($doctorId);

        $totalPatients = Appointment::where('doctor_id', $doctorId)
            ->whereNotNull('patient_id')
            ->distinct('patient_id')
            ->count();

        $todayAppointments = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_time', today())
            ->count();

        $totalRevenue = Appointment::join('services', 'appointments.service_id', '=', 'services.id')
            ->where('appointments.doctor_id', $doctorId)
            ->where('appointments.status', 'completed')
            ->sum('services.price');

        $visitsChart = collect();
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        $dateRange = new \DatePeriod($startDate, new \DateInterval('P1D'), $endDate->copy()->addDay());

        foreach ($dateRange as $date) {
            $count = Appointment::where('doctor_id', $doctorId)
                ->where('status', 'completed')
                ->whereDate('appointment_time', $date->format('Y-m-d'))
                ->count();
            $visitsChart->push([
                'day' => $date->format('d/m'),
                'total' => $count
            ]);
        }

        return Excel::download(
            new DoctorStatsExport($doctor, $totalPatients, $todayAppointments, $totalRevenue, $visitsChart),
            'thong_ke_bac_si.xlsx'
        );
    }

    public function exportPDF($doctorId)
    {
        $doctor = Doctor::findOrFail($doctorId);

        $totalPatients = Appointment::where('doctor_id', $doctorId)
            ->whereNotNull('patient_id')
            ->distinct('patient_id')
            ->count();

        $todayAppointments = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_time', today())
            ->count();

        $totalRevenue = Appointment::where('appointments.doctor_id', $doctorId)
            ->where('appointments.status', 'completed')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->sum('services.price');

        $visitsChart = Appointment::selectRaw('DATE(appointment_time) as day, COUNT(*) as total')
            ->where('doctor_id', $doctorId)
            ->where('status', 'completed')
            ->groupByRaw('DATE(appointment_time)')
            ->orderBy('day', 'desc')
            ->limit(7)
            ->get()
            ->map(function ($item) {
                return [
                    'day' => \Carbon\Carbon::parse($item->day)->format('d/m'),
                    'total' => $item->total,
                ];
            });

        $pdf = Pdf::loadView('doctor.dashboard.stats-pdf', [
            'doctor' => $doctor,
            'totalPatients' => $totalPatients,
            'todayAppointments' => $todayAppointments,
            'totalRevenue' => $totalRevenue,
            'visitsChart' => $visitsChart,
        ]);

        return $pdf->download('thong-ke-bac-si-' . $doctor->id . '.pdf');
    }
}
