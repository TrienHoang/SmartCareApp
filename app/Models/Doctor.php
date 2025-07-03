<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'room_id',
        'specialization',
        'biography',
    ];

    // --------------------
    // Relationships
    // --------------------

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function leaves()
    {
        return $this->hasMany(DoctorLeave::class);
    }

    // --------------------
    // Custom Methods
    // --------------------

    /**
     * Kiểm tra bác sĩ có đang nghỉ hôm nay không (đã được duyệt).
     */
    public function isOnLeaveToday()
    {
        $today = Carbon::today()->toDateString();

        return $this->leaves()
            ->where('approved', true)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->exists();
    }

    /**
     * Lấy danh sách ngày nghỉ hiện tại (đang diễn ra).
     */
    public function currentLeave()
    {
        $today = Carbon::today()->toDateString();

        return $this->leaves()
            ->where('approved', true)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->first(); // hoặc ->get() nếu bạn muốn danh sách
    }
}
