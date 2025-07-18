<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;

        protected $table = 'educations'; 


protected $fillable = [
    'doctor_id',
    'school',       // ✅ đúng với migration
    'degree',
    'start_year',
    'end_year',
];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
