<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DoctorLeave extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'start_date',
        'end_date',
        'reason',
        'created_at',
        'approved'
    ];

    public $timestamps = false;
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
