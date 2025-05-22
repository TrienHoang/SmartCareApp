<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id', 'symptoms', 'diagnosis',
        'treatment', 'notes', 'created_at'
    ];
    public $timestamps = false;
}

