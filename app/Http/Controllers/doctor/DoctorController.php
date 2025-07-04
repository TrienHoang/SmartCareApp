<?php

namespace App\Http\Controllers\doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Department;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;



class DoctorController extends Controller
{
    /**
     * Hiển thị danh sách bác sĩ với chức năng tìm kiếm.
     */
    public function index(Request $request)
    {
        // Khởi tạo query cơ bản
        $query = Doctor::with(['user', 'department', 'room']);

        // ✅ Tìm theo tên bác sĩ (liên kết với bảng users)
        if ($request->filled('name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        // ✅ Tìm theo chuyên môn
        if ($request->filled('specialization')) {
            $query->where('specialization', 'like', '%' . $request->specialization . '%');
        }

        // ✅ Lọc theo khoa
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // ✅ Lọc theo phòng
        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        // ✅ Sắp xếp & phân trang
        $doctors = $query->orderByDesc('updated_at')
            ->paginate(10)
            ->appends($request->query()); // Giữ query khi bấm phân trang

        // ✅ Lấy danh sách khoa & phòng cho dropdown filter
        $departments = Department::all();
        $rooms = Room::all();

        return view('doctor.list.index', compact('doctors', 'departments', 'rooms'));
    }

    public function show($id)
    {
        $doctor = Doctor::with(['user', 'department', 'room'])->findOrFail($id);

        return view('doctor.list.show', compact('doctor'));
    }

public function history(Request $request)
{
    $user = Auth::user();

    // ✅ Kiểm tra user và quan hệ doctor
    $doctor = $user->doctor;

    if (!$doctor) {
        return redirect()->route('doctor.dashboard')
            ->with('error', 'Tài khoản của bạn chưa được liên kết với bác sĩ nào trong hệ thống.');
    }

    // ✅ Truy vấn lịch sử khám (đã hoàn thành)
    $query = Appointment::with(['patient', 'service', 'medicalRecord'])
        ->where('doctor_id', $doctor->id)
        ->where('status', 'completed')
        ->orderByDesc('appointment_time');

    // ✅ Tìm kiếm theo từ khoá (họ tên, email, số điện thoại)
    if ($request->filled('keyword')) {
        $keyword = trim($request->keyword);

        $query->whereHas('patient', function ($q) use ($keyword) {
            $q->where('full_name', 'like', '%' . $keyword . '%')
              ->orWhere('email', 'like', '%' . $keyword . '%')
              ->orWhere('phone', 'like', '%' . $keyword . '%');
        });
    }

    // ✅ Lấy kết quả có phân trang
    $appointments = $query->paginate(10);

    return view('doctor.history.index', compact('appointments'));
}

public function historyShow($id)
{
    $appointment = Appointment::with(['patient', 'service', 'medicalRecord'])
        ->where('status', 'completed')
        ->findOrFail($id);

    return view('doctor.history.show', compact('appointment'));
}



}
