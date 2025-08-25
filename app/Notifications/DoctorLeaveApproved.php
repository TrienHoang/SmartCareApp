<?php

namespace App\Notifications;

use App\Models\DoctorLeave;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DoctorLeaveApproved extends Notification
{
    use Queueable;

    protected $leave;

    public function __construct(DoctorLeave $leave)
    {
        $this->leave = $leave;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Lịch nghỉ đã được duyệt')
            ->greeting('Xin chào ' . $notifiable->full_name . ',')
            ->line('Yêu cầu nghỉ phép từ ' . $this->leave->start_date . ' đến ' . $this->leave->end_date . ' của bạn đã được duyệt.')
            ->line('Lý do: ' . $this->leave->reason)
            ->action('Xem chi tiết', url('/doctor/leaves/' . $this->leave->id))
            ->line('Chúc bạn một kỳ nghỉ vui vẻ và hợp lý!');
    }
}
