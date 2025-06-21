<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}