<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Exception;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            if (!$googleUser->getEmail()) {
                return redirect('/login')->withErrors([
                    'google' => 'Không lấy được email từ Google. Vui lòng sử dụng phương thức đăng nhập khác.'
                ]);
            }

            $findUserByGoogleId = User::where('google_id', $googleUser->getId())->first();
            if ($findUserByGoogleId) {
                Auth::login($findUserByGoogleId);
                return redirect('/')->with('message', 'Đăng nhập bằng Google thành công!');
            }

            $existingUser = User::where('email', $googleUser->getEmail())->first();
            if ($existingUser) {
                return redirect('/login')->withErrors([
                    'google' => 'Email của bạn đã được đăng ký. Vui lòng đăng nhập bằng phương thức cũ.'
                ]);
            }

            $newUser = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'username' => Str::slug($googleUser->getName()) . rand(1000, 9999),
                'google_id' => $googleUser->getId(),
                'password' => bcrypt(Str::random(16)),
                'role_id' => 3
            ]);

            Auth::login($newUser);
            return redirect('/')->with('message', 'Đăng ký & đăng nhập thành công bằng Google!');

        } catch (Exception $e) {
            return redirect('/login')->withErrors([
                'google' => 'Lỗi đăng nhập Google: ' . $e->getMessage()
            ]);
        }
    }
}
