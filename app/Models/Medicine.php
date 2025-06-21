<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'unit',
        'dosage', 'price', 'created_at'
    ];
    public $timestamps = false;

    protected $casts = [
        'price' => 'decimal:2',
        'created_at' => 'datetime'
    ];
    public function prescriptionItems()
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 3, ',', '.') . ' VNĐ';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
