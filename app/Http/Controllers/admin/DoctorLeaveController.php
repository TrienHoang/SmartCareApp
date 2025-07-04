<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorLeave;
use App\Models\Room;
use App\Notifications\DoctorLeaveApproved;
use Illuminate\Http\Request;

class DoctorLeaveController extends Controller
{
    public function index(Request $request)
    {
        $query = DoctorLeave::with(['doctor.user'])->orderBy('id', 'desc');

        // Lọc theo tên bác sĩ
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->whereHas('doctor.user', function ($q) use ($keyword) {
                $q->where('full_name', 'like', '%' . $keyword . '%');
            });
        }

        // Lọc theo ngày bắt đầu nghỉ
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }

        // Lọc theo ngày kết thúc nghỉ
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }

        // Lọc theo trạng thái duyệt
        if ($request->filled('approved') && in_array($request->approved, ['0', '1'])) {
            $query->where('approved', $request->approved);
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
            // Gửi notification nếu muốn (hoặc có thể gửi khi duyệt thành công ở nơi khác)
            $leave->doctor->user->notify(new DoctorLeaveApproved($leave));

            return redirect()->route('admin.doctor_leaves.index')
                ->with('error', 'Lịch nghỉ đã được duyệt và không thể chỉnh sửa.');
        }

        // Kiểm tra có thay đổi trạng thái approved không
        if ($leave->approved == $request->approved) {
            return redirect()->route('admin.doctor_leaves.index')
                ->with('info', 'Không có thay đổi nào được thực hiện.');
        }

        // Cập nhật trạng thái approved
        $leave->approved = $request->approved;
        $leave->save();

        // Nếu trạng thái chuyển sang duyệt, gửi notification
        if ($leave->approved == 1) {
            $leave->doctor->user->notify(new DoctorLeaveApproved($leave));
        }

        return redirect()->route('admin.doctor_leaves.index')
            ->with('success', 'Cập nhật trạng thái duyệt lịch nghỉ thành công.');
    }
}
