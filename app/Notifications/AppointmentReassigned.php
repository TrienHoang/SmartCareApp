<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentReassigned extends Notification implements ShouldQueue
{
    use Queueable;

    protected $appointment;

    public function __construct($appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // hoặc 'mail' nếu bạn chỉ dùng email
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Cuộc hẹn của bạn đã được chuyển bác sĩ')
            ->line('Bác sĩ gốc đã nghỉ đột xuất, cuộc hẹn của bạn được chuyển sang bác sĩ khác.')
            ->action('Xem chi tiết', url('/appointments/' . $this->appointment->id));
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Cuộc hẹn được chuyển sang bác sĩ khác do bác sĩ gốc nghỉ đột xuất.',
            'appointment_id' => $this->appointment->id,
        ];
    }
}
