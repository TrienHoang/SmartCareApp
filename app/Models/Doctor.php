<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'department_id',
        'specialization',
        'biography',
    ];

    /**
     * Quan hệ: Doctor thuộc về một User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quan hệ: Doctor thuộc về một Room
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Quan hệ: Doctor thuộc về một Department
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
     public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
        public function treatmentPlans()
    {
        return $this->hasMany(TreatmentPlan::class);
    }
    
}

