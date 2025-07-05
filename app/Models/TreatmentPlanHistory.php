<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentPlanHistory extends Model
{
    use HasFactory;

    // Tắt timestamps (created_at, updated_at) vì đã có changed_at
    public $timestamps = false;

    protected $fillable = [
        'treatment_plan_id',
        'changed_by_id',
        'change_description',
        'old_data',
        'new_data',
        'changed_at',
    ];

    /**
     * Lấy người đã thực hiện thay đổi.
     */
    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by_id');
    }
}
