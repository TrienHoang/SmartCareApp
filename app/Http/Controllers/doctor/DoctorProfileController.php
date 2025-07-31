<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DoctorProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('doctor.profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('doctor.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
    'full_name'     => 'nullable|string|max:255',
    'username'      => 'nullable|string|max:255|unique:users,username,' . $user->id,
    'email'         => 'nullable|email|max:255|unique:users,email,' . $user->id,
    'phone'         => 'nullable|regex:/^0\d{9}$/',
    'gender'        => 'nullable|in:male,female,other',
    'date_of_birth' => 'nullable|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
    'address'       => 'nullable|string|max:255',
    'avatar'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    'description'   => 'nullable|string',
    'education'     => 'nullable|string',
], [
    'username.unique'                => 'Tên đăng nhập đã được sử dụng.',
    'email.unique'                   => 'Email đã tồn tại.',
    'email.email'                    => 'Email không đúng định dạng.',
    'phone.regex'                    => 'Số điện thoại phải bắt đầu bằng 0 và gồm 10 chữ số.',
    'avatar.image'                   => 'Tệp tải lên phải là hình ảnh.',
    'avatar.mimes'                   => 'Ảnh đại diện phải có định dạng jpeg, png, jpg hoặc gif.',
    'avatar.max'                     => 'Ảnh đại diện không được vượt quá 2MB.',
    'date_of_birth.before_or_equal' => 'Bạn phải đủ ít nhất 18 tuổi.',
]);



        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->fill($request->only([
            'full_name', 'username', 'email', 'phone',
            'gender', 'date_of_birth', 'address',
            'description', 'education',
        ]));

        $user->save();

        return redirect()->route('doctor.profile.show')->with('success', 'Cập nhật thông tin thành công!');
    }

    public function changePasswordForm()
    {
        return view('doctor.profile.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password'  => 'required|string',
            'new_password'      => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required'     => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min'          => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'new_password.confirmed'    => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Đổi mật khẩu thành công!');
    }
}
