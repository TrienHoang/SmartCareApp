<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;
use Exception;

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
                    'google' => 'Không lấy được email từ Google. Vui lòng thử lại.'
                ]);
            }

            /** ① Đăng nhập nếu đã liên kết google_id */
            $userByGoogleId = User::where('google_id', $googleUser->getId())->first();
            if ($userByGoogleId) {
                Auth::login($userByGoogleId);
                return redirect('/')->with('message', 'Đăng nhập bằng Google thành công!');
            }

            /** ② Kiểm tra xem email đã tồn tại chưa */
            $userByEmail = User::where('email', $googleUser->getEmail())->first();
            if ($userByEmail) {
                return redirect('/login')
                    ->withErrors(['google' => 'Tài khoản đã tồn tại. Vui lòng đăng nhập bằng mật khẩu.'])
                    ->withInput(['form_type' => 'login']);
            }

            /** ③ Tạo tài khoản mới */
            $newUser = User::create([
                'name'       => $googleUser->getName(),
                'username'   => Str::slug($googleUser->getName()) . rand(1000, 9999),
                'email'      => $googleUser->getEmail(),
                'google_id'  => $googleUser->getId(),
                'password'   => bcrypt(Str::random(16)),
                'role_id'    => 3
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
