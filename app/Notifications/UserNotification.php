<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification as BaseNotification;

class UserNotification extends BaseNotification implements ShouldQueue // implements ShouldQueue để đẩy vào Queue
{
    use Queueable;

    protected $notificationData; // Dữ liệu từ bảng Notification của admin

    /**
     * Create a new notification instance.
     */
    public function __construct(array $notificationData)
    {
        $this->notificationData = $notificationData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail']; 
    }

    public function toMail(object $notifiable): MailMessage
    {
        // Sử dụng Markdown mail template đã tạo: resources/views/notifications/user_notification.blade.php
        return (new MailMessage)
                    ->subject($this->notificationData['title']) // Tiêu đề email
                    ->markdown('notifications.user_notification', [
                        'content' => $this->notificationData['content'],
                        'user' => $notifiable, 
                    ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'id' => $this->notificationData['id'],
            'title' => $this->notificationData['title'],
            'content' => $this->notificationData['content'],
            'type' => $this->notificationData['type'],
            // Thêm các trường khác bạn muốn lưu trong bảng notifications của Laravel (dành cho người dùng)
        ];
    }
}