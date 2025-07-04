<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'assigned_to',
        'created_by',
        'status',
        'priority',
        'deadline',
    ];

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function comments()
    {
        return $this->hasMany(\App\Models\TaskComment::class);
    }

    public function logs()
    {
        return $this->hasMany(\App\Models\TaskLog::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
