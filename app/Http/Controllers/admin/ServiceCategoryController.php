<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceCategoryController extends Controller
{
    // Hiển thị danh sách danh mục dịch vụ
    public function index()
    {
        $categories = ServiceCategory::orderBy('created_at', 'desc')->paginate(10);
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
            'name' => 'required|max:100',
            'description' => 'nullable|string',
        ]);
        // Kiểm tra thủ công nếu name rỗng
        if (trim($request->input('name')) === '') {
            return back()
                ->withErrors(['name' => 'Vui lòng nhập tên danh mục.']) // Thông báo lỗi
                ->withInput(); // Giữ lại dữ liệu cũ
        }


        ServiceCategory::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Thêm danh mục thành công!');
    }

    // Hiển thị form chỉnh sửa danh mục dịch vụ
    public function edit($id)
    {
        $serviceCategory = ServiceCategory::findOrFail($id);

        // Nếu có submit form (method POST hoặc PUT), kiểm tra rỗng
        if (request()->isMethod('post') || request()->isMethod('put')) {
            if (trim(request()->input('name')) === '') {
                return back()
                    ->withErrors(['name' => 'Vui lòng nhập tên danh mục.'])
                    ->withInput();
            }
        }

        return view('admin.categories.edit', compact('serviceCategory'));
    }


    // Cập nhật danh mục dịch vụ
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:service_categories,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $serviceCategory = ServiceCategory::findOrFail($id);
        $serviceCategory->name = $validated['name'];
        $serviceCategory->description = $validated['description'] ?? null;
        $serviceCategory->save();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Cập nhật danh mục dịch vụ thành công!');
    }

    // Xóa danh mục dịch vụ
    public function destroy($id)
    {
        $serviceCategory = ServiceCategory::findOrFail($id);
        $serviceCategory->delete();

        return redirect()->route('admin.categories.index')
            ->with('message', 'Xóa danh mục dịch vụ thành công!');
    }

    // Hiển thị chi tiết danh mục dịch vụ
    public function show($id)
    {
        $serviceCategory = ServiceCategory::findOrFail($id);
        return view('admin.categories.show', compact('serviceCategory'));
    }
}
