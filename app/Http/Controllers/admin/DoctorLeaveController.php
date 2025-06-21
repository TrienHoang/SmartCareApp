<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorLeave;
use App\Models\Room;
use Illuminate\Http\Request;

class DoctorLeaveController extends Controller
{
    public function index(Request $request)
    {
        $query = DoctorLeave::with(['doctor.user'])->orderBy('id', 'desc');

        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->whereHas('doctor.user', function ($q) use ($keyword) {
                $q->where('full_name', 'like', '%' . $keyword . '%');
            });
        }

        $doctorLeaves = $query->paginate(10)->withQueryString();
        return view('admin.doctor_leaves.index', compact('doctorLeaves'));
    }
    public function edit($id)
    {
        // Kiểm tra quyền truy cập
        $leave = DoctorLeave::findOrFail($id);

        // Nếu đã duyệt thì không cho truy cập trang chỉnh sửa
        if ($leave->approved == 1) {
            return redirect()->route('admin.doctor_leaves.index')->with('error', 'Lịch nghỉ đã được duyệt và không thể chỉnh sửa.');
        }

        $doctors = Doctor::all();
        $rooms = Room::all(); // Assuming you want to show a list of rooms for selection

        return view('admin.doctor_leaves.edit', compact('leave', 'doctors', 'rooms'));
    }

    public function update(Request $request, $id)
    {
        // Validate input
        $request->validate([
            'approved' => 'required|in:0,1',
        ]);

        // Lấy bản ghi lịch nghỉ
        $leave = DoctorLeave::findOrFail($id);

        // Nếu đã duyệt (approved = 1), không cho thay đổi nữa
        if ($leave->approved == 1) {
            return redirect()->route('admin.doctor_leaves.index')->with('error', 'Lịch nghỉ đã được duyệt và không thể chỉnh sửa.');
        }

        // Nếu trạng thái hiện tại là 0 và yêu cầu là 1, thì cho phép cập nhật
        if ($leave->approved == 0 && $request->input('approved') == 1) {
            $leave->approved = 1;
            $leave->save();
            return redirect()->route('admin.doctor_leaves.index')->with('success', 'Duyệt lịch nghỉ thành công.');
        }

        // Trường hợp còn lại (ví dụ: yêu cầu chuyển từ 0 sang 0) thì không cần cập nhật
        return redirect()->route('admin.doctor_leaves.index')->with('info', 'Không có thay đổi nào được thực hiện.');
    }
}
