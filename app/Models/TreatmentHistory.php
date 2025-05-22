<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class TreatmentHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'doctor_id', 'treatment_description',
        'treatment_date', 'created_at'
    ];
    public $timestamps = false;
}

