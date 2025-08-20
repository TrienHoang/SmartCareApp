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
$query = Doctor::whereHas('user', function ($q) use ($request) {
    $q->where('role_id', 2);

    // Nếu có từ khóa tìm kiếm
    if ($request->filled('search')) {
        $q->where('full_name', 'like', '%' . $request->search . '%');
    }
})->with(['user', 'department', 'room', 'services']);

        // Lọc theo phòng ban
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // Lọc theo dịch vụ
        if ($request->filled('service_id')) {
            $query->whereHas('services', function ($q) use ($request) {
                $q->where('services.id', $request->service_id);
            });
        }

        $doctors = $query->paginate(10);
        $departments = Department::all();
        $services = Service::all();

        return view('admin.doctors.index', compact('doctors', 'departments', 'services'));
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
            'service_id'      => 'required|exists:services,id', // sửa từ service_ids sang service_id
        ], [
            'full_name.required'     => 'Họ và tên không được để trống.',
            'username.required'      => 'Tên đăng nhập không được để trống.',
            'username.unique'        => 'Tên đăng nhập đã tồn tại.',
            'email.required'         => 'Email không được để trống.',
            'email.email'            => 'Email không hợp lệ.',
            'email.unique'           => 'Email đã tồn tại.',
            'password.required'      => 'Mật khẩu không được để trống.',
            'password.min'           => 'Mật khẩu phải có ít nhất :min ký tự.',
            'avatar.image'           => 'Ảnh đại diện phải là hình ảnh.',
            'avatar.mimes'           => 'Ảnh đại diện phải có định dạng: jpeg, png, jpg, gif.',
            'department_id.required' => 'Vui lòng chọn phòng ban.',
            'department_id.exists'   => 'Phòng ban đã chọn không hợp lệ.',
            'service_id.required'    => 'Vui lòng chọn dịch vụ.',
            'service_id.exists'      => 'Dịch vụ đã chọn không hợp lệ.',
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

        // Gán một dịch vụ cho bác sĩ
        $doctor->services()->attach([$request->service_id]);


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
    $services = Service::where('status', 'active')->orderBy('name')->get();

    // chỉ 1 dịch vụ (service_id), lấy ID đầu tiên nếu có
    $selectedServiceId = $doctor->services()->pluck('services.id')->first();

    return view('admin.doctors.edit', compact('doctor', 'departments', 'services', 'selectedServiceId'));
}

public function update(Request $request, Doctor $doctor)
{
    $request->validate([
        'full_name'       => 'required|string|max:100',
        'email'           => 'required|email|unique:users,email,' . $doctor->user_id,
        'avatar'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'department_id'   => 'required|exists:departments,id',
        'service_id'      => 'required|exists:services,id', // đồng bộ với store
        'specialization'  => 'nullable|string|max:100',
        'biography'       => 'nullable|string|max:1000',
    ]);

    try {
        DB::beginTransaction();

        $avatarPath = $doctor->user->avatar;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $doctor->user->update([
            'full_name' => $request->full_name,
            'email'     => $request->email,
            'avatar'    => $avatarPath,
        ]);

        $doctor->update([
            'department_id'  => $request->department_id,
            'specialization' => $request->specialization,
            'biography'      => $request->biography,
        ]);

        // Gán lại dịch vụ (một dịch vụ duy nhất)
        $doctor->services()->sync([$request->service_id]);

        DB::commit();

        return redirect()->route('admin.doctors.index')->with('success', 'Cập nhật thông tin bác sĩ thành công.');
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