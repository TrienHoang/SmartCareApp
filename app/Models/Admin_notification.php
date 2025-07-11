<?php

namespace App\Models;

use App\Notifications\LateNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Berkayk\OneSignal\OneSignalFacade as OneSignal;
use App\Models\User;

class Admin_notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'type',
        'sender_id',
        'recipient_type',
        'recipient_ids',
        'scheduled_at',
        'sent_at',
        'status',
    ];

    protected $casts = [
        'recipient_ids' => 'array', // Đây là dòng quan trọng nhất
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    // Có thể định nghĩa mối quan hệ với Admin nếu cần
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }



    public static function sendToUser(User $user, string $title, string $message): void
    {
        // 1️⃣ Lưu vào bảng admin_notifications
        self::create([
            'title' => $title,
            'content' => "<p>{$message}</p>",
            'recipient_type' => 'specific_users',
            'recipient_ids' => [$user->id],
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        // 2️⃣ Gửi email (hoặc database + broadcast)
        $user->notify(new LateNotification($title, $message));
    }

public function getDisplayRecipients()
{
    // recipient_ids đã được cast thành mảng, nên chỉ cần kiểm tra null
    $recipientIds = $this->recipient_ids ?? [];

    if ($this->recipient_type === 'specific_users' && !empty($recipientIds)) {
        return User::whereIn('id', $recipientIds)->pluck('full_name')->toArray();
    } elseif ($this->recipient_type === 'roles' && !empty($recipientIds)) {
        return Role::whereIn('id', $recipientIds)->pluck('name')->toArray();
    } elseif ($this->recipient_type === 'all') {
        return ['Tất cả người dùng'];
    }

    return ['Không xác định'];
}
}
