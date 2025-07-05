<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TreatmentPlan extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'patient_id',
        'doctor_id',
        'plan_title',
        'total_estimated_cost',
        'notes',
        'diagnosis',
        'goal',
        'start_date',
        'end_date',
        'status',
    ];
       protected $casts = [
        'start_date' => 'datetime', 
        'end_date' => 'datetime',   
    ];

    public $timestamps = false;

    // Quan hệ với Patient
    public function patient()
    {
        return $this->belongsTo(User::class); // Giả sử bạn có Patient model
    }

    // Quan hệ với Doctor (User)
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id'); // Giả sử bác sĩ là User
    }

    // Quan hệ với TreatmentPlanItems (One-to-Many)
    public function items()
    {
        return $this->hasMany(TreatmentPlanItem::class);
    }

    // Quan hệ với TreatmentPlanHistories (One-to-Many)
    public function histories()
    {
        return $this->hasMany(TreatmentPlanHistory::class);
    }

    public function getBadgeClassForStatus($status = null)
    {
        $statusToUse = $status ?? $this->status;
        switch ($statusToUse) {
            case 'draft': return 'bg-dark';
            case 'active': return 'bg-info';
            case 'completed': return 'bg-success';
            case 'paused': return 'bg-warning';
            case 'cancelled': return 'bg-danger';
            default: return 'bg-secondary'; // Fallback
        }
    }
}