<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorLeave;
use App\Models\Room;
use App\Notifications\DoctorLeaveApproved;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;

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

        $leave = DoctorLeave::with('doctor')->findOrFail($id);

        // Nếu đã duyệt thì không được chỉnh sửa nữa
        if ($leave->approved == 1) {
            return redirect()->route('admin.doctor_leaves.index')
                ->with('error', 'Lịch nghỉ đã được duyệt và không thể chỉnh sửa.');
        }

        $oldApproved = $leave->approved;

        // Cập nhật trạng thái
        $leave->approved = $request->approved;
        $leave->save();

        // Nếu đơn mới được duyệt
        if ($oldApproved == 0 && $leave->approved == 1) {

            // Gửi thông báo cho bác sĩ xin nghỉ
            $leave->doctor->user->notify(new DoctorLeaveApproved($leave));

            // Nếu có bác sĩ thay thế thì chuyển lịch hẹn
            if ($leave->replacement_doctor_id) {
                DB::transaction(function () use ($leave) {
                    $replacement = Doctor::find($leave->replacement_doctor_id);

                    $appointments = Appointment::where('doctor_id', $leave->doctor_id)
                        ->whereBetween(DB::raw('DATE(appointment_time)'), [$leave->start_date, $leave->end_date])
                        ->where('status', '!=', 'cancelled')
                        ->get();

                    foreach ($appointments as $appointment) {
                        $appointment->doctor_id = $replacement->id;
                        $appointment->save();

                        // Thông báo cho bệnh nhân về sự thay đổi
                        $appointment->user?->notify(
                            new \App\Notifications\AppointmentReassigned(
                                $appointment,
                                "Cuộc hẹn đã được chuyển sang bác sĩ {$replacement->name} do bác sĩ {$leave->doctor->name} nghỉ."
                            )
                        );
                    }
                });
            }
        }

        return redirect()->route('admin.doctor_leaves.index')
            ->with('success', 'Cập nhật trạng thái duyệt lịch nghỉ thành công.');
    }
}
