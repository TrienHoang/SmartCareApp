<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http; // THÊM DÒNG NÀY

class DoctorProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        // LẤY TÊN địa phương để hiển thị ra ngoài
        $province = $district = $ward = null;

        if ($user->province_code) {
            $province = Http::get("https://provinces.open-api.vn/api/p/{$user->province_code}")->json('name');
        }

        if ($user->district_code) {
            $district = Http::get("https://provinces.open-api.vn/api/d/{$user->district_code}")->json('name');
        }

        if ($user->ward_code) {
            $ward = Http::get("https://provinces.open-api.vn/api/w/{$user->ward_code}")->json('name');
        }

        return view('doctor.profile.show', compact('user', 'province', 'district', 'ward'));
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
            'full_name'     => 'required|string|max:255',
            'username'      => 'required|string|max:255|unique:users,username,' . $user->id,
            'email'         => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone'         => 'nullable|regex:/^0\d{9}$/',
            'gender'        => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
            'address'       => 'nullable|string|max:255',
            'avatar'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description'   => 'nullable|string',
            'education'     => 'nullable|string',

            // THÊM 3 validate mới
            'province_code' => 'nullable|string|max:20',
            'district_code' => 'nullable|string|max:20',
            'ward_code'     => 'nullable|string|max:20',
        ], [
            'full_name.required'         => 'Vui lòng nhập họ tên.',
            'username.required'          => 'Vui lòng nhập tên đăng nhập.',
            'username.unique'            => 'Tên đăng nhập đã được sử dụng.',
            'email.required'             => 'Vui lòng nhập email.',
            'email.unique'               => 'Email đã tồn tại.',
            'email.email'                => 'Email không đúng định dạng.',
            'phone.regex'                => 'Số điện thoại phải bắt đầu bằng 0 và gồm 10 chữ số.',
            'avatar.image'               => 'Tệp tải lên phải là hình ảnh.',
            'avatar.mimes'               => 'Ảnh đại diện phải có định dạng jpeg, png, jpg hoặc gif.',
            'avatar.max'                 => 'Ảnh đại diện không được vượt quá 2MB.',
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

            // THÊM 3 trường này để lưu xuống DB
            'province_code', 'district_code', 'ward_code',
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
            'new_password'      => [
                'required',
                'string',
                'min:6',
                'confirmed',
                function ($attribute, $value, $fail) use ($request) {
                    if (Hash::check($value, Auth::user()->password)) {
                        $fail('Mật khẩu mới không được trùng với mật khẩu hiện tại.');
                    }
                },
            ],
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required'     => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min'          => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
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
