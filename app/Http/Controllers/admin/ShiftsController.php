<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shift;
use Illuminate\Support\Facades\Auth;

class ShiftsController extends Controller
{
    public function index()
    {
        $shifts = Shift::paginate(10); // Lấy danh sách ca làm việc với phân trang

        return view('admin.shifts.index', compact('shifts'));
    }

    public function create()
    {
        return view('admin.shifts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ], [
            'name.required' => 'Tên ca làm không được để trống.',
            'start_time.required' => 'Thời gian bắt đầu không được để trống.',
            'start_time.date_format' => 'Thời gian bắt đầu phải đúng định dạng HH:mm.',
            'end_time.required' => 'Thời gian kết thúc không được để trống.',
            'end_time.date_format' => 'Thời gian kết thúc phải đúng định dạng HH:mm.',
            'end_time.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
        ]);

        Shift::create([
            'name' => $request->name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('admin.shifts.index')->with('success', 'Ca làm việc đã được tạo thành công.');
    }

    public function edit(Shift $shift)
    {
        return view('admin.shifts.edit', compact('shift'));
    }

    public function update(Request $request, Shift $shift)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ], [
            'name.required' => 'Tên ca làm không được để trống.',
            'start_time.required' => 'Thời gian bắt đầu không được để trống.',
            'start_time.date_format' => 'Thời gian bắt đầu phải đúng định dạng HH:mm.',
            'end_time.required' => 'Thời gian kết thúc không được để trống.',
            'end_time.date_format' => 'Thời gian kết thúc phải đúng định dạng HH:mm.',
            'end_time.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
        ]);

        $shift->update([
            'name' => $request->name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect()->route('admin.shifts.index')->with('success', 'Ca làm việc đã được cập nhật thành công.');
    }

    public function destroy(Shift $shift)
    {
        $shift->delete();
        return redirect()->route('admin.shifts.index')->with('success', 'Ca làm việc đã được xóa thành công.');
    }
}
