<?php

namespace App\Http\Controllers\admin;

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
        // Logic to show the form for editing a doctor leave
        $leave = DoctorLeave::findOrFail($id);
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

        // Cập nhật trạng thái duyệt
        $leave->approved = $request->input('approved');

        // Lưu vào CSDL
        $leave->save();

        // Quay lại danh sách với thông báo thành công
        return redirect()->route('admin.doctor_leaves.index')->with('success', 'Cập nhật trạng thái duyệt thành công.');
    }
}
