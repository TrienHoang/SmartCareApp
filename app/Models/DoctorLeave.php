<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class DoctorLeave extends Model
{
    use HasFactory;

    // Nếu bạn muốn theo dõi thời gian tạo/sửa
    public $timestamps = true;

    protected $fillable = [
        'doctor_id',
        'start_date',
        'end_date',
        'reason',
        'approved',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved' => 'boolean',
    ];

    // --------------------
    // Relationships
    // --------------------

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class); // nếu không dùng có thể xóa
    }

    // --------------------
    // Custom Methods
    // --------------------

    /**
     * Kiểm tra một ngày cụ thể có nằm trong khoảng nghỉ không
     */
    public function includesDate($date): bool
    {
        $date = Carbon::parse($date)->toDateString();
        return $this->start_date->toDateString() <= $date &&
               $this->end_date->toDateString() >= $date;
    }

    /**
     * Kiểm tra xem lịch nghỉ này có áp dụng hôm nay không
     */
    public function isToday(): bool
    {
        return $this->includesDate(Carbon::today());
    }

    /**
     * Lấy số ngày nghỉ (bao gồm cả ngày bắt đầu và kết thúc)
     */
    public function totalDays(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    /**
     * Chuỗi mô tả lịch nghỉ (để hiển thị)
     */
    public function formattedRange(): string
    {
        return 'Từ ' . $this->start_date->format('d/m/Y') . ' đến ' . $this->end_date->format('d/m/Y');
    }
}
