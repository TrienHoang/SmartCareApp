<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'service_cate_id',
        'name',
        'description',
        'price',
        'duration',
        'status',
        'content',
        'min_booking_hours',
        'department_id',
        'image',
    ];

    protected $dates = ['deleted_at'];

    /**
     * Quan hệ: Service thuộc về một danh mục dịch vụ
     */
    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_cate_id');
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_service')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    public function room (){
        return $this->belongsTo(Room::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'doctor_service', 'service_id', 'doctor_id');
    }
}
