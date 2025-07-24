<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
class Contact extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'phone', 'message', 'title', 'status'];
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'unread' => 'Chưa đọc',
            'read' => 'Đã đọc',
            'replied' => 'Đã trả lời',
            default => 'Không xác định',
        };
    }

    public function routeNotificationForMail(): string
{
    return $this->email;
}

}
