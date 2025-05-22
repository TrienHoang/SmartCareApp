<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class PaymentHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id', 'amount',
        'payment_method', 'payment_date'
    ];
    public $timestamps = false;
}

