<?php

namespace App\Notifications;

use App\Models\DoctorLeave;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DoctorLeaveCreated extends Notification
{
    use Queueable;

    protected $leave;
    protected $isUpdate;

    /**
     * @param DoctorLeave $leave
     * @param bool $isUpdate - true nếu là cập nhật
     */
    public function __construct(DoctorLeave $leave, $isUpdate = false)
    {
        $this->leave = $leave;
        $this->isUpdate = $isUpdate;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $doctor = $this->leave->doctor;

        $subject = $this->isUpdate
            ? 'Thông báo: Bác sĩ cập nhật đơn nghỉ'
            : 'Thông báo: Bác sĩ đăng ký lịch nghỉ';

        $line1 = $this->isUpdate
            ? 'Bác sĩ "' . $doctor->user->full_name . '" vừa cập nhật lại lịch nghỉ.'
            : 'Bác sĩ "' . $doctor->user->full_name . '" vừa đăng ký lịch nghỉ.';

        $line2 = 'Thời gian: ' . $this->leave->start_date . ' đến ' . $this->leave->end_date;
        $line3 = 'Lý do: ' . $this->leave->reason;

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Xin chào Admin,')
            ->line($line1)
            ->line($line2)
            ->line($line3)
            ->action('Xem chi tiết', url('/admin/doctor-leaves/' . $this->leave->id))
            ->line($this->isUpdate
                ? 'Vui lòng kiểm tra lại nội dung đơn nghỉ vừa được chỉnh sửa.'
                : 'Vui lòng xem xét và duyệt yêu cầu này.');
    }
    
}
