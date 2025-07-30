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
            'full_name'      => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email,' . $user->id,
            'phone'          => 'nullable|string|max:20',
            'date_of_birth'  => 'nullable|date',
            'gender'         => 'nullable|in:Nam,Nữ,Khác',
            'address'        => 'nullable|string|max:500',
            'avatar'         => 'nullable|image|max:2048',
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

        if ($updateSuccess) {
            return redirect()->back()->with('success', 'Cập nhật thông tin thành công');
        } else {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi cập nhật thông tin.');
        }
    }
}
