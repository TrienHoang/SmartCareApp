<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Prescription;
use App\Models\DoctorLeave;
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

        $request->validate([
            'type' => 'in:month,year,custom',
            'year' => 'nullable|integer|min:2000|max:' . now()->year,
            'start_date' => 'nullable|date|before_or_equal:end_date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $statLabels = [];
        $statBookings = [];

        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);
            if ($start->year == $year && $end->year == $year) {
                if ($start->diffInDays($end) > 62) {
                    return redirect()->back()->withErrors(['end_date' => 'Khoảng thời gian tối đa là 62 ngày.']);
                }
                while ($start <= $end) {
                    $label = $start->format('d/m/Y');
                    $statLabels[] = $label;
                    $statBookings[] = Appointment::where('doctor_id', $doctor->id)
                        ->whereDate('appointment_time', $start)
                        ->count();
                    $start->addDay();
                }
            } else {
                return redirect()->back()->withErrors(['start_date' => 'Ngày phải nằm trong năm đã chọn.']);
            }
        } else {
            if ($type === 'month') {
                $start = Carbon::create($year, now()->month, 1)->startOfMonth();
                $end = $start->copy()->endOfMonth();
                while ($start <= $end) {
                    $label = $start->format('d/m/Y');
                    $statLabels[] = $label;
                    $statBookings[] = Appointment::where('doctor_id', $doctor->id)
                        ->whereDate('appointment_time', $start)
                        ->count();
                    $start->addDay();
                }
            } elseif ($type === 'year' || $type === 'custom') {
                for ($i = 1; $i <= 12; $i++) {
                    $statLabels[] = 'Tháng ' . $i;
                    $start = Carbon::create($year, $i, 1)->startOfMonth();
                    $end = $start->copy()->endOfMonth();
                    $statBookings[] = Appointment::where('doctor_id', $doctor->id)
                        ->whereBetween('appointment_time', [$start, $end])
                        ->count();
                }
            }
        }

        $bookingGrowth = $monthAppointments - $weekAppointments;
        $growthValue = $weekAppointments ? round($bookingGrowth / $weekAppointments * 100, 1) : 0;
        $growthLabel = 'So với tuần trước';

        return view('doctor.dashboard.index', [
            'doctor' => $doctor,
            'totalAppointments' => $totalAppointments,
            'todayAppointments' => $todayAppointments,
            'weekAppointments' => $weekAppointments,
            'monthAppointments' => $monthAppointments,
            'successRate' => $successRate,
            'cancelRate' => $cancelRate,
            'successAppointments' => $successAppointments,
            'cancelAppointments' => $cancelAppointments,
            'totalPatients' => $totalPatients,
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
            'growthValue' => $growthValue,
            'growthLabel' => $growthLabel,
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
            $dashboardData = app()->call([$this, 'index'], ['request' => $request])->getData();
            $data = (array) $dashboardData;
            $pdf = Pdf::loadView('doctor.dashboard.stats-pdf', $data);
            return $pdf->download("doctor_stats_{$doctorId}.pdf");
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Lỗi khi tạo PDF: ' . $e->getMessage()]);
        }
    }
}
