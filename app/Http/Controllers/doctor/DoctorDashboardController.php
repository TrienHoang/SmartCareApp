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
use Illuminate\Support\Facades\Validator;

class DoctorDashboardController extends Controller
{
<<<<<<< HEAD
    public function index(Request $request)
    {
        $user = Auth::user();
        $doctor = $user->doctor;
=======
    public function index(Request $request, $doctorId = null)
    {
        $user = auth()->user();
        $doctor = $user->doctor ?? Doctor::find($doctorId);
>>>>>>> 8b50d06627551d61ef7f0f455357c188c304bd94

        if (!$doctor) {
            abort(403, 'Không tìm thấy thông tin bác sĩ.');
        }

<<<<<<< HEAD
        $doctorId = $doctor->id;

        if (!$request->has('type')) {
            $request->merge([
                'type' => 'month',
                'year' => now()->year
            ]);
        }

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:month,year,custom',
            'year' => [
                'required_if:type,month,year',
                'numeric',
                'min:2000',
                'max:' . now()->year
            ],
            'start_date' => [
                'required_if:type,custom',
                'date',
                'date_format:Y-m-d',
                'before_or_equal:end_date'
            ],
            'end_date' => [
                'required_if:type,custom',
                'date',
                'date_format:Y-m-d',
                'after_or_equal:start_date'
            ]
        ], [
            'type.required' => 'Vui lòng chọn loại thống kê.',
            'type.in' => 'Loại thống kê không hợp lệ.',
            'year.required_if' => 'Vui lòng chọn năm thống kê.',
            'year.numeric' => 'Năm phải là số.',
            'year.min' => 'Năm phải lớn hơn hoặc bằng 2000.',
            'year.max' => 'Năm không được lớn hơn năm hiện tại.',
            'start_date.required_if' => 'Vui lòng chọn ngày bắt đầu.',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ.',
            'start_date.date_format' => 'Định dạng ngày bắt đầu không đúng (Y-m-d).',
            'start_date.before_or_equal' => 'Ngày bắt đầu không được lớn hơn ngày kết thúc.',
            'end_date.required_if' => 'Vui lòng chọn ngày kết thúc.',
            'end_date.date' => 'Ngày kết thúc không hợp lệ.',
            'end_date.date_format' => 'Định dạng ngày kết thúc không đúng (Y-m-d).',
            'end_date.after_or_equal' => 'Ngày kết thúc không được nhỏ hơn ngày bắt đầu.'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $type = $request->input('type', 'month');
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $year = $request->input('year', now()->year);

        if ($type === 'custom') {
            $startDate = Carbon::parse($start)->startOfDay();
            $endDate = Carbon::parse($end)->endOfDay();

            if ($startDate->diffInDays($endDate) > 62) {
                return back()->with('error', 'Khoảng thời gian tối đa là 62 ngày.');
            }
        }

        $totalPatients = Appointment::where('doctor_id', $doctorId)
            ->whereNotNull('patient_id')
            ->distinct('patient_id')
            ->count();

        $todayAppointments = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_time', today())
            ->count();

        $appointments_pending = Appointment::where('doctor_id', $doctorId)
            ->where('status', 'pending')
            ->whereDate('appointment_time', today())
            ->count();

        $appointments_confirmed = Appointment::where('doctor_id', $doctorId)
            ->where('status', 'confirmed')
            ->whereDate('appointment_time', today())
            ->count();

        $appointments_completed = Appointment::where('doctor_id', $doctorId)
            ->where('status', 'completed')
            ->whereDate('appointment_time', today())
            ->count();

        $appointments_cancelled = Appointment::where('doctor_id', $doctorId)
            ->where('status', 'cancelled')
            ->whereDate('appointment_time', today())
            ->count();

        $totalRevenue = Appointment::join('services', 'appointments.service_id', '=', 'services.id')
            ->where('appointments.doctor_id', $doctorId)
            ->where('appointments.status', 'completed')
            ->sum('services.price');

        $totalAppointments = Appointment::where('doctor_id', $doctorId)->count();
        $successAppointments = Appointment::where('doctor_id', $doctorId)
            ->where('status', 'completed')
            ->count();
        $cancelAppointments = Appointment::where('doctor_id', $doctorId)
            ->where('status', 'cancelled')
            ->count();

        $successRate = $totalAppointments > 0 ? round($successAppointments / $totalAppointments * 100, 1) : 0;
        $cancelRate = $totalAppointments > 0 ? round($cancelAppointments / $totalAppointments * 100, 1) : 0;

        $statLabels = [];
        $statBookings = [];
        $statRevenue = [];

        if ($type === 'month') {
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

        $growthValue = 0;
        $growthLabel = 'Không đổi';
        if (count($statBookings) > 1) {
            $last = end($statBookings);
            $prev = prev($statBookings);
            if ($prev > 0) {
                $growthValue = round((($last - $prev) / $prev) * 100, 1);
                $growthLabel = $growthValue > 0 ? 'Tăng' : ($growthValue < 0 ? 'Giảm' : 'Không đổi');
            }
        }

        $revenueGrowth = 0;
        $revenueLabel = 'Không đổi';
        if (count($statRevenue) > 1) {
            $lastR = end($statRevenue);
            $prevR = prev($statRevenue);
            if ($prevR > 0) {
                $revenueGrowth = round((($lastR - $prevR) / $prevR) * 100, 1);
                $revenueLabel = $revenueGrowth > 0 ? 'Tăng' : ($revenueGrowth < 0 ? 'Giảm' : 'Không đổi');
            }
        }

        // Thống kê số lượng file đã tải lên
        $fileUploadsCount = \App\Models\FileUpload::where('user_id', $user->id)->count();

        return view('doctor.dashboard.index', compact(
            'doctor',
            'totalPatients',
            'todayAppointments',
            'totalRevenue',
            'type',
            'statLabels',
            'statBookings',
            'statRevenue',
=======
        // Nếu không có type thì mặc định là tháng hiện tại
        $request->merge([
            'type' => $request->input('type', 'month'),
            'year' => $request->input('year', now()->year),
        ]);

        // Validate dữ liệu lọc
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:month,year,custom',
            'year' => 'required_if:type,month,year|nullable|integer|min:2000|max:' . now()->year,
            'start_date' => 'required_if:type,custom|date|nullable',
            'end_date' => 'required_if:type,custom|date|nullable|after_or_equal:start_date',
        ], [
            'type.required' => 'Vui lòng chọn loại thống kê.',
            'type.in' => 'Loại thống kê không hợp lệ.',
            'year.required_if' => 'Vui lòng nhập năm.',
            'year.integer' => 'Năm phải là số nguyên.',
            'year.min' => 'Năm phải từ 2000 trở lên.',
            'year.max' => 'Năm không được vượt quá hiện tại.',
            'start_date.required_if' => 'Vui lòng chọn ngày bắt đầu.',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ.',
            'end_date.required_if' => 'Vui lòng chọn ngày kết thúc.',
            'end_date.date' => 'Ngày kết thúc không hợp lệ.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $type = $request->input('type');
        $year = (int)$request->input('year');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Xử lý khoảng thời gian thống kê
        $labels = [];
        $appointmentsByTime = [];
        $revenueByTime = [];

        switch ($type) {
            case 'month':
                for ($month = 1; $month <= 12; $month++) {
                    $labels[] = "Tháng $month";
                    $appointments = Appointment::where('doctor_id', $doctor->id)
                        ->whereYear('appointment_time', $year)
                        ->whereMonth('appointment_time', $month);
                    $appointmentsByTime[] = $appointments->count();
                    $revenueByTime[] = $appointments->join('services', 'appointments.service_id', '=', 'services.id')
                        ->where('appointments.status', 'completed')->sum('services.price');
                }
                break;

            case 'year':
                for ($i = $year - 5; $i <= $year; $i++) {
                    $labels[] = "Năm $i";
                    $appointments = Appointment::where('doctor_id', $doctor->id)
                        ->whereYear('appointment_time', $i);
                    $appointmentsByTime[] = $appointments->count();
                    $revenueByTime[] = $appointments->join('services', 'appointments.service_id', '=', 'services.id')
                        ->where('appointments.status', 'completed')->sum('services.price');
                }
                break;

            case 'custom':
                $start = Carbon::parse($startDate)->startOfDay();
                $end = Carbon::parse($endDate)->endOfDay();

                if ($start->diffInDays($end) > 62) {
                    return redirect()->back()->with('error', 'Khoảng thời gian thống kê tối đa là 62 ngày.');
                }

                $current = $start->copy();
                while ($current->lte($end)) {
                    $label = $current->format('d/m');
                    $labels[] = $label;

                    $appointments = Appointment::where('doctor_id', $doctor->id)
                        ->whereDate('appointment_time', $current);
                    $appointmentsByTime[] = $appointments->count();
                    $revenueByTime[] = $appointments->join('services', 'appointments.service_id', '=', 'services.id')
                        ->where('appointments.status', 'completed')->sum('services.price');

                    $current->addDay();
                }
                break;
        }

        // Dữ liệu thống kê tổng
        $today = now()->toDateString();

        $totalRevenue = Appointment::join('services', 'appointments.service_id', '=', 'services.id')
            ->where('doctor_id', $doctor->id)
            ->where('appointments.status', 'completed')
            ->sum('services.price');

        $totalPatients = Appointment::where('doctor_id', $doctor->id)
            ->whereNotNull('patient_id')
            ->distinct('patient_id')->count('patient_id');

        $todayAppointments = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_time', $today)
            ->count();

        $appointments_pending = Appointment::where('doctor_id', $doctor->id)->where('status', 'pending')->count();
        $appointments_confirmed = Appointment::where('doctor_id', $doctor->id)->where('status', 'confirmed')->count();
        $appointments_completed = Appointment::where('doctor_id', $doctor->id)->where('status', 'completed')->count();
        $appointments_cancelled = Appointment::where('doctor_id', $doctor->id)->where('status', 'cancelled')->count();

        $totalAppointments = Appointment::where('doctor_id', $doctor->id)->count();
        $successAppointments = $appointments_completed;
        $cancelAppointments = $appointments_cancelled;

        $successRate = $totalAppointments > 0 ? round(($successAppointments / $totalAppointments) * 100, 2) : 0;
        $cancelRate = $totalAppointments > 0 ? round(($cancelAppointments / $totalAppointments) * 100, 2) : 0;

        // Tăng trưởng theo thời gian
        $last = count($appointmentsByTime) > 1 ? $appointmentsByTime[count($appointmentsByTime) - 2] : 0;
        $current = $appointmentsByTime[count($appointmentsByTime) - 1] ?? 0;

        $growthValue = $last == 0 ? ($current > 0 ? 100 : 0) : round((($current - $last) / $last) * 100, 2);
        $growthLabel = "So với kỳ trước";

        $lastRevenue = count($revenueByTime) > 1 ? $revenueByTime[count($revenueByTime) - 2] : 0;
        $currentRevenue = $revenueByTime[count($revenueByTime) - 1] ?? 0;

        $revenueGrowth = $lastRevenue == 0 ? ($currentRevenue > 0 ? 100 : 0) : round((($currentRevenue - $lastRevenue) / $lastRevenue) * 100, 2);
        $revenueLabel = "So với kỳ trước";

        return view('doctor.dashboard.index', compact(
            'doctor',
            'type',
            'totalRevenue',
            'totalPatients',
            'todayAppointments',
>>>>>>> 8b50d06627551d61ef7f0f455357c188c304bd94
            'appointments_pending',
            'appointments_confirmed',
            'appointments_completed',
            'appointments_cancelled',
<<<<<<< HEAD
            'successRate',
            'successAppointments',
            'totalAppointments',
            'cancelRate',
            'cancelAppointments',
=======
            'totalAppointments',
            'successAppointments',
            'cancelAppointments',
            'successRate',
            'cancelRate',
>>>>>>> 8b50d06627551d61ef7f0f455357c188c304bd94
            'growthValue',
            'growthLabel',
            'revenueGrowth',
            'revenueLabel',
<<<<<<< HEAD
            'fileUploadsCount'
        ));
    }



=======
            'labels',
            'appointmentsByTime',
            'revenueByTime'
        ))->with([
            'statLabels' => $labels,
            'statBookings' => $appointmentsByTime,
            'statRevenue' => $revenueByTime,
        ]);
    }
>>>>>>> 8b50d06627551d61ef7f0f455357c188c304bd94
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
