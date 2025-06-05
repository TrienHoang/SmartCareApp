<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class PrescriptionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'prescription_id', 'medicine_id', 'quantity', 'usage_instructions'
    ];
    public $timestamps = false;
    public function prescription(){
        return $this->belongsTo(Prescription::class);
    }
    public function medicine(){
        return $this->belongsTo(Medicine::class);
    }
}

