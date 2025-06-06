<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // Danh sách dịch vụ
    public function index()
    {
        $services = Service::with('category')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.services.index', compact('services'));
    }

    // Form thêm mới
    public function create()
    {
        $categories = ServiceCategory::all();
        return view('admin.services.create', compact('categories'));
    }

    // Lưu mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_cate_id' => 'required|exists:service_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);

        Service::create($validated);
        return redirect()->route('admin.services.index')->with('success', 'Thêm dịch vụ thành công!');
    }

    // Form sửa
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $categories = ServiceCategory::all();
        return view('admin.services.edit', compact('service', 'categories'));
    }

    // Cập nhật
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'service_cate_id' => 'required|exists:service_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);

        $service = Service::findOrFail($id);
        $service->update($validated);

        return redirect()->route('admin.services.index')->with('success', 'Cập nhật dịch vụ thành công!');
    }

    // Xóa
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Xóa dịch vụ thành công!');
    }

    // Xem chi tiết
    public function show($id)
    {
        $service = Service::with('category')->findOrFail($id);
        return view('admin.services.show', compact('service'));
    }
}
