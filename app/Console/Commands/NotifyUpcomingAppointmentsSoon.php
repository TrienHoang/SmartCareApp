<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\AdminNotificationController;
use App\Models\Admin_notification;
use App\Models\Appointment;
use App\Models\User;
use App\Notifications\UpcomingAppointmentNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class NotifyUpcomingAppointmentsSoon extends Command
{
    protected $signature = 'appointments:notify-upcoming-soon {--dry-run}';
    protected $description = 'Cảnh báo admin: lịch hẹn sắp đến giờ nhưng chưa được xác nhận';

    public function handle(): int
    {
        $now = now();

        // Lấy danh sách admin (tùy hệ thống role của bạn)
        $adminIds = User::query()
            ->whereHas('role', fn($q) => $q->where('name', 'admin'))
            ->pluck('id')
            ->values()
            ->all();

        if (empty($adminIds)) {
            $this->warn('Không tìm thấy admin nào để nhận thông báo.');
            return self::SUCCESS;
        }

        // Quét các lịch pending trong 24h tới để hạn chế scan
        $appointments = Appointment::query()
            ->with(['service:id,name,min_booking_hours', 'patient:id,full_name', 'doctor.user:id,full_name'])
            ->where('status', 'pending')
            ->whereBetween('appointment_time', [$now, $now->copy()->addDay()])
            ->get();

        $count = 0;

        foreach ($appointments as $appt) {
            $minHours = (int) ($appt->service->min_booking_hours ?? 0);
            if ($minHours <= 0) {
                continue;
            }

            $minutesLeft = Carbon::parse($appt->appointment_time)->diffInMinutes($now, false) * -1;
            if ($minutesLeft < 0 || $minutesLeft > $minHours * 60) {
                continue;
            }

            $title = 'Lịch hẹn sắp đến giờ (chưa xác nhận)';
            $content = sprintf(
                'Lịch hẹn #%d - BN: %s - BS: %s - %s. Vui lòng xác nhận.',
                $appt->id,
                optional($appt->patient)->full_name ?? 'N/A',
                optional(optional($appt->doctor)->user)->full_name ?? 'N/A',
                Carbon::parse($appt->appointment_time)->format('d/m/Y H:i')
            );

            if ($this->option('dry-run')) {
                $this->line("[DRY] Would notify: {$content}");
                $count++;
                continue;
            }

            // Kiểm tra đã tạo thông báo trong hôm nay cho appointment này chưa
            $exists = Admin_notification::where('type', 'upcoming_appointment_warning')
                ->whereDate('created_at', now()->toDateString())
                ->where('content', 'like', '%#' . $appt->id . '%')
                ->exists();

            if (!$exists) {
                // Gửi notification cho tất cả admin
                $admins = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->get();

                foreach ($admins as $admin) {
                    $alreadySent = $admin->notifications()
                        ->whereDate('created_at', now()->toDateString())
                        ->where('data->appointment_id', $appt->id)
                        ->exists();

                    if (!$alreadySent) {
                        $admin->notify(new UpcomingAppointmentNotification($appt));
                    }
                }
            }

            $count++;
        }

        $this->info("Đã xử lý {$count} thông báo.");
        return self::SUCCESS;
    }
}
