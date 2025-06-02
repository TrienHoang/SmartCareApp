<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use Exception;

class FacebookController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
{
    try {
        $user = Socialite::driver('facebook')->user();

        $finduser = User::where('facebook_id', $user->id)->first();

        if ($finduser) {
            Auth::login($finduser);
            return redirect('/dashboard');
        } else {
            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'facebook_id' => $user->id,
                'username' => Str::slug($user->name) . rand(1000, 9999),
                'password' => bcrypt(Str::random(16)),
                'role_id' => 3
            ]);
            Auth::login($newUser);
            return redirect('/dashboard');
        }
    } catch (Exception $e) {
        // dd($e->getMessage()); // Xem chi tiết lỗi nếu có
        return redirect('/login')->withErrors(['facebook' => 'Đăng nhập thất bại']);
    }
}
}
