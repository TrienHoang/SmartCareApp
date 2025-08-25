<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Hiển thị trang thông tin cá nhân.
     */
    public function show()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để xem trang này.');
        }

        return view('client.profile', compact('user'));
    }

    /**
     * Cập nhật thông tin người dùng.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'full_name'      => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone'          => ['nullable', 'regex:/^0[0-9]{9}$/'], // Số điện thoại Việt Nam 10 chữ số
            'date_of_birth'  => ['nullable', 'date', 'before:today'],
            'gender'         => ['nullable', 'in:Nam,Nữ,Khác'],
            'address'        => ['nullable', 'string', 'max:500'],
            'avatar'         => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // 2MB
        ], [
            // Thông điệp lỗi tuỳ chỉnh
            'full_name.required'     => 'Họ tên không được để trống.',
            'email.required'         => 'Email không được để trống.',
            'email.email'            => 'Email không đúng định dạng.',
            'email.unique'           => 'Email đã được sử dụng.',
            'phone.regex'            => 'Số điện thoại không đúng định dạng (bắt đầu bằng 0 và gồm 10 chữ số).',
            'date_of_birth.date'     => 'Ngày sinh không hợp lệ.',
            'date_of_birth.before'   => 'Ngày sinh phải nhỏ hơn ngày hiện tại.',
            'gender.in'              => 'Giới tính không hợp lệ.',
            'avatar.image'           => 'Ảnh đại diện phải là tệp hình ảnh.',
            'avatar.mimes'           => 'Ảnh phải có định dạng jpeg, png, jpg hoặc gif.',
            'avatar.max'             => 'Ảnh đại diện không được vượt quá 2MB.',
        ]);

        // Nếu người dùng tải ảnh mới lên
        if ($request->hasFile('avatar')) {
            // Xoá avatar cũ nếu tồn tại
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Lưu avatar mới
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        // Cập nhật dữ liệu
        $updateSuccess = $user->update($validated);


        return redirect()->route('client.profile.show')
            ->with($updateSuccess ? 'success' : 'error', $updateSuccess ? 'Cập nhật thông tin thành công' : 'Có lỗi xảy ra khi cập nhật thông tin.');
    }
}
