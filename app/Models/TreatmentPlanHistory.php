<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentPlanHistory extends Model
{
    use HasFactory;

    // Tắt timestamps nếu bạn không dùng created_at/updated_at tự động của Laravel
    public $timestamps = false;
    protected $dateFormat = 'Y-m-d H:i:s'; // Định dạng ngày giờ nếu cần

    protected $fillable = [
        'treatment_plan_id',
        'changed_by_id',
        'change_description',
        'old_data',
        'new_data',
        'changed_at',
    ];

    protected $casts = [
        'old_data' => 'array', // Cast JSON sang array
        'new_data' => 'array', // Cast JSON sang array
        'changed_at' => 'datetime',
    ];

    // Quan hệ với TreatmentPlan (Many-to-One)
    public function treatmentPlan()
    {
        return $this->belongsTo(TreatmentPlan::class);
    }

    // Quan hệ với User (người thay đổi)
    public function changedBy()
    {
        return $this->belongsTo(Doctor::class, 'changed_by_id'); // Giả sử người thay đổi là User
    }
}