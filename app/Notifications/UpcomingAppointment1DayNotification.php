<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UpcomingAppointment1DayNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nhắc nhở lịch hẹn ngày mai')
            ->greeting('Xin chào ' . $notifiable->full_name)
            ->line('Bạn có lịch hẹn vào ngày mai với bác sĩ ' . $this->appointment->doctor->user->full_name)
            ->line('Thời gian: ' . $this->appointment->appointment_time->format('H:i d/m/Y'))
            ->action('Xem chi tiết', url('/appointments/' . $this->appointment->id))
            ->line('Vui lòng đến đúng giờ để không bị gián đoạn.');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Lịch hẹn ngày mai',
            'message' => 'Bạn có lịch hẹn với bác sĩ ' . $this->appointment->doctor->user->full_name .
                ' vào lúc ' . $this->appointment->appointment_time->format('H:i d/m/Y'),
            'appointment_id' => $this->appointment->id,
        ];
    }
}
