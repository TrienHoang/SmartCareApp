<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Payment;

class PaymentHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'amount',
        'payment_method',
        'payment_date',
        'note',  // Phải có dòng này để lưu mass assign
    ];

    public $timestamps = false;

    

    // 👉 Thêm dòng này để fix lỗi format()
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
}
