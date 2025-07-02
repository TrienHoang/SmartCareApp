<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\DoctorLeave;
use App\Models\User;
use App\Notifications\DoctorLeaveCreated;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorLeaveController extends Controller
{
    public function index()
    {
        $doctorId = Auth::user()->doctor->id;
        $leaves = DoctorLeave::where('doctor_id', $doctorId)
            ->orderByDesc('start_date')
            ->get();

        return view('doctor.doctor_leaves.index', compact('leaves'));
    }

    public function create()
    {
        return view('doctor.doctor_leaves.create');
    }

    public function store(Request $request)
    {
        $minStartDate = Carbon::today()->addDays(2)->format('Y-m-d');

        $request->validate([
            'start_date' => ['required', 'date', 'after_or_equal:' . $minStartDate],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string', 'max:1000'],
        ], [
            'start_date.required' => 'Vui lòng chọn ngày bắt đầu.',
            'start_date.after_or_equal' => 'Lịch phải được đăng ký trước ít nhất 2 ngày.',
            'end_date.required' => 'Vui lòng chọn ngày kết thúc.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
            'reason.required' => 'Vui lòng nhập lý do nghỉ phép.',
        ]);

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $days = $start->diffInDays($end) + 1;

        if ($days > 7) {
            return redirect()->back()
                ->withErrors(['end_date' => 'Không được nghỉ quá 7 ngày.'])
                ->withInput();
        }

        $doctorId = Auth::user()->doctor->id;

        $hasApprovedLeaveThisMonth = DoctorLeave::where('doctor_id', $doctorId)
            ->whereMonth('start_date', $start->month)
            ->whereYear('start_date', $start->year)
            ->where('approved', true)
            ->exists();

        if ($hasApprovedLeaveThisMonth) {
            return redirect()->back()
                ->withErrors(['start_date' => 'Bạn chỉ được đăng ký lịch nghỉ 1 lần trong mỗi tháng.'])
                ->withInput();
        }

        $leave = DoctorLeave::create([
            'doctor_id' => $doctorId,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'approved' => false,
        ]);

        // Gửi thông báo đến tất cả admin
        $admins = User::where('role_id', 1)->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\DoctorLeaveCreated($leave, false));
        }

        return redirect()->route('doctor.leaves.index')
            ->with('success', 'Đăng ký lịch nghỉ thành công! Đã gửi thông báo đến quản trị viên.');
    }

    public function show($id)
    {
        $leave = DoctorLeave::where('id', $id)
            ->where('doctor_id', Auth::user()->doctor->id)
            ->firstOrFail();

        return view('doctor.doctor_leaves.show', compact('leave'));
    }

    public function edit($id)
    {
        $leave = DoctorLeave::where('id', $id)
            ->where('doctor_id', Auth::user()->doctor->id)
            ->firstOrFail();

        if ($leave->approved) {
            return redirect()->route('doctor.leaves.index')
                ->with('error', 'Đơn đăng ký đã được duyệt. Không thể thay đổi!');
        }

        return view('doctor.doctor_leaves.edit', compact('leave'));
    }

    public function update(Request $request, $id)
    {
        $leave = DoctorLeave::where('id', $id)
            ->where('doctor_id', Auth::user()->doctor->id)
            ->firstOrFail();

        if ($leave->approved) {
            return redirect()->route('doctor.leaves.index')->with('error', 'Lịch nghỉ đã được duyệt, không thể cập nhật.');
        }

        $minStartDate = Carbon::today()->addDays(2)->format('Y-m-d');

        $request->validate([
            'start_date' => ['required', 'date', 'after_or_equal:' . $minStartDate],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string', 'max:1000'],
        ], [
            'start_date.required' => 'Vui lòng chọn ngày bắt đầu.',
            'start_date.after_or_equal' => 'Lịch phải được đăng ký trước ít nhất 2 ngày.',
            'end_date.required' => 'Vui lòng chọn ngày kết thúc.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
            'reason.required' => 'Vui lòng nhập lý do nghỉ phép.',
        ]);

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $days = $start->diffInDays($end) + 1;

        if ($days > 7) {
            return redirect()->back()
                ->withErrors(['end_date' => 'Không được nghỉ quá 7 ngày.'])
                ->withInput();
        }

        // Kiểm tra sự thay đổi
        $isChanged =
            $leave->start_date != $request->start_date ||
            $leave->end_date != $request->end_date ||
            $leave->reason != $request->reason;

        if ($isChanged) {
            $leave->update([
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'reason' => $request->reason,
            ]);

            // Gửi thông báo cập nhật đến admin
            $admins = User::where('role_id', 1)->get();
            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\DoctorLeaveCreated($leave, true));
            }

            return redirect()->route('doctor.leaves.index')->with('success', 'Cập nhật lịch nghỉ thành công! Đã gửi thông báo đến quản trị viên.');
        }

        return redirect()->route('doctor.leaves.index')->with('success', 'Lịch nghỉ không thay đổi, không cần gửi thông báo.');
    }

    public function destroy($id)
    {
        $leave = DoctorLeave::where('id', $id)
            ->where('doctor_id', Auth::user()->doctor->id)
            ->firstOrFail();

        if ($leave->approved) {
            return redirect()->route('doctor.leaves.index')
                ->with('error', 'Đơn nghỉ đã được duyệt. Không thể xóa!');
        }

        $leave->delete();

        return redirect()->route('doctor.leaves.index')->with('success', 'Đã xóa đơn nghỉ thành công.');
    }
}
