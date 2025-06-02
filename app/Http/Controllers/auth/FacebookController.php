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
            $facebookUser = Socialite::driver('facebook')->user();

            if (!$facebookUser->getEmail()) {
                return redirect('/login')->withErrors(['facebook' => 'Không lấy được email từ Facebook. Vui lòng sử dụng phương thức đăng nhập khác.']);
            }

            $findUserByFacebookId = User::where('facebook_id', $facebookUser->getId())->first();

            if ($findUserByFacebookId) {
                Auth::login($findUserByFacebookId);
                return redirect('/dashboard');
            }

            $existingUser = User::where('email', $facebookUser->getEmail())->first();
            if ($existingUser) {
                return redirect('/login')->withErrors(['facebook' => 'Email của bạn đã được đăng ký. Vui lòng đăng nhập bằng phương thức cũ.']);
            }

            $newUser = User::create([
                'name' => $facebookUser->getName(),
                'email' => $facebookUser->getEmail(),
                'facebook_id' => $facebookUser->getId(),
                'username' => Str::slug($facebookUser->getName()) . rand(1000, 9999),
                'password' => bcrypt(Str::random(16)),
                'role_id' => 3
            ]);

            Auth::login($newUser);
            return redirect('/dashboard');
        } catch (Exception $e) {
            return redirect('/login')->withErrors(['facebook' => 'Đăng nhập thất bại: ' . $e->getMessage()]);
        }
    }
}
