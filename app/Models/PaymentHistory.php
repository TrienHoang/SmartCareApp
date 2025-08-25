<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'amount',
        'payment_method',
        'payment_date',
    ];

    public $timestamps = false;

    protected $casts = [
        'payment_date' => 'datetime',
    ];

    /**
     * Quan hệ: mỗi PaymentHistory thuộc về một Payment
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    // Relationships thông qua Payment
    public function appointment()
    {
        return $this->hasOneThrough(Appointment::class, Payment::class, 'id', 'id', 'payment_id', 'appointment_id');
    }

    public function order()
    {
        return $this->payment->appointment->order ?? null;
    }
}