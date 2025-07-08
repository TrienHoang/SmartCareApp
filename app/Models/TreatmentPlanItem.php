<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentPlanItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'treatment_plan_id',
        'title',
        'description',
        'expected_start_date',
        'expected_end_date',
        'actual_end_date',
        'frequency',
        'status',
        'notes',
        'service_id',
    ];
    protected $casts = [
        // Hoặc 'date' tùy thuộc vào kiểu dữ liệu trong DB
         'expected_start_date' => 'datetime',
        'expected_end_date' => 'datetime',
        'actual_end_date' => 'datetime',
    ];
    /**
     * Lấy kế hoạch điều trị mà bước này thuộc về.
     */
    public function plan()
    {
        return $this->belongsTo(TreatmentPlan::class, 'treatment_plan_id');
    }
}