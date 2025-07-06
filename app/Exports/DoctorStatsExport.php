<?php

namespace App\Exports;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorLeave;
use App\Models\Prescription;
use App\Models\WorkingSchedule;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DoctorStatsExport implements FromArray, WithHeadings, WithStyles
{
    protected $doctor;
    protected $filters;
    protected $topPrescribed;

    public function __construct(Doctor $doctor, array $filters)
    {
        $this->doctor = $doctor;
        $this->filters = $filters;

        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }

    public function array(): array
    {
        $now = Carbon::now();
        $today = Carbon::today();

        $appointments = Appointment::where('doctor_id', $this->doctor->id);
        $totalAppointments = $appointments->count();
        $todayAppointments = $appointments->clone()->whereDate('appointment_time', $today)->count();
        $weekAppointments = $appointments->clone()->whereBetween('appointment_time', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()])->count();
        $monthAppointments = $appointments->clone()->whereMonth('appointment_time', $now->month)->whereYear('appointment_time', $now->year)->count();

        $completed = $appointments->clone()->where('status', 'completed')->count();
        $cancelled = $appointments->clone()->where('status', 'cancelled')->count();

        $successRate = $totalAppointments ? round($completed / $totalAppointments * 100, 1) : 0;
        $cancelRate = $totalAppointments ? round($cancelled / $totalAppointments * 100, 1) : 0;

        $appointments_pending = $appointments->clone()->whereDate('appointment_time', $today)->where('status', 'pending')->count();
        $appointments_confirmed = $appointments->clone()->whereDate('appointment_time', $today)->where('status', 'confirmed')->count();
        $appointments_completed = $appointments->clone()->whereDate('appointment_time', $today)->where('status', 'completed')->count();
        $appointments_cancelled = $appointments->clone()->whereDate('appointment_time', $today)->where('status', 'cancelled')->count();

        $totalPatients = $appointments->clone()->distinct('patient_id')->count('patient_id');

        $totalPrescriptions = Prescription::where('doctor_id', $this->doctor->id)->count();
        $todayPrescriptions = Prescription::where('doctor_id', $this->doctor->id)->whereDate('created_at', $today)->count();

        $topPrescribed = Prescription::join('prescription_items', 'prescriptions.id', '=', 'prescription_items.prescription_id')
            ->join('medicines', 'prescription_items.medicine_id', '=', 'medicines.id')
            ->where('prescriptions.doctor_id', $this->doctor->id)
            ->whereNull('prescriptions.deleted_at')
            ->selectRaw('medicines.name as medicine_name, COUNT(*) as total')
            ->groupBy('medicines.name')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        $leaveRequests = DoctorLeave::where('doctor_id', $this->doctor->id);
        $totalLeaves = $leaveRequests->count();
        $approvedLeaves = $leaveRequests->clone()->where('approved', true)->count();

        $totalHoursWorked = WorkingSchedule::where('doctor_id', $this->doctor->id)->count();

        $data = [
            ['THỐNG KÊ BÁC SĨ'],
            ['Tên bác sĩ', $this->doctor->user->full_name ?? $this->doctor->name],
            ['Chuyên khoa', $this->doctor->specialization ?? 'Chưa cập nhật'],
            ['Thời gian', 'Tháng ' . $now->month . '/' . $now->year],
            [],
            ['TỔNG QUAN'],
            ['Chỉ số', 'Giá trị'],
            ['Tổng số bệnh nhân', $totalPatients],
            ['Tổng số lịch hẹn', $totalAppointments],
            ['Lịch hẹn hôm nay', $todayAppointments],
            ['Lịch hẹn trong tuần', $weekAppointments],
            ['Lịch hẹn trong tháng', $monthAppointments],
            ['Tỷ lệ khám thành công', $successRate . '%'],
            ['Tỷ lệ hủy lịch', $cancelRate . '%'],
            ['Lịch hẹn chờ xác nhận hôm nay', $appointments_pending],
            ['Lịch hẹn đã xác nhận hôm nay', $appointments_confirmed],
            ['Lịch hẹn hoàn thành hôm nay', $appointments_completed],
            ['Lịch hẹn đã hủy hôm nay', $appointments_cancelled],
            ['Tổng số đơn thuốc', $totalPrescriptions],
            ['Đơn thuốc hôm nay', $todayPrescriptions],
            ['Tổng số ngày nghỉ', $totalLeaves],
            ['Ngày nghỉ được duyệt', $approvedLeaves],
            ['Tổng giờ làm việc', $totalHoursWorked],
            [],
            ['TOP 5 THUỐC ĐƯỢC KÊ ĐƠN NHIỀU NHẤT'],
            ['Tên thuốc', 'Số lượng'],
        ];

        foreach ($topPrescribed as $item) {
            $data[] = [$item->medicine_name, $item->total];
        }

        return $data;
    }

    public function headings(): array
    {
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:C1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FF4472C4']],
            'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FFE3F2FD']],
        ]);

        $sheet->getStyle('A6:B6')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FF1565C0']],
            'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FFE3F2FD']],
        ]);

        return [];
    }
}
