<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskLog extends Model
{
    use HasFactory;

    /**
     * Các trường cho phép ghi dữ liệu hàng loạt
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'task_id',
        'changed_by',
        'from_status',
        'to_status',
        'changed_at',
    ];

    /**
     * Quan hệ: log này thuộc về một công việc (task)
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Quan hệ: người thay đổi trạng thái
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
