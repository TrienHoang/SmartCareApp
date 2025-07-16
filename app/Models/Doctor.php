<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'room_id',
        'specialization',
        'biography',
        'status', // Thêm trường status
    ];

    // --------------------
    // Relationships
    // --------------------

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'user_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }


    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function file_uploads()
    {
        return $this->hasMany(FileUpload::class);
    }
    public function treatmentPlans()
    {
        return $this->hasMany(TreatmentPlan::class);
    }


    public function services()
    {
        return $this->belongsToMany(Service::class, 'doctor_service');
    }

    public function workingSchedules()
    {
        return $this->hasMany(WorkingSchedule::class);
    }

    public function leaves()
    {
        return $this->hasMany(DoctorLeave::class)
            ->where('approved', true)
            ->whereNull('deleted_at');
    }
}
