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
            ->subject('Phản hồi từ phòng khám')
            ->greeting('Xin chào ' . $this->contact->name . '!')
            ->line('Chúng tôi đã nhận được yêu cầu liên hệ của bạn.')
            ->line('Dưới đây là phản hồi từ chúng tôi:')
            ->line('---')
            ->line($this->replyContent)
            ->line('---')
            ->line('Nếu bạn cần thêm hỗ trợ, đừng ngần ngại liên hệ lại với chúng tôi.')
            ->salutation('Trân trọng, Phòng khám');
    }
}
