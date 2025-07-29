<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;


class NotifyAdminWorkingSchedule extends Notification
{
    use Queueable;

    protected $schedule;
    protected $action; // 'Tạo mới' hoặc 'Cập nhật'

    public function __construct($schedule, $action = 'Tạo mới')
    {
        $this->schedule = $schedule;
        $this->action = $action;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Lịch làm việc bác sĩ - ' . $this->action)
            ->greeting('Xin chào Admin,')
            ->line("Bác sĩ {$this->schedule->doctor->name} vừa {$this->action} lịch làm việc.")
            ->line("Ngày: {$this->schedule->day}")
            ->line("Ca: {$this->schedule->shift->name}")
            ->line("Phòng: {$this->schedule->room->name}")
            ->line('Vui lòng đăng nhập hệ thống để xem chi tiết.')
            ->action('Xem lịch làm việc', route('admin.working_schedules.index'))
            ->line('Cảm ơn bạn đã sử dụng hệ thống!');
    }
}
