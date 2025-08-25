<?php

namespace App\Models;

use App\Notifications\CustomResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

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
        'status',
        'description',
        'education',
        'email_verified_at',

    ];

    public $timestamps = true;

    protected $casts = [
        'date_of_birth' => 'date',
        'email_verified_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // 🔗 Quan hệ Role (nếu không dùng Spatie thì mới cần)
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // 🔐 Dùng khi không dùng trực tiếp từ Spatie
    public function permissions()
    {
        return $this->role?->permissions();
    }

    public function hasPermission($permissionName): bool
    {
        return $this->permissions()->where('name', $permissionName)->exists();
    }

    // 🔄 Gửi link đặt lại mật khẩu
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token, $this->email));
    }

    // 🩺 Liên kết với bảng bác sĩ
    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'user_id', 'id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function isAdmin()
    {
        return $this->role && $this->role->name === '1';
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }
    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'user_id');
    }
}
