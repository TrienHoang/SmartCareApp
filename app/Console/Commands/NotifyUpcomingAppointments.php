<?php

namespace App\Console\Commands;

use App\Models\Admin_notification;
use App\Models\Appointment;
use App\Notifications\UpcomingAppointment1DayNotification;
use App\Notifications\UpcomingAppointmentNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;

class NotifyUpcomingAppointments extends Command
{
    protected $signature = 'appointments:notify-upcoming';
    protected $description = 'Gửi thông báo cho các lịch hẹn sắp đến';

    public function handle(): int
    {
        $now = Carbon::now();
        $from = $now->copy()->addDay()->startOfDay();
        $to   = $now->copy()->addDay()->endOfDay();

        $appointments = Appointment::with(['patient', 'doctor.user'])
            ->where('status', 'confirmed')
            ->whereBetween('appointment_time', [$from, $to])
            ->get();

        foreach ($appointments as $appointment) {
            $patient = $appointment->patient;

            if ($patient && $patient->email) {
                $alreadySent = $patient->notifications()
                    ->where('type', UpcomingAppointment1DayNotification::class)
                    ->whereJsonContains('data->appointment_id', $appointment->id)
                    ->exists();

                if (!$alreadySent) {
                    $patient->notify(new UpcomingAppointment1DayNotification($appointment));

                    Admin_notification::create([
                        'title'          => 'Lịch hẹn ngày mai',
                        'content'        => 'Bệnh nhân <strong>' . e($patient->full_name) . '</strong> có lịch hẹn với bác sĩ <strong>' .
                            e($appointment->doctor->user->full_name) . '</strong> vào ngày mai lúc <strong>' .
                            $appointment->appointment_time->format('H:i d/m/Y') . '</strong>.',
                        'type'           => 'appointment_reminder_1day',
                        'sender_id'      => null,
                        'recipient_type' => 'specific_users',
                        'recipient_ids'  => json_encode([$patient->id]),
                        'scheduled_at'   => now(),
                        'sent_at'        => now(),
                        'status'         => 'sent',
                    ]);

                    $this->info("✅ Gửi nhắc lịch hẹn ngày mai (ID {$appointment->id})");
                }
            }
        }

        return Command::SUCCESS;
    }

    public function schedule(Schedule $schedule): void
    {
        $schedule->command(static::class)->everyFiveMinutes();
    }
}
