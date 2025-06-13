<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // Quan trọng: Để đẩy việc gửi vào hàng đợi (Queue)
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification as BaseNotification; // Đổi tên để tránh xung đột với class Notification của Admin

class AdminPanelNotification extends BaseNotification implements ShouldQueue // Implements ShouldQueue
{
    use Queueable; // Sử dụng Queueable trait để notification được gửi trong hàng đợi

    protected $notificationData; // Chứa dữ liệu của thông báo từ bảng admin_notifications

    /**
     * Create a new notification instance.
     *
     * @param array $notificationData Dữ liệu thông báo từ CustomNotification model của Admin
     */
    public function __construct(array $notificationData)
    {
        // Gán dữ liệu thông báo được truyền vào
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
        // Nếu bạn muốn thêm SMS, bạn sẽ cần cấu hình Twilio/Nexmo và thêm 'vonage'/'twilio' vào đây
        // return ['database', 'mail', 'vonage'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Sử dụng template markdown đã tạo cho email
        return (new MailMessage)
                    ->subject($this->notificationData['title']) // Tiêu đề email là tiêu đề thông báo từ Admin
                    ->markdown('notifications.admin_panel_notification', [
                        'notification' => $this->notificationData, // Truyền toàn bộ dữ liệu thông báo
                        'user' => $notifiable, // Truyền đối tượng người nhận (User, Doctor, Patient)
                    ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        // Dữ liệu này sẽ được lưu vào cột `data` (JSON) trong bảng `notifications` của Laravel
        return [
            'id_from_admin_notification' => $this->notificationData['id'], // Lưu ID của thông báo gốc từ bảng admin_notifications
            'title' => $this->notificationData['title'],
            'content' => $this->notificationData['content'],
            'type' => $this->notificationData['type'],
            // Bạn có thể thêm các trường khác nếu muốn hiển thị trên giao diện người dùng
            'sent_by' => 'Admin', // Hoặc tên admin nếu bạn muốn phức tạp hơn
        ];
    }

}