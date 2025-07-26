<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'status'];


    /**
     * Một danh mục có nhiều dịch vụ
     */
    public function services()
    {
        return $this->hasMany(Service::class, 'service_cate_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
