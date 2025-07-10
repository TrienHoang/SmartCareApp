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

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::with(['user', 'department', 'room']);

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
        $users = User::all();
        $departments = Department::all();
        $rooms = Room::all();

        $existingDoctorUserIds = Doctor::pluck('user_id')->toArray();
        $availableUsers = $users->whereNotIn('id', $existingDoctorUserIds);

        return view('admin.doctors.create', compact('availableUsers', 'departments', 'rooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|numeric|exists:users,id',
            'specialization' => 'required|string|max:100',
            'department_id' => 'required|exists:departments,id',
            'room_id' => 'required|exists:rooms,id',
            'biography' => 'nullable|string|max:1000',
        ], [
            'user_id.required' => '🧑 Vui lòng chọn người dùng.',
            'specialization.required' => '💼 Vui lòng nhập chuyên môn.',
            'department_id.required' => '🏥 Vui lòng chọn phòng ban.',
            'room_id.required' => '🏨 Vui lòng chọn phòng khám.',
        ]);

        if (Doctor::where('user_id', $validated['user_id'])->exists()) {
            return back()->withInput()->with('error', '⚠️ Người dùng này đã là bác sĩ.');
        }

        Doctor::create($validated);

        return redirect()->route('admin.doctors.index')->with('success', '✅ Thêm bác sĩ mới thành công.');
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
}