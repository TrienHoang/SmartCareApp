<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_upload_id',
        'action',
        'timestamp'
    ];

    protected $casts = [
        'timestamp' => 'datetime'
    ];

    public $timestamps = false; // Không sử dụng created_at, updated_at

    /**
     * Relationship với FileUpload
     */
    public function fileUpload()
    {
        return $this->belongsTo(FileUpload::class);
    }

    /**
     * Lấy tên action tiếng Việt
     */
    public function getActionNameAttribute()
    {
        $actions = [
            'created' => 'Tạo mới',
            'uploaded' => 'Tải lên',
            'downloaded' => 'Tải xuống',
            'viewed' => 'Xem',
            'updated' => 'Cập nhật',
            'category_updated' => 'Cập nhật danh mục',
            'deleted' => 'Xóa',
            'restored' => 'Khôi phục'
        ];

        return $actions[$this->action] ?? $this->action;
    }

    /**
     * Lấy CSS class cho action
     */
    public function getActionClassAttribute()
    {
        $classes = [
            'created' => 'text-blue-600 bg-blue-100',
            'uploaded' => 'text-green-600 bg-green-100',
            'downloaded' => 'text-purple-600 bg-purple-100',
            'viewed' => 'text-gray-600 bg-gray-100',
            'updated' => 'text-orange-600 bg-orange-100',
            'category_updated' => 'text-orange-600 bg-orange-100',
            'deleted' => 'text-red-600 bg-red-100',
            'restored' => 'text-emerald-600 bg-emerald-100'
        ];

        return $classes[$this->action] ?? 'text-gray-600 bg-gray-100';
    }

    /**
     * Scope: Lọc theo action
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope: Lọc theo khoảng thời gian
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('timestamp', [$startDate, $endDate]);
    }

    /**
     * Scope: Lấy history gần đây
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('timestamp', '>=', now()->subDays($days));
    }
}