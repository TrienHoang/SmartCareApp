<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id', 'symptoms', 'diagnosis',
        'treatment', 'notes', 'created_at'
    ];
    protected $casts = [
        'created_at' => 'datetime'
    ];
    public $timestamps = false;
    public function appointment()
{
    return $this->belongsTo(Appointment::class);
}
 public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
public function getCodeAttribute()
{
    return 'SM-' . str_pad($this->id, 2, '0', STR_PAD_LEFT);
}
}

