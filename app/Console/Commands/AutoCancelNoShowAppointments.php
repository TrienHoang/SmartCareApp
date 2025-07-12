<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoCancelNoShowAppointments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:auto-cancel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tự động hủy các lịch hẹn quá hạn mà bệnh nhân không đến (trạng thái vẫn pending hoặc confirmed)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $appointments = Appointment::whereIn('status', ['pending', 'confirmed'])
            ->where('appointment_time', '<', $now)
            ->get();

        $count = 0;

        foreach ($appointments as $appointment) {
            $oldStatus = $appointment->status;

            $appointment->update([
                'status' => 'cancelled',
                'cancel_reason' => 'Tự động hủy do bệnh nhân không đến'
            ]);

            // Ghi log
            $appointment->logs()->create([
                'changed_by' => 1, // hệ thống
                'status_before' => $oldStatus,
                'status_after' => 'cancelled',
                'change_time' => now(),
                'note' => 'Tự động hủy do bệnh nhân không đến'
            ]);

            $count++;
        }

        $this->info("✅ Đã tự động hủy {$count} lịch hẹn quá hạn.");
    }
}
