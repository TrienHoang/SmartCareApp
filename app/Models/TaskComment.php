<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model
{
    use HasFactory;

    /**
     * Các field được phép gán dữ liệu hàng loạt
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'task_id',
        'user_id',
        'comment', // ✅ Chỉ để tên cột thôi
    ];

    /**
     * Một comment thuộc về một task
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Một comment được viết bởi một người dùng
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
