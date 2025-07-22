<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Department;
use App\Models\Room;
use App\Models\Appointment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::whereHas('user', function ($q) {
            $q->where('role_id', 2); // Chỉ user là bác sĩ
        })->with(['user', 'department', 'room']);

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('specialization')) {
            $query->where('specialization', 'like', '%' . $request->specialization . '%');
        }

        $doctors = $query->paginate(10);
        $departments = Department::all();

        return view('admin.doctors.index', compact('doctors', 'departments'));
    }

    public function create()
    {
        $existingDoctorUserIds = Doctor::pluck('user_id')->toArray();

        $availableUsers = User::where('role_id', 2)
            ->whereNotIn('id', $existingDoctorUserIds)
            ->get();

        $departments = Department::all();
        $rooms = Room::all();

        return view('admin.doctors.create', compact('availableUsers', 'departments', 'rooms'));
    }


    public function store(Request $request)
    {
        // 1. Validate dữ liệu nhập vào
        $request->validate([
            'full_name'       => 'required|string|max:100',
            'email'           => 'required|email|unique:users,email',
            'password'        => 'required|string|min:6',
            'avatar'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'specialization'  => 'required|string|max:255',
            'department_id'   => 'required|exists:departments,id',
            'room_id'         => 'required|exists:rooms,id',
        ], [
            'full_name.required'      => 'Vui lòng nhập họ tên bác sĩ.',
            'full_name.max'           => 'Họ tên không được vượt quá 100 ký tự.',

            'email.required'          => 'Vui lòng nhập email.',
            'email.email'             => 'Email không đúng định dạng.',
            'email.unique'            => 'Email này đã được sử dụng.',

            'password.required'       => 'Vui lòng nhập mật khẩu.',
            'password.min'            => 'Mật khẩu phải có ít nhất :min ký tự.',

            'avatar.image'            => 'Ảnh đại diện phải là file hình ảnh.',
            'avatar.mimes'            => 'Ảnh đại diện phải có định dạng jpeg, png, jpg hoặc gif.',
            'avatar.max'              => 'Ảnh đại diện không được vượt quá 2MB.',

            'specialization.required' => 'Vui lòng nhập chuyên môn.',
            'specialization.max'      => 'Chuyên môn không được vượt quá 255 ký tự.',

            'department_id.required'  => 'Vui lòng chọn phòng ban.',
            'department_id.exists'    => 'Phòng ban đã chọn không hợp lệ.',

            'room_id.required'        => 'Vui lòng chọn phòng khám.',
            'room_id.exists'          => 'Phòng khám đã chọn không hợp lệ.',
        ]);


        // 2. Upload ảnh đại diện nếu có
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        // 3. Tạo tài khoản người dùng mới với role bác sĩ
        $user = User::create([
            'username'  => $this->generateUsername($request->full_name),
            'full_name' => $request->full_name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role_id'   => 2, // Role bác sĩ
            'avatar'    => $avatarPath,
        ]);

        // 4. Tạo bản ghi bác sĩ
        Doctor::create([
            'user_id'       => $user->id,
            'specialization' => $request->specialization,
            'department_id' => $request->department_id,
            'room_id'       => $request->room_id,
            'biography'     => $request->biography,
        ]);

        return redirect()->route('admin.doctors.index')->with('success', 'Đã thêm bác sĩ mới thành công!');
    }

    // Hàm phụ để tự tạo username không trùng
    protected function generateUsername($fullName)
    {
        $base = Str::slug($fullName);
        $username = $base;
        $i = 1;

        while (User::where('username', $username)->exists()) {
            $username = $base . $i++;
        }

        return $username;
    }




    public function edit(Doctor $doctor)
    {
        $departments = Department::all();
        $rooms = Room::all();
        $users = User::all();

        return view('admin.doctors.edit', compact('doctor', 'departments', 'rooms', 'users'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $validator = Validator::make($request->all(), [
            'specialization' => 'required|string|max:100',
            'department_id' => 'required|exists:departments,id',
            'room_id' => 'required|exists:rooms,id',
            'biography' => 'nullable|string|max:1000',
        ], [
            'specialization.required' => '💼 Vui lòng nhập chuyên môn.',
            'department_id.required' => '🏥 Vui lòng chọn phòng ban.',
            'room_id.required' => '🏨 Vui lòng chọn phòng khám.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $doctor->update([
                'specialization' => $request->specialization,
                'department_id' => $request->department_id,
                'room_id' => $request->room_id,
                'biography' => $request->biography,
            ]);

            DB::commit();

            $name = $doctor->user->full_name ?? 'bác sĩ';
            return redirect()->route('admin.doctors.index')->with('success', "✅ Đã cập nhật thông tin bác sĩ '{$name}' thành công!");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Lỗi khi cập nhật bác sĩ: ' . $e->getMessage());

            return back()->withInput()->with('error', '❌ Có lỗi xảy ra khi cập nhật. Vui lòng thử lại!');
        }
    }

    public function destroy(Doctor $doctor)
    {
        $userName = $doctor->user->full_name ?? 'bác sĩ';

        try {
            if (Appointment::where('doctor_id', $doctor->id)->exists()) {
                return redirect()->route('admin.doctors.index')
                    ->with('error', "❌ Không thể xóa bác sĩ '{$userName}' vì đã có lịch hẹn!");
            }

            $doctor->delete();

            return redirect()->route('admin.doctors.index')
                ->with('success', "✅ Đã xóa bác sĩ '{$userName}' thành công!");
        } catch (\Exception $e) {
            Log::error('❌ Lỗi khi xóa bác sĩ: ' . $e->getMessage());

            return redirect()->route('admin.doctors.index')
                ->with('error', '❌ Có lỗi xảy ra khi xóa bác sĩ. Vui lòng thử lại!');
        }
    }

    public function show(Doctor $doctor)
    {
        // Chỉ cần truyền doctor đã được tự động bind từ route
        return view('admin.doctors.show', compact('doctor'));
    }
}
