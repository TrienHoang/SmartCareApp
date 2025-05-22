<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'room_id', 'department_id', 'specialization', 'biography'
    ];
    public $timestamps = true;
}

