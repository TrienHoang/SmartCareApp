<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'patient_id',
        'doctor_id',
        'service_id',
        'rating',
        'comment',
        'is_visible',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'rating' => 'integer',
    ];

    /**
     * Quan hệ: Người đánh giá (bệnh nhân)
     */
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Quan hệ: Bác sĩ được đánh giá
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    /**
     * Quan hệ: Dịch vụ đã sử dụng
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    /**
     * Quan hệ: Lịch hẹn liên quan
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }
}
