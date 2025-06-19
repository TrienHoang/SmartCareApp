<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_cate_id',
        'name',
        'description',
        'price',
        'duration',
        'status'
    ];

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_cate_id');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_service')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }

}
