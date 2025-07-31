<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ContactReplyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $replyContent;
    protected $contact;

    public function __construct($contact, $replyContent)
    {
        $this->contact = $contact;
        $this->replyContent = $replyContent;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Phản hồi từ Phòng khám SmartCare')
            ->greeting('Kính chào ' . $this->contact->name . ',')
            ->line('Cảm ơn bạn đã liên hệ với Phòng khám SmartCare.')
            ->line('Chúng tôi đã tiếp nhận yêu cầu của bạn và xin phản hồi như sau:')
            ->line('――――――――――――――――――――')
            ->line($this->replyContent)
            ->line('――――――――――――――――――――')
            ->line('Nếu bạn còn bất kỳ thắc mắc nào, xin vui lòng phản hồi lại email này hoặc liên hệ trực tiếp với chúng tôi qua thông tin bên dưới.')
            ->line('📞 Hotline: 0123 456 789')
            ->line('📧 Email: smartcare@gmail.com.vn')
            ->salutation('Trân trọng,')
            ->salutation('Đội ngũ Phòng khám SmartCare');
    }
}
