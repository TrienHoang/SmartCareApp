<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TreatmentPlan extends Model
{
    use HasFactory, SoftDeletes;


    // Cho phép mass assignment cho các trường này
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
        'start_date' => 'datetime', // Hoặc 'date' nếu bạn chỉ quan tâm đến ngày
        'end_date' => 'datetime',   // Hoặc 'date' nếu bạn chỉ quan tâm đến ngày
        // created_at và updated_at đã được Laravel tự động cast, không cần thêm ở đây
    ];

    /**
     * Lấy thông tin bệnh nhân của kế hoạch này.
     */
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Lấy thông tin bác sĩ của kế hoạch này.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    /**
     * Lấy tất cả các bước điều trị của kế hoạch này.
     */
    public function items()
    {
        return $this->hasMany(TreatmentPlanItem::class);
    }

    /**
     * Lấy lịch sử thay đổi của kế hoạch này.
     */
    public function histories()
    {
        return $this->hasMany(TreatmentPlanHistory::class)->latest('changed_at');
    }

    public function treatmentPlanItems()
    {
        return $this->hasMany(TreatmentPlanItem::class);
    }
}
