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
use App\Models\Service;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;



class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::whereHas('user', function ($q) {
            $q->where('role_id', 2);
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
    $services = Service::where('status', 'active')->orderBy('name')->get(); // 👈 sửa ở đây

    return view('admin.doctors.create', compact('availableUsers', 'departments', 'services'));
}



    public function toggleStatus(Request $request, User $user)
    {
        if ($user->role_id != 2) {
            return response()->json(['success' => false, 'message' => 'Không phải tài khoản bác sĩ.']);
        }

        $user->status = $user->status === 'online' ? 'offline' : 'online';
        $user->save();

        return response()->json([
            'success' => true,
            'status' => $user->status,
            'message' => 'Trạng thái đã được cập nhật.'
        ]);
    }

public function store(Request $request)
{
    $request->validate([
        'full_name'       => 'required|string|max:100',
        'username'        => 'required|string|max:50|unique:users,username',
        'email'           => 'required|email|unique:users,email',
        'password'        => 'required|string|min:6',
        'avatar'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'department_id'   => 'required|exists:departments,id',
        'service_ids'     => 'required|array',
        'service_ids.*'   => 'exists:services,id',
    ]);

    $avatarPath = null;
    if ($request->hasFile('avatar')) {
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
    }

    $user = User::create([
        'full_name' => $request->full_name,
        'username'  => $request->username,
        'email'     => $request->email,
        'password'  => Hash::make($request->password),
        'role_id'   => 2,
        'avatar'    => $avatarPath,
        'status'    => 'online',
    ]);

    $doctor = Doctor::create([
        'user_id'       => $user->id,
        'department_id' => $request->department_id,
        'biography'     => $request->biography,
    ]);

    // Gán dịch vụ cho bác sĩ qua bảng trung gian doctor_service
    $doctor->services()->attach($request->service_ids);

    return redirect()->route('admin.doctors.index')->with('success', 'Đã thêm bác sĩ mới thành công.');
}







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
            'department_id'  => 'required|exists:departments,id',
            'biography'      => 'nullable|string|max:1000',
        ], [
            'specialization.required' => 'Vui lòng nhập chuyên môn.',
            'department_id.required'  => 'Vui lòng chọn phòng ban.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $doctor->update([
                'specialization' => $request->specialization,
                'department_id'  => $request->department_id,
                'room_id'        => $request->room_id,
                'biography'      => $request->biography,
            ]);

            DB::commit();

            $name = $doctor->user->full_name ?? 'bác sĩ';
            return redirect()->route('admin.doctors.index')->with('success', "Đã cập nhật thông tin bác sĩ {$name} thành công.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi cập nhật bác sĩ: ' . $e->getMessage());

            return back()->withInput()->with('error', 'Có lỗi xảy ra khi cập nhật. Vui lòng thử lại.');
        }
    }

    public function destroy(Doctor $doctor)
    {
        $userName = $doctor->user->full_name ?? 'bác sĩ';

        try {
            if (Appointment::where('doctor_id', $doctor->id)->exists()) {
                return redirect()->route('admin.doctors.index')
                    ->with('error', "Không thể xóa bác sĩ {$userName} vì đã có lịch hẹn.");
            }

            $doctor->delete();

            return redirect()->route('admin.doctors.index')
                ->with('success', "Đã xóa bác sĩ {$userName} thành công.");
        } catch (\Exception $e) {
            Log::error('Lỗi khi xóa bác sĩ: ' . $e->getMessage());

            return redirect()->route('admin.doctors.index')
                ->with('error', 'Có lỗi xảy ra khi xóa bác sĩ. Vui lòng thử lại.');
        }
    }

    public function show(Doctor $doctor)
    {
        return view('admin.doctors.show', compact('doctor'));
    }
}
