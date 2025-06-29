<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Log; // Thêm dòng này ở đầu file
use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{


    public function index()
    {
        // Lấy tối đa 5 phòng ban mỗi trang
        $departments = Department::paginate(5);

        return view('admin.departments.index', compact('departments'));
    }
    public function create()
    {
        return view('admin.departments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:departments,name',
            'description' => 'nullable|string',
        ], [
            'name.required' => '⚠️ Vui lòng nhập tên phòng ban.',
            'name.unique' => '❌ Phòng ban này đã tồn tại.',
            'name.max' => '⚠️ Tên phòng ban không được vượt quá 100 ký tự.',
        ]);

        \App\Models\Department::create($validated);

        return redirect()->route('admin.departments.index')
            ->with('success', '✅ Thêm phòng ban thành công!');
    }


    public function edit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }


    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('departments')->ignore($department->id),
            ],
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Vui lòng nhập tên phòng ban.',
            'name.unique'   => 'Tên phòng ban đã tồn tại.',
            'name.max'      => 'Tên phòng ban không được vượt quá 100 ký tự.',
            'description.string' => 'Mô tả phải là chuỗi.',
        ]);

        $department->update($request->only('name', 'description'));

        return redirect()->route('admin.departments.index')->with('success', '✅ Cập nhật phòng ban thành công!');
    }

    public function destroy(Department $department)
    {
        try {
            // Kiểm tra nếu có bác sĩ thuộc phòng ban này
            if ($department->doctors()->exists()) {
                return redirect()->route('admin.departments.index')
                    ->with('error', "❌ Không thể xóa phòng ban '{$department->name}' vì đang được sử dụng bởi bác sĩ!");
            }

            $department->delete();

            return redirect()->route('admin.departments.index')
                ->with('success', "✅ Đã xóa phòng ban '{$department->name}' thành công!");
        } catch (\Exception $e) {
            Log::error('Lỗi khi xóa phòng ban: ' . $e->getMessage());

            return redirect()->route('admin.departments.index')
                ->with('error', '⚠️ Có lỗi xảy ra khi xóa phòng ban. Vui lòng thử lại sau!');
        }
    }
}
