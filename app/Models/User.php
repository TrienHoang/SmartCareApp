<?php

namespace App\Models;

use App\Notifications\CustomResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'password',
        'full_name',
        'email',
        'facebook_id',
        'google_id',
        'phone',
        'gender',
        'date_of_birth',
        'address',
        'role_id',
        'avatar',
        'status'
    ];

    public $timestamps = true;

    protected $casts = [
        'date_of_birth' => 'date', // ✅ Giúp dùng format() trong view
        'email_verified_at' => 'datetime',
    ];

    /**
     * Ẩn các cột khi trả về JSON
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }


    public function permissions()
    {
        return $this->role?->permissions(); // dùng được như $user->permissions
    }

    public function hasPermission($permissionName): bool
    {
        return $this->permissions()->where('name', $permissionName)->exists();
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token, $this->email));
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }
}
