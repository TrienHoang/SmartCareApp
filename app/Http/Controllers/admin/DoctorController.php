<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDoctorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Department;
use App\Models\Room;
use App\Models\Appointment;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        // Lấy danh sách bác sĩ kèm các mối quan hệ liên quan
        $query = Doctor::with(['user', 'department', 'room']);

        // Bộ lọc theo phòng ban
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // Bộ lọc theo chuyên môn
        if ($request->filled('specialization')) {
            $query->where('specialization', 'like', '%' . $request->specialization . '%');
        }

        // Bộ lọc theo tên bác sĩ (tìm trong user.full_name)
        if ($request->filled('name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->name . '%');
            });
        }

        $doctors = $query->paginate(10);
        $departments = Department::all();

        // ✅ Gán cờ "đang nghỉ" cho từng bác sĩ
        foreach ($doctors as $doctor) {
            $doctor->is_on_leave_today = $doctor->isOnLeaveToday();
        }

        // ✅ Lấy danh sách người dùng chưa là bác sĩ
        $existingDoctorUserIds = Doctor::pluck('user_id')->toArray();
        $availableUsers = User::whereNotIn('id', $existingDoctorUserIds)->get();

        return view('admin.doctors.index', compact('doctors', 'departments', 'availableUsers'));
    }


    public function create()
    {
        $users = User::all();
        $departments = Department::all();
        $rooms = Room::all();

        $existingDoctorUserIds = Doctor::pluck('user_id')->toArray();
        $availableUsers = $users->whereNotIn('id', $existingDoctorUserIds);

        return view('admin.doctors.create', compact('availableUsers', 'departments', 'rooms'));
    }



    public function store(StoreDoctorRequest $request)
    {
        $validated = $request->validated();

        $department = Department::find($validated['department_id']);
        if (!$department || !$department->is_active) {
            return back()->withInput()
                ->with('error', '❌ Không thể thêm bác sĩ vào phòng ban đã ngừng hoạt động.');
        }

        $existingDoctor = Doctor::where('user_id', $validated['user_id'])->first();

        if ($existingDoctor) {
            if ($existingDoctor->department_id != $validated['department_id']) {
                return back()->withInput()
                    ->with('error', '⚠️ Người dùng này đã là bác sĩ ở phòng ban khác.');
            }

            return back()->withInput()->with('error', '⚠️ Người dùng này đã là bác sĩ.');
        }

        DB::beginTransaction();

        try {
            $doctor = Doctor::create($validated);

            Log::info('👨‍⚕️ Bác sĩ mới được tạo', [
                'doctor_id'     => $doctor->id,
                'user_id'       => $validated['user_id'],
                'department_id' => $validated['department_id'],
                'created_by'    => auth()->id(),
            ]);

            DB::commit();

            return redirect()->route('admin.doctors.index')
                ->with('success', '✅ Thêm bác sĩ mới thành công.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('❌ Lỗi khi thêm bác sĩ', [
                'error'     => $e->getMessage(),
                'user_id'   => $validated['user_id'],
                'performed_by' => auth()->id(),
            ]);

            return back()->withInput()
                ->with('error', '❌ Có lỗi xảy ra khi thêm bác sĩ. Vui lòng thử lại.');
        }
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
            'room_id'        => 'required|exists:rooms,id',
            'biography'      => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $department = Department::find($request->department_id);
        if (!$department || !$department->is_active) {
            return back()->withInput()
                ->with('error', '❌ Không thể chuyển bác sĩ sang phòng ban đã ngừng hoạt động.');
        }

        if ($request->department_id != $doctor->department_id) {
            $hasDoctorInTargetDepartment = Doctor::where('department_id', $request->department_id)
                ->where('id', '!=', $doctor->id)
                ->exists();

            if ($hasDoctorInTargetDepartment) {
                return back()->withInput()
                    ->with('error', '⚠️ Phòng ban này đã có bác sĩ. Vui lòng chọn phòng ban khác.');
            }
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
            return redirect()->route('admin.doctors.index')
                ->with('success', "✅ Cập nhật thông tin bác sĩ '{$name}' thành công!");
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('❌ Lỗi khi cập nhật bác sĩ: ' . $e->getMessage());

            return back()->withInput()
                ->with('error', '❌ Có lỗi xảy ra khi cập nhật. Vui lòng thử lại!');
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
        // Load các mối quan hệ liên quan nếu cần
        $doctor->load(['user', 'department', 'room']);

        return view('admin.doctors.show', compact('doctor'));
    }
}
