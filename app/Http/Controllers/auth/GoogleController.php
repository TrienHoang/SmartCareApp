<?php

namespace App\Http\Controllers\auth;

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
            $user = Socialite::driver('google')->user();

            $finduser = User::where('google_id', $user->id)->first();

            if ($finduser) {
                Auth::login($finduser);
                return redirect('/dashboard')->with('message', 'Đăng nhập bằng Google thành công!');
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'username' => Str::slug($user->name) . rand(1000, 9999),
                    'google_id' => $user->id,
                    'password' => bcrypt(Str::random(16)),
                    'role_id' => 3
                ]);

                Auth::login($newUser);
                return redirect('/dashboard')->with('message', 'Đăng ký & đăng nhập thành công bằng Google!');
            }
        } catch (Exception $e) {
            // dd($e->getMessage());
            return redirect('/login')->withErrors(['google' => 'Lỗi đăng nhập Google: ' . $e->getMessage()]);
        }
    }
    
}
