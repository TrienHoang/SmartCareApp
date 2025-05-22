<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class AppointmentLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id', 'changed_by',
        'status_before', 'status_after',
        'change_time', 'note'
    ];
    public $timestamps = false;
}

