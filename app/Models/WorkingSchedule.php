<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkingSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['doctor_id', 'day_of_week', 'start_time', 'end_time', 'day', 'room_id'];
    public $timestamps = false;
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
