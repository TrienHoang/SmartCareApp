<?php
namespace App\Models;

use App\Notifications\CustomResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory,Notifiable;

    protected $fillable = [
        'username', 'password', 'full_name', 'email' , 'phone', 'gender', 'date_of_birth',
        'address', 'role_id', 'avatar'
    ];

    public $timestamps = true;

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function sendPasswordResetNotification($token)
{
    $this->notify(new CustomResetPassword($token, $this->email));
}
}
