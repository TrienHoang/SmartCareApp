<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description'
    ];

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class, 'department_id');
    }
}

