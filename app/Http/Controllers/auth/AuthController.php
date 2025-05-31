<?php

namespace App\Http\Controllers\auth;


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
        // $request->validate([
        //     'username' => 'required',
        //     'password' => 'required'
        // ], [
        //     'username.required' => 'Vui lòng nhập tên đăng nhập',
        //     'password.required' => 'Vui lòng nhập mật khẩu'
        // ]);

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            session()->put('user_id', Auth::id());

            // Chuyển hướng tùy theo vai trò
            if (Auth::user()->role_id == 1) {
                return redirect()->route('dashboard')->with('message', 'Chào mừng quản trị viên!');
            } else {
                return redirect()->route('dashboard')->with('message', 'Đăng nhập thành công!');
            }
        }

        return redirect()->back()
            ->withInput($request->only('username', 'form_type'))
            ->withErrors(['username' => 'Sai tên đăng nhập hoặc mật khẩu']);
    }

    public function register(RegisterRequest $request)
    {
        // $request->validate([
        //     'username' => 'required|unique:users,username',
        //     'email' => 'required|email|unique:users',
        //     'password' => 'required|min:6',
        // ], [
        //     'username.required' => 'Vui lòng nhập tên đăng nhập',
        //     'username.unique' => 'Tên đăng nhập đã tồn tại',
        //     'email.required' => 'Vui lòng nhập email',
        //     'email.unique' => 'Email đã tồn tại',
        //     'email.email' => 'Email không hợp lệ',
        //     'password.required' => 'Vui lòng nhập mật khẩu',
        //     'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
        // ]);


        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'full_name' => '',
            'email' => $request->email,
            'phone' => '',
            'gender' => '',
            'date_of_birth' => null,
            'address' => '',
            'role_id' => 3, // 3 = user
            'avatar' => '',
        ]);


        return redirect()->route('login')->with('success', 'Đăng ký thành công. Vui lòng đăng nhập!');
    }
}
