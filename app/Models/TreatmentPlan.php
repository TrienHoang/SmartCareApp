<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class TreatmentPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'doctor_id', 'plan_title',
        'total_estimated_cost', 'notes', 'created_at'
    ];
    public $timestamps = false;
}

