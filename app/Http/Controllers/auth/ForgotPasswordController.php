<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /**
     * Hiển thị form quên mật khẩu.
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Xử lý gửi liên kết đặt lại mật khẩu.
     */
    public function sendResetLinkEmail(Request $request)
    {
        // ✅ Bước 1: Kiểm tra hợp lệ email
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
        ]);
        if (!\App\Models\User::where('email', $request->email)->exists()) {
            return back()->withErrors(['email' => 'Email chưa được đăng ký.'])->withInput();
        }

        // ✅ Bước 2: Gửi liên kết đặt lại mật khẩu
        $status = Password::sendResetLink($request->only('email'));

        // ✅ Bước 3: Xử lý phản hồi
        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('message', 'Đã gửi email khôi phục mật khẩu! Vui lòng kiểm tra hộp thư đến hoặc thư rác.');
        }

        
        if (__($status) === 'Please wait before retrying.') {
            return back()->withErrors([
                'email' => 'Bạn vừa yêu cầu đặt lại mật khẩu. Vui lòng thử lại sau ít phút.',
            ]);
        }
    }
}