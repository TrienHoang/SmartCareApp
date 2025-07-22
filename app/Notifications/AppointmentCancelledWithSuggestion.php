<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentCancelledWithSuggestion extends Notification
{
    use Queueable;

    protected $appointment;

    public function __construct($appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        return ['mail']; // You can add other channels like 'database' or 'sms' if needed
    }

    public function toMail($notifiable)
    {
        $appointmentTime = Carbon::parse($this->appointment->appointment_time)->format('d/m/Y H:i');

        return (new MailMessage)
            ->subject('Cuộc Hẹn Của Bạn Đã Bị Hủy')
            ->greeting('Xin chào ' . $notifiable->name . ',')
            ->line('Cuộc hẹn của bạn đã bị hủy do bác sĩ nghỉ đột xuất và không có bác sĩ thay thế.')
            ->line("**Chi tiết cuộc hẹn bị hủy:**")
            ->line("Thời gian: {$appointmentTime}")
            ->line("Ghi chú: {$this->appointment->note}")
            ->line('Vui lòng đặt lại lịch vào một ngày khác.')
            ->action('Đặt Lịch Mới', url('/appointments/create'))
            ->line('Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!');
    }

    public function toArray($notifiable)
    {
        return [
            'appointment_id' => $this->appointment->id,
            'message' => 'Cuộc hẹn của bạn đã bị hủy. Vui lòng đặt lịch vào ngày khác.',
            'time' => Carbon::parse($this->appointment->appointment_time)->format('d/m/Y H:i'),
        ];
    }
}
