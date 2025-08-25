<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

use function Symfony\Component\String\b;

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
            $user = Auth::user();
            
            if ($user->status === 'offline') {
                Auth::logout();
                return redirect()->back()
                    ->withInput($request->only('username', 'form_type'))
                    ->withErrors(['username' => 'Tài khoản của bạn đang bị khóa (offline), không thể đăng nhập.']);
            }

            session()->put('user_id', Auth::id());

            if (Auth::user()->role_id == 1) {
                return redirect()->route('admin.dashboard.index')
                    ->with('success', 'Chào mừng quản trị viên!');
            } elseif (Auth::user()->role_id == 2) {
                return redirect()->route('doctor.dashboard')
                    ->with('success', 'Chào mừng bác sĩ!');
            } elseif (Auth::user()->role_id == 4) {
                return redirect()->route('receptionist.dashboard')
                    ->with('success', 'Chào mừng lễ tân!');
            } else {
                return redirect()->route('home')
                    ->with('success', 'Đăng nhập thành công!');
            }
        }

        return redirect()->back()
            ->withInput($request->only('username', 'form_type'))
            ->withErrors(['username' => 'Sai tên đăng nhập hoặc mật khẩu']);
    }

    public function register(RegisterRequest $request)
    {
        // Tạo mã OTP
        $otp = rand(100000, 999999);

        // Lưu tạm vào session
        session([
            'pending_register' => [
                'username'   => $request->username,
                'password'   => Hash::make($request->password),
                'full_name'  => $request->input('fullname'), // Lấy chính xác field
                'email'      => $request->email,
            ],
            'register_otp' => $otp,
        ]);

        // Gửi mail OTP
        Mail::raw("Mã OTP: {$otp}", function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Xác nhận đăng ký');
        });

        return redirect()->route('verify.otp.form')
            ->with('success', 'OTP đã được gửi đến email, vui lòng nhập để xác nhận.');
    }

    public function showVerifyOtpForm()
    {
        if (!session()->has('pending_register') || !session()->has('register_otp')) {
            return redirect()->route('login')
                ->with('error', 'Vui lòng đăng ký trước khi xác thực OTP.')
                ->withInput(['form_type' => 'register']);
        }
        return view('auth.verify_otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ], [
            'otp.required' => 'Mã OTP là bắt buộc.',
            'otp.numeric' => 'Mã OTP phải là số.',
        ]);

        $otp = session('register_otp');
        $userData = session('pending_register');

        // Kiểm tra session còn không
        if (!$otp || !$userData || !isset($userData['full_name'])) {
            return redirect()->route('login')
                ->withErrors(['otp' => 'Phiên OTP đã hết hạn, vui lòng đăng ký lại.']);
        }

        if ($request->otp == $otp) {
            // Tạo user từ dữ liệu session
            User::create([
                'username' => $userData['username'],
                'password' => $userData['password'],
                'full_name' => $userData['full_name'],  // Lúc này chắc chắn có
                'email' => $userData['email'],
                'phone' => '',
                'gender' => '',
                'date_of_birth' => null,
                'address' => '',
                'role_id' => 3,
                'avatar' => '',
                'status' => 'online',
            ]);

            session()->forget(['pending_register', 'register_otp']);

            return redirect()->route('login')
                ->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
        }

        return back()->withErrors(['otp' => 'Mã OTP không đúng']);
    }

    public function resendOtp()
    {
        // dd(session()->all());

        $userData = session('pending_register');

        if (!$userData || !isset($userData['email'])) {
            return redirect()->route('login')
                ->withErrors(['otp' => 'Không tìm thấy thông tin đăng ký. Vui lòng đăng ký lại.']);
        }

        // Tạo mã OTP mới
        $otp = rand(100000, 999999);

        session(['register_otp' => $otp]);

        // Gửi mail OTP mới
        Mail::raw("Mã OTP xác nhận đăng ký mới của bạn là: {$otp}", function ($message) use ($userData) {
            $message->to($userData['email'])->subject('Xác nhận đăng ký (mã mới)');
        });

        return back()->with('success', 'Mã OTP mới đã được gửi đến email của bạn.');
    }

    public function logout()
    {
        Auth::logout();
        session()->forget('user_id');
        // Thêm query parameter logged_out=1
        return redirect()->route('login', ['logged_out' => 1])
            ->with('success', 'Đăng xuất thành công!');
    }
}
