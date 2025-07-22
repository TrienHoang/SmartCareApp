<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentReassigned extends Notification
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
        $doctor = $this->appointment->doctor; // Assumes Appointment has a doctor relationship
        $appointmentTime = Carbon::parse($this->appointment->appointment_time)->format('d/m/Y H:i');

        return (new MailMessage)
            ->subject('Cuộc Hẹn Của Bạn Đã Được Chuyển Sang Bác Sĩ Khác')
            ->greeting('Xin chào ' . $notifiable->name . ',')
            ->line('Cuộc hẹn của bạn đã được chuyển sang một bác sĩ khác do bác sĩ ban đầu nghỉ đột xuất.')
            ->line("**Chi tiết cuộc hẹn:**")
            ->line("Bác sĩ: {$doctor->user->full_name}")
            ->line("Thời gian: {$appointmentTime}")
            ->line("Ghi chú: {$this->appointment->note}")
            ->action('Xem Cuộc Hẹn', url('/appointments/' . $this->appointment->id))
            ->line('Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!');
    }

    public function toArray($notifiable)
    {
        return [
            'appointment_id' => $this->appointment->id,
            'message' => 'Cuộc hẹn của bạn đã được chuyển sang bác sĩ ' . $this->appointment->doctor->user->full_name . '.',
            'time' => Carbon::parse($this->appointment->appointment_time)->format('d/m/Y H:i'),
        ];
    }
}
