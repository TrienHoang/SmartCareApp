<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function showLogin()
    {
        $title = 'Đăng nhập';
        return view('auth.login', compact('title'));
    }

    public function showRegister()
    {
        $title = 'Đăng ký';
        return view('auth.login', compact('title'));
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            session()->put('user_id', Auth::id());

            if (Auth::user()->role_id == 1) {
                return redirect()->route('admin.dashboard')->with('message', 'Chào mừng quản trị viên!');
            } else {
                return redirect()->route('home')->with('message', 'Đăng nhập thành công!');
            }
        }

        return redirect()->back()
            ->withInput($request->only('username', 'form_type'))
            ->withErrors(['username' => 'Sai tên đăng nhập hoặc mật khẩu']);
    }

    public function register(RegisterRequest $request)
    {
        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'full_name' => $request->fullname,
            'email' => $request->email,
            'phone' => '',
            'gender' => '',
            'date_of_birth' => null,
            'address' => '',
            'role_id' => 3,
            'avatar' => '',
        ]);

        return back()->with('success', 'Đăng ký thành công. Vui lòng đăng nhập!')->withInput(['form_type' => 'register']);
    }

    public function logout()
    {
        Auth::logout();
        session()->forget('user_id');
        return redirect()->route('login')->with('success', 'Đăng xuất thành công!');
    }
}
