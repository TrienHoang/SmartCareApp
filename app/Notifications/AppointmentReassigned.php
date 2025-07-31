<?php

namespace App\Notifications;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentReassigned extends Notification
{
    use Queueable;

    protected $appointment;
    protected $message;

    public function __construct(Appointment $appointment, $message = null)
    {
        $this->appointment = $appointment;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['mail']; // Bạn có thể thêm 'database' nếu cần
    }

    public function toMail($notifiable)
    {
        $doctor = $this->appointment->doctor;
        $doctorName = $doctor && $doctor->user ? $doctor->user->full_name : 'Không xác định';

        $appointmentTime = $this->appointment->appointment_time
            ? Carbon::parse($this->appointment->appointment_time)->format('d/m/Y H:i')
            : 'Không xác định';

        return (new MailMessage)
            ->subject('Cuộc Hẹn Của Bạn Đã Được Chuyển Sang Bác Sĩ Khác')
            ->greeting('Xin chào ' . ($notifiable->name ?? 'bạn') . ',')
            ->line('Cuộc hẹn của bạn đã được chuyển sang một bác sĩ khác do bác sĩ ban đầu nghỉ đột xuất.')
            ->line('Chi tiết cuộc hẹn:')
            ->line("👨‍⚕️ Bác sĩ: {$doctorName}")
            ->line("🕒 Thời gian: {$appointmentTime}")
            ->line("📌 Ghi chú: " . ($this->appointment->note ?? 'Không có'))
            ->action('Xem Cuộc Hẹn', url('/appointments/' . $this->appointment->id))
            ->line('Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!');
    }

    public function toArray($notifiable)
    {
        $doctorName = $this->appointment->doctor && $this->appointment->doctor->user
            ? $this->appointment->doctor->user->full_name
            : 'Không xác định';

        return [
            'appointment_id' => $this->appointment->id,
            'message' => 'Cuộc hẹn của bạn đã được chuyển sang bác sĩ ' . $doctorName . '.',
            'time' => $this->appointment->appointment_time
                ? Carbon::parse($this->appointment->appointment_time)->format('d/m/Y H:i')
                : 'Không xác định',
        ];
    }
}
