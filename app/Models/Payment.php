<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // Nếu tên bảng đúng là "payments" thì không cần khai báo
    // protected $table = 'payments';

    protected $fillable = [
        'appointment_id',
        'promotion_id',
        'amount',
        'payment_method',
        'status',
        'paid_at',
    ];

    // Nếu muốn dùng Carbon cho paid_at
    protected $dates = [
        'paid_at',
    ];

    // Quan hệ với appointment
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    // Quan hệ với promotion (có thể null)
    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }
}
