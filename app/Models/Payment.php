<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'promotion_id',
        'amount',
        'payment_method',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    public function paymentHistories(): HasMany
    {
        return $this->hasMany(PaymentHistory::class);
    }

    // Accessor: lấy thông tin bệnh nhân thông qua cuộc hẹn
    public function getPatientAttribute()
    {
        return optional($this->appointment)->patient;
    }

    // Accessor: lấy bác sĩ
    public function getDoctorAttribute()
    {
        return optional($this->appointment)->doctor;
    }

    // Accessor: lấy dịch vụ
    public function getServiceAttribute()
    {
        return optional($this->appointment)->service;
    }
}
