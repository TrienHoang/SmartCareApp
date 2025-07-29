<?php

namespace App\Notifications;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentCancelledWithSuggestion extends Notification
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
        return ['mail']; // Bạn có thể thêm 'database' nếu muốn ghi log trong DB
    }

    public function toMail($notifiable)
    {
        $appointmentTime = $this->appointment->appointment_time
            ? Carbon::parse($this->appointment->appointment_time)->format('d/m/Y H:i')
            : 'Không xác định';

        return (new MailMessage)
            ->subject('Cuộc Hẹn Của Bạn Đã Bị Hủy')
            ->greeting('Xin chào ' . ($notifiable->name ?? 'bạn') . ',')
            ->line('Cuộc hẹn của bạn đã bị hủy do bác sĩ nghỉ đột xuất và không có bác sĩ thay thế.')
            ->line('📌 **Chi tiết cuộc hẹn bị hủy:**')
            ->line("🕒 Thời gian: {$appointmentTime}")
            ->line("📋 Ghi chú: " . ($this->appointment->note ?? 'Không có'))
            ->line('Vui lòng đặt lại lịch vào một ngày khác hoặc liên hệ với chúng tôi nếu cần hỗ trợ.')
            ->action('🔁 Đặt Lịch Mới', url('/appointments/create'))
            ->line('Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!');
    }

    public function toArray($notifiable)
    {
        return [
            'appointment_id' => $this->appointment->id,
            'message' => $this->message ?? 'Cuộc hẹn của bạn đã bị hủy. Vui lòng đặt lịch lại.',
            'time' => $this->appointment->appointment_time
                ? Carbon::parse($this->appointment->appointment_time)->format('d/m/Y H:i')
                : 'Không xác định',
        ];
    }
}
