<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active'
    ];

    protected $with = ['doctors', 'rooms', 'services'];

    // Scope phòng ban đang hoạt động
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    // Quan hệ
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class, 'department_id');
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'department_id');
    }

    // Tự động tạo slug
    protected static function booted()
    {
        static::creating(function ($department) {
            $department->slug = Str::slug($department->name);
        });

        static::updating(function ($department) {
            $department->slug = Str::slug($department->name);
        });
    }

    public function activeServices()
    {
        return $this->hasMany(Service::class)->where('status', 'active');
    }
}
