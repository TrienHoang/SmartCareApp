<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorLeave;
use App\Models\Room;
use App\Notifications\DoctorLeaveApproved;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DoctorLeaveController extends Controller
{


    public function index(Request $request)
    {
        $query = DoctorLeave::with(['doctor.user'])->orderBy('id', 'desc');

        // Validate ngày bắt đầu <= ngày kết thúc
        if ($request->filled('start_date') && $request->filled('end_date')) {
            if ($request->start_date > $request->end_date) {
                return back()->withInput()->withErrors([
                    'start_date' => 'Ngày bắt đầu không được lớn hơn ngày kết thúc.',
                    'end_date' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.'
                ]);
            }

            // Tính số ngày nghỉ
            $start = Carbon::parse($request->start_date);
            $end = Carbon::parse($request->end_date);
            $days = $start->diffInDays($end) + 1;

            if ($days > 3) {
                return back()->withInput()->withErrors([
                    'end_date' => 'Bác sĩ chỉ được nghỉ tối đa 3 ngày. Khoảng bạn chọn là ' . $days . ' ngày.'
                ]);
            }
        }

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
        $request->validate([
            'approved' => 'required|in:0,1',
        ]);

        $leave = DoctorLeave::findOrFail($id);

        // Nếu đã duyệt thì không được chỉnh sửa nữa
        if ($leave->approved == 1) {
            return redirect()->route('admin.doctor_leaves.index')
                ->with('error', 'Lịch nghỉ đã được duyệt và không thể chỉnh sửa.');
        }

        $oldApproved = $leave->approved;

        // Cập nhật trạng thái
        $leave->approved = $request->approved;
        $leave->save();

        // Chỉ gửi thông báo nếu vừa chuyển sang trạng thái được duyệt
        if ($oldApproved == 0 && $leave->approved == 1) {
            $leave->doctor->user->notify(new DoctorLeaveApproved($leave));
        }

        return redirect()->route('admin.doctor_leaves.index')
            ->with('success', 'Cập nhật trạng thái duyệt lịch nghỉ thành công.');
    }
}
