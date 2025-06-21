<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DoctorLeave extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'doctor_id',
        'start_date',
        'end_date',
        'reason',
        'created_at',
        'approved'
    ];
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
