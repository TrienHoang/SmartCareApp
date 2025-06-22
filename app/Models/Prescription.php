<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prescription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'medical_record_id',
        'prescribed_at',
        'notes'
    ];
    public $timestamps = false;
    protected $casts = [
        'prescribed_at' => 'datetime'
    ];
    public function items()
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function prescriptionItems()
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    public function getFormattedDateAttribute()
    {
        return \Carbon\Carbon::parse($this->prescribed_at)->format('d/m/Y');
    }

    public function getTotalMedicineTypesAttribute()
    {
        return $this->prescriptionItems->count();
    }

    public function getTotalQuantityAttribute()
    {
        return $this->prescriptionItems->sum('quantity');
    }

    public function histories(){
        return $this->hasMany(PrescriptionHistory::class)->latest('changed_at');
    }
}
