<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'department_id', 'description','status'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
