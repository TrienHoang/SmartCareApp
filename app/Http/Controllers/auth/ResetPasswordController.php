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
                ->with('message', 'Liên kết đặt lại không hợp lệ hoặc đã hết hạn.');
        }

        if (!$request->has('email') || !filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->route('password.request')
                ->with('message', 'Email không hợp lệ hoặc bị thiếu trong liên kết.');
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
            'password' => ['required', 'min:6'],
            'password_confirmation' => ['required', 'same:password'],
        ], [
            'token.required' => 'Thiếu mã token đặt lại mật khẩu.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Định dạng email không hợp lệ.',
            'password.required' => 'Mật khẩu không được để trống.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
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

                // Gửi mail thông báo đã đổi mật khẩu
                try {
                    Mail::to($user->email)->send(new PasswordChangedMail($user));
                } catch (\Exception $e) {
                    Log::error("Lỗi gửi mail đổi mật khẩu cho email {$user->email}: " . $e->getMessage());
                    // Không cần dừng tiến trình, chỉ log lỗi
                }
            }
        );

        // Step 3: Phản hồi kết quả
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Mật khẩu của bạn đã được đặt lại thành công. Vui lòng đăng nhập.')
            : back()->withErrors(['email' => ['Liên kết đặt lại không hợp lệ hoặc đã hết hạn.']])->withInput();
    }
}
