<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::with('category');

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%");
            });
        }

        if ($request->filled('category')) {
            $query->where('service_cate_id', $request->get('category'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $query->orderBy('created_at', 'desc');
        $services = $query->paginate(10);
        $services->appends($request->query());

        $categories = ServiceCategory::where('status', 'active')->orderBy('name')->get();

        return view('admin.services.index', compact('services', 'categories'));
    }

    public function create()
    {
        $categories = ServiceCategory::where('status', 'active')->orderBy('name')->get();
        return view('admin.services.create', compact('categories'));
    }

    public function store(Request $request)
    {
        //  dd($request->all());
      
            $validated = $request->validate($this->getStoreValidationRules(), $this->getValidationMessages());

            $businessErrors = $this->validateBusinessRules($request);
            if (!empty($businessErrors)) {
                return back()->withInput()->withErrors($businessErrors);
            }

            $validated['name'] = trim($validated['name']);
            $validated['description'] = $validated['description'] ? trim($validated['description']) : null;
            $validated['price'] = round($validated['price'], 0);
            $validated['slug'] = str()->slug($validated['name']);

            DB::beginTransaction();
            Service::create($validated);
            DB::commit();

            return redirect()->route('admin.services.index')->with('success', 'Thêm dịch vụ thành công!');
      
            DB::rollBack();
            return back()->withInput()->with('error', 'Có lỗi xảy ra. Vui lòng thử lại.');
        
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $categories = ServiceCategory::where('status', 'active')->orderBy('name')->get();
        return view('admin.services.edit', compact('service', 'categories'));
    }

   
public function update(Request $request, $id)
{
    // ✅ validate trước để Laravel hiển thị lỗi tự động
    $validated = $request->validate(
        $this->getUpdateValidationRules($id),
        $this->getValidationMessages()
    );

    // ✅ kiểm tra trùng tên và khoảng giá
    $businessErrors = $this->validateBusinessRules($request, $id);
    if (!empty($businessErrors)) {
        return back()->withInput()->withErrors($businessErrors);
    }

   
        $validated['name'] = trim($validated['name']);
        $validated['description'] = $validated['description'] ? trim($validated['description']) : null;
        $validated['price'] = round($validated['price'], 0);
        $validated['slug'] = str()->slug($validated['name']);

        $service = Service::findOrFail($id);
        $service->update($validated);

        return redirect()->route('admin.services.index')->with('success', 'Cập nhật dịch vụ thành công!');
  
        return back()->withInput()->with('error', 'Có lỗi xảy ra. Vui lòng thử lại.');
    
}
public function show($id)
{
    $service = Service::with('category')->findOrFail($id);
    return view('admin.services.show', compact('service'));
}

public function destroy($id)
{
    try {
        $service = Service::findOrFail($id);
        $service->delete(); // Laravel sẽ set deleted_at, không xóa thật

        return redirect()->route('admin.services.index')->with('success', 'Đã chuyển dịch vụ vào thùng rác.');
    } catch (\Exception $e) {
        return redirect()->route('admin.services.index')->with('error', 'Không thể xóa dịch vụ.');
    }
}

    private function getStoreValidationRules()
    {
        return [
            'service_cate_id' => 'required|exists:service_categories,id',
            'name' => 'required|string|min:3|max:255|unique:services,name|regex:/^[\p{L}\p{N}\s\-_.,()]+$/u',
            'description' => 'nullable|string|max:2000',
            'price' => 'required|numeric|min:1000|max:99999999',
            'duration' => 'required|integer|min:5|max:600',
            'status' => ['required', Rule::in(['active', 'inactive'])]
        ];
    }

    private function getUpdateValidationRules($id)
    {
        return [
            'service_cate_id' => 'required|exists:service_categories,id',
            'name' => ['required', 'string', 'min:3', 'max:255', Rule::unique('services', 'name')->ignore($id), 'regex:/^[\p{L}\p{N}\s\-_.,()]+$/u'],
            'description' => 'nullable|string|max:2000',
            'price' => 'required|numeric|min:1000|max:99999999',
            'duration' => 'required|integer|min:5|max:600',
            'status' => ['required', Rule::in(['active', 'inactive'])]
        ];
    }

    private function getValidationMessages()
{
    return [
        'name.required' => 'Tên dịch vụ không được để trống.',
        'name.unique' => 'Tên dịch vụ đã tồn tại.',
        'name.min' => 'Tên dịch vụ phải có ít nhất :min ký tự.',
        'name.max' => 'Tên dịch vụ không vượt quá :max ký tự.',
        'name.regex' => 'Tên dịch vụ không hợp lệ, chỉ cho phép chữ cái, số và một số ký tự như - _ . , ( ).',

        'description.max' => 'Mô tả không được vượt quá :max ký tự.',
        
        'price.required' => 'Giá dịch vụ không được để trống.',
        'price.numeric' => 'Giá dịch vụ phải là số.',
        'price.min' => 'Giá dịch vụ tối thiểu là 1.000đ.',
        'price.max' => 'Giá dịch vụ không vượt quá 99 triệu.',

        'duration.required' => 'Thời gian không được để trống.',
        'duration.integer' => 'Thời gian phải là số nguyên.',
        'duration.min' => 'Thời gian tối thiểu là :min phút.',
        'duration.max' => 'Thời gian tối đa là :max phút.',

        'service_cate_id.required' => 'Vui lòng chọn danh mục.',
        'service_cate_id.exists' => 'Danh mục đã chọn không tồn tại.',

        'status.required' => 'Vui lòng chọn trạng thái.',
        'status.in' => 'Trạng thái không hợp lệ.'
    ];
}


    private function validateBusinessRules(Request $request, $id = null)
    {
        $errors = [];
        $normalized = trim(strtolower($request->name));
        $exists = Service::whereRaw('LOWER(TRIM(name)) = ?', [$normalized])
            ->when($id, fn($q) => $q->where('id', '!=', $id))
            ->exists();

        if ($exists) {
            $errors['name'] = 'Tên dịch vụ này đã tồn tại (không phân biệt chữ hoa/thường).';
        }

        $category = ServiceCategory::find($request->service_cate_id);
        if ($category && isset($category->price_range)) {
            $range = json_decode($category->price_range, true);
            if ($range && ($request->price < $range['min'] || $request->price > $range['max'])) {
                $errors['price'] = "Giá phải trong khoảng từ " . number_format($range['min']) . "đ đến " . number_format($range['max']) . "đ.";
            }
        }

        return $errors;
    }
}
