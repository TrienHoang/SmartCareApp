<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::with(['doctors.user'])
            ->withCount(['doctors', 'services']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status') && in_array($request->status, ['active', 'inactive'])) {
            $query->where('is_active', $request->status === 'active' ? 1 : 0);
        }

        if ($request->filled('created_at')) {
            $query->whereDate('created_at', $request->created_at);
        }

        if ($request->filled('empty') && $request->empty == '1') {
            $query->has('doctors', '=', 0);
        }

        $departments = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('admin.departments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:departments,name',
            'description' => 'nullable|string|max:1000',
            'is_active'   => 'required|in:0,1',
        ], [
            'name.required'      => '⚠️ Vui lòng nhập tên phòng ban.',
            'name.unique'        => '❌ Phòng ban này đã tồn tại.',
            'name.max'           => '⚠️ Tên phòng ban không được vượt quá 100 ký tự.',
            'description.max'    => '⚠️ Mô tả không vượt quá 1000 ký tự.',
            'is_active.required' => '⚠️ Vui lòng chọn trạng thái hoạt động.',
            'is_active.in'       => '⚠️ Trạng thái không hợp lệ.',
        ]);

        Department::create([
            'name'        => trim($validated['name']),
            'description' => $validated['description'] ?? null,
            'is_active'   => $validated['is_active'] == '1' ? 1 : 0,
        ]);

        return redirect()->route('admin.departments.index')
            ->with('success', '✅ Thêm phòng ban thành công!');
    }

    public function edit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('departments')->ignore($department->id),
            ],
            'description' => 'nullable|string',
            'is_active' => 'required|in:0,1',
        ], [
            'name.required' => '⚠️ Vui lòng nhập tên phòng ban.',
            'name.unique'   => '❌ Tên phòng ban đã tồn tại.',
            'name.max'      => '⚠️ Tên phòng ban không được vượt quá 100 ký tự.',
            'description.string' => '⚠️ Mô tả phải là chuỗi.',
            'is_active.required' => '⚠️ Vui lòng chọn trạng thái.',
            'is_active.in' => '⚠️ Trạng thái không hợp lệ.',
        ]);

        $department->update($validated);

        return redirect()->route('admin.departments.index')
            ->with('success', '✅ Cập nhật phòng ban thành công!');
    }

    public function destroy(Department $department)
    {
        try {
            if ($department->doctors()->exists() || $department->services()->exists()) {
                return redirect()->route('admin.departments.index')
                    ->with('error', "❌ Không thể xóa phòng ban '{$department->name}' vì đang được sử dụng bởi bác sĩ hoặc dịch vụ!");
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

    public function show(Department $department)
    {
        $department->load([
            'doctors.user',
            'rooms',
            'services' => function ($query) {
                $query->where('status', 'active') // 👉 chỉ dịch vụ đang hoạt động
                    ->orderBy('name');
            },
        ]);

        return view('admin.departments.show', compact('department'));
    }
}
