<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id', 'promotion_id',
        'amount', 'payment_method',
        'status', 'paid_at'
    ];
    public $timestamps = false;
}

