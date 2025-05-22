<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'doctor_id', 'service_id',
        'appointment_time', 'status', 'reason',
        'cancel_reason', 'created_at', 'updated_at'
    ];
    public $timestamps = true;
}
