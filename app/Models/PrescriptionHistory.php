<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionHistory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'prescription_id',
        'updated_by',
        'old_data',
        'new_data',
        'changed_at',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
        'changed_at' => 'datetime',
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}

