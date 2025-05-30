<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ServiceCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name, description'];
    public $timestamps = true;
}
