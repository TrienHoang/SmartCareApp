<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Department;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('department')->paginate(10);
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('admin.rooms.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:100',
                'department_id' => 'required|exists:departments,id',
                'description' => 'nullable|string',
                'status' => 'nullable|in:active,inactive',
            ],
            [
                'name.required' => 'Tên phòng là bắt buộc.',
                'name.max' => 'Tên phòng không được vượt quá 100 ký tự.',
                'department_id.required' => 'Phòng phải thuộc một khoa.',
                'department_id.exists' => 'Phòng phải thuộc một khoa hợp lệ.',
            ]
        );

        Room::create($request->all());

        return redirect()->route('admin.rooms.index')->with('success', 'Tạo phòng thành công!');
    }

    public function edit($id)
    {
        $room = Room::findOrFail($id);
        $departments = Department::all();
        return view('admin.rooms.edit', compact('room', 'departments'));
    }

    public function update(Request $request, $id)
    {
        // validate input
        $request->validate([
            'name' => 'nullable|string|max:100',
            'department_id' => 'nullable|exists:departments,id',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ]);
    
        // lấy room theo id
        $room = Room::findOrFail($id);
    
        // update với field cho phép
        $room->update($request->only(['name', 'department_id', 'description', 'status']));
    
        return redirect()->route('admin.rooms.index')->with('success', 'Cập nhật phòng thành công!');
    }

    public function show($id)
    {
        $room = Room::with('department')->findOrFail($id);
        return view('admin.rooms.show', compact('room'));
    }

    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete(); // Soft delete

        return redirect()->route('admin.rooms.index')->with('success', 'Xoá phòng thành công!');
    }

    // ✅ Hàm đổi trạng thái
    public function toggleStatus($id)
    {
        $room = Room::findOrFail($id);
        if ($room->status === 'active') {
            $room->status = 'inactive';
        } elseif ($room->status === 'inactive') {
            $room->status = 'active';
        }
        $room->save();

        return redirect()->back()->with('success', 'Đã đổi trạng thái phòng thành công!');
    }

    // Hiển thị danh sách phòng đã xóa mềm
    public function trash()
    {
        $rooms = Room::onlyTrashed()->with('department')->get();
        return view('admin.rooms.trash', compact('rooms'));
    }

    // Khôi phục lại phòng đã xóa
    public function restore($id)
    {
        $room = Room::onlyTrashed()->findOrFail($id);
        $room->restore();

        return redirect()->route('admin.rooms.trash')->with('success', 'Khôi phục phòng thành công!');
    }
}
