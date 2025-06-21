<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    protected $fillable = [
        'type',
        'date',
        'total_doctors',
        'total_patients',
        'total_appointments',
        'total_revenue',
        'appointments_pending',
        'appointments_completed',
        'appointments_cancelled',
    ];
}