<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordChangedMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;


class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token)
    {
        // Kiểm tra token và email có tồn tại
        if (!$token) {
            return redirect()->route('password.request')
                ->withErrors(['token' => 'Mã token đặt lại mật khẩu không hợp lệ hoặc bị thiếu.']);
        }

        if (!$request->has('email') || !filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Email không hợp lệ hoặc bị thiếu trong liên kết.']);
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function reset(Request $request)
    {
        // Step 1: Validation
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'min:8'],
            'password_confirmation' => ['required', 'same:password'],
        ], [
            'token.required' => 'Thiếu mã token đặt lại mật khẩu.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Định dạng email không hợp lệ.',
            'password.required' => 'Mật khẩu không được để trống.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password_confirmation.required' => 'Vui lòng xác nhận mật khẩu.',
            'password_confirmation.same' => 'Xác nhận mật khẩu không khớp.',
        ]);

        // Step 2: Attempt password reset
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                // Thêm try-catch xử lý lỗi gửi mail
                try {
                    Mail::to($user->email)->send(new PasswordChangedMail($user));
                } catch (\Exception $e) {
                    Log::error("Lỗi gửi mail đổi mật khẩu cho email {$user->email}: " . $e->getMessage());
                    // Có thể thêm flash message nhẹ nếu muốn
                }
            }
        );

        // Step 3: Phản hồi kết quả
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]])->withInput();
    }
}
