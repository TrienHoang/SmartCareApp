<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; 
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification as BaseNotification; 

class AdminPanelNotification extends BaseNotification implements ShouldQueue 
{
    use Queueable; 

    protected $notificationData; // Chứa dữ liệu của thông báo từ bảng admin_notifications

    /**
     * Create a new notification instance.
     *
     * @param array $notificationData Dữ liệu thông báo từ CustomNotification model của Admin
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
        // Gửi thông báo qua kênh database (hiển thị trên web) và kênh email
        return ['database', 'mail'];

    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject($this->notificationData['title']) 
                    ->markdown('notifications.admin_panel_notification', [
                        'notification' => $this->notificationData,
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
            'sent_by' => 'Admin',
        ];
    }

}