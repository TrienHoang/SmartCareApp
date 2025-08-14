<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'keyword',
        'response',
        'suggested_services',
        'is_active',
        'priority'
    ];

    protected $casts = [
        'suggested_services' => 'array',
        'is_active' => 'boolean',
        'priority' => 'integer'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByPriority($query)
    {
        return $query->orderBy('priority', 'desc');
    }

    public static function findByKeyword($message)
    {
        return self::active()
            ->byPriority()
            ->where('keyword', 'LIKE', '%' . strtolower($message) . '%')
            ->first();
    }

    public function getSuggestedServicesArrayAttribute()
    {
        return $this->suggested_services ?: [];
    }

    public function hasSuggestedServices()
    {
        $services = $this->suggested_services ?: [];
        return !empty($services) && !empty(array_filter($services));
    }
}