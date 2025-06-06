<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with(['user', 'department'])->get(); // Load cả user & department
        return view('admin.doctors.index', compact('doctors'));
    }

public function create()
{
    $users = User::all();
    // dd($users); // xem dữ liệu có lấy được không

    $departments = Department::all();
    return view('admin.doctors.create', compact('users', 'departments'));
}

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'department_id' => 'nullable|exists:departments,id',
            'specialization' => 'nullable|string|max:100',
            'biography' => 'nullable|string',
        ]);

        Doctor::create([
            'user_id' => $request->user_id,
            'department_id' => $request->department_id,
            'specialization' => $request->specialization,
            'biography' => $request->biography,
        ]);

        return redirect()->route('admin.doctors.index')->with('success', 'Thêm bác sĩ thành công!');
    }

    public function edit(Doctor $doctor)
    {
        $departments = Department::all();
        // Nếu muốn cho phép sửa user_id, truyền danh sách users
        $users = User::all();
        return view('admin.doctors.edit', compact('doctor', 'departments', 'users'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $request->validate([
            // Nếu không muốn thay đổi user_id, bạn có thể bỏ dòng này
            'user_id' => 'required|exists:users,id',
            'department_id' => 'nullable|exists:departments,id',
            'specialization' => 'nullable|string|max:100',
            'biography' => 'nullable|string',
        ]);

        // Nếu bạn không muốn cho phép sửa user_id, bỏ trường này khỏi update
        $doctor->update([
            'user_id' => $request->user_id,
            'department_id' => $request->department_id,
            'specialization' => $request->specialization,
            'biography' => $request->biography,
        ]);

        return redirect()->route('admin.doctors.index')->with('success', 'Cập nhật bác sĩ thành công!');
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return redirect()->route('admin.doctors.index')->with('success', 'Xóa bác sĩ thành công!');
    }
}
