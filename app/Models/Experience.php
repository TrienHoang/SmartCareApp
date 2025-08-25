<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

protected $fillable = [
    'doctor_id',
    'position',
    'institution', // ✅ dùng đúng tên cột trong migration
    'start_year',
    'end_year',
];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
