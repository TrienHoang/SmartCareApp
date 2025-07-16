<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TreatmentHistory extends Model
{
    protected $fillable = ['patient_id', 'doctor_id', 'treatment_description', 'treatment_date', 'created_at'];
    public $timestamps = false;

    public function patient() {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor() {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
}

