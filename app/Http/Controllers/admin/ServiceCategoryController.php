<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceCategoryController extends Controller
{
    // Hiển thị danh sách danh mục dịch vụ
    public function index(Request $request)
    {
        $query = ServiceCategory::query();

        // Tìm kiếm theo tên danh mục
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $categories = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    // Hiển thị form tạo mới danh mục dịch vụ
    public function create()
    {
        return view('admin.categories.create');
    }

    // Lưu danh mục dịch vụ mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:service_categories,name'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'in:active,inactive'],
        ], [
            'name.required' => 'Tên danh mục không được để trống.',
            'name.string' => 'Tên danh mục phải là chuỗi ký tự.',
            'name.max' => 'Tên danh mục không được vượt quá 100 ký tự.',
            'name.unique' => 'Tên danh mục đã tồn tại.',
            'description.string' => 'Mô tả phải là chuỗi ký tự.',
            'status.in' => 'Trạng thái phải là một trong các giá trị: active hoặc inactive.',
        ]);

        ServiceCategory::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'] ?? 'active',
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Thêm danh mục thành công!');
    }

    // Hiển thị form chỉnh sửa danh mục dịch vụ
    public function edit($id)
    {
        $serviceCategory = ServiceCategory::findOrFail($id);
        return view('admin.categories.edit', compact('serviceCategory'));
    }

    // Cập nhật danh mục dịch vụ

    public function update(Request $request, $id)
    {
        $serviceCategory = ServiceCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:service_categories,name,' . $serviceCategory->id],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'in:active,inactive'],
        ], [
            'name.required' => 'Tên danh mục không được để trống.',
            'name.string' => 'Tên danh mục phải là chuỗi ký tự.',
            'name.max' => 'Tên danh mục không được vượt quá 100 ký tự.',
            'name.unique' => 'Tên danh mục đã tồn tại.',
            'description.string' => 'Mô tả phải là chuỗi ký tự.',
            'status.in' => 'Trạng thái phải là một trong các giá trị: active hoặc inactive.',
        ]);

        $serviceCategory->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Cập nhật danh mục dịch vụ thành công!');
    }


    public function destroy($id)
    {
        $category = ServiceCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Xóa danh mục thành công.');
    }

    // Hiển thị chi tiết danh mục dịch vụ
    public function show($id)
    {
        $serviceCategory = ServiceCategory::findOrFail($id);
        return view('admin.categories.show', compact('serviceCategory'));
    }
    public function toggleStatus($id)
    {
        $category = ServiceCategory::findOrFail($id);
        $category->status = $category->status === 'active' ? 'inactive' : 'active';
        $category->save();

        return redirect()->back()->with('success', 'Trạng thái danh mục đã được cập nhật.');
    }
}
