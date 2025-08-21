<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Appointment;
use Carbon\Carbon;

class UpcomingAppointmentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    // Kênh gửi notification (database)
    public function via($notifiable)
    {
        return ['database'];
    }

    // Lưu dữ liệu xuống bảng notifications
    public function toDatabase($notifiable)
    {
        return [
            'title'   => 'Lịch hẹn sắp đến giờ (chưa xác nhận)',
            'content' => sprintf(
                'Lịch hẹn #%d - BN: %s - BS: %s - %s. Vui lòng xác nhận.',
                $this->appointment->id,
                optional($this->appointment->patient)->full_name ?? 'N/A',
                optional(optional($this->appointment->doctor)->user)->full_name ?? 'N/A',
                Carbon::parse($this->appointment->appointment_time)->format('d/m/Y H:i')
            ),
            'appointment_id' => $this->appointment->id,
        ];
    }
}
