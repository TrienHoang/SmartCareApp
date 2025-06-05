<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_record_id',
        'prescribed_at',
        'notes'
    ];
    public $timestamps = false;
    public function items()
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    public function getFormattedDateAttribute()
    {
        return \Carbon\Carbon::parse($this->prescribed_at)->format('d/m/Y');
    }
}
