<?php



namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{Doctor, User, Appointment, Payment, Statistic};
use Carbon\Carbon;

class SnapshotStatistics extends Command
{
    protected $signature = 'snapshot:statistics {type=daily}';
    protected $description = 'Tạo thống kê tổng quan theo ngày, tháng hoặc năm';

    public function handle(): void
    {
        $type = $this->argument('type');

        $now = Carbon::now();

        // Xác định mốc thời gian theo loại
        $date = match ($type) {
            'daily' => $now->copy()->startOfDay(),
            'monthly' => $now->copy()->startOfMonth(),
            'yearly' => $now->copy()->startOfYear(),
            default => $now->copy()->startOfDay(),
        };

        $to = match ($type) {
            'daily' => $now->copy()->endOfDay(),
            'monthly' => $now->copy()->endOfMonth(),
            'yearly' => $now->copy()->endOfYear(),
            default => $now->copy()->endOfDay(),
        };

        // Ghi snapshot vào bảng statistics
        Statistic::updateOrCreate(
            ['type' => $type, 'date' => $date->toDateString()],
            [
                'total_doctors' => Doctor::count(),
                'total_patients' => User::whereHas('role', fn($q) => $q->where('name', 'patient'))->count(),
                'total_appointments' => Appointment::whereBetween('appointment_time', [$date, $to])->count(),
                'total_revenue' => Payment::where('status', 'paid')->whereBetween('paid_at', [$date, $to])->sum('amount'),
                'appointments_pending' => Appointment::where('status', 'pending')->whereBetween('appointment_time', [$date, $to])->count(),
                'appointments_completed' => Appointment::where('status', 'completed')->whereBetween('appointment_time', [$date, $to])->count(),
                'appointments_cancelled' => Appointment::where('status', 'cancelled')->whereBetween('appointment_time', [$date, $to])->count(),
            ]
        );

        $this->info("Đã tạo snapshot thống kê [$type] cho thời gian $date -> $to");
    }
}