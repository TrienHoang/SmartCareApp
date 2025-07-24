<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

protected $fillable = [
    'doctor_id',
    'title',
    'organization', // ✅ Thêm dòng này
    'description',
    'year',
];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
