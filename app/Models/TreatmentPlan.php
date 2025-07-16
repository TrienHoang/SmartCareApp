<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TreatmentPlan extends Model
{
    protected $fillable = ['patient_id', 'doctor_id', 'plan_title', 'total_estimated_cost', 'notes', 'created_at'];
    public $timestamps = false;

    public function patient() {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor() {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function histories() {
        return $this->hasMany(TreatmentHistory::class);
    }
}

