<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Appointment;

class AppointmentCancelledWithSuggestion extends Notification
{
    use Queueable;

    public $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // nếu dùng thông báo lưu vào DB
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Cuộc hẹn bị hủy')
            ->line('Cuộc hẹn của bạn đã bị hủy do bác sĩ nghỉ đột xuất.')
            ->line('Hiện tại không có bác sĩ nào thay thế.')
            ->line('Bạn có thể đặt lại lịch vào thời gian khác.')
            ->action('Đặt lịch lại', url('/appointments/create')) // sửa lại URL nếu cần
            ->line('Xin lỗi vì sự bất tiện!');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Cuộc hẹn bị hủy do bác sĩ nghỉ đột xuất. Vui lòng đặt lại lịch hẹn.',
            'appointment_id' => $this->appointment->id,
        ];
    }
}
