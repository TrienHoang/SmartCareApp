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
    // Danh sách dịch vụ với tìm kiếm và lọc
    public function index(Request $request)
    {
        $query = Service::with('category');

        // Tìm kiếm theo tên và mô tả
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Lọc theo danh mục
        if ($request->filled('category')) {
            $query->where('service_cate_id', $request->get('category'));
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Sắp xếp theo thời gian tạo mới nhất
        $query->orderBy('created_at', 'desc');

        // Phân trang
        $services = $query->paginate(10);
        $services->appends($request->query());

        // Lấy danh sách categories cho filter dropdown
        $categories = ServiceCategory::orderBy('name')->get();

        return view('admin.services.index', compact('services', 'categories'));
    }

    // Form thêm mới
    public function create()
    {
        $categories = ServiceCategory::orderBy('name')->get();
        return view('admin.services.create', compact('categories'));
    }

    // Validation rules cho store
    private function getStoreValidationRules()
    {
        return [
            'service_cate_id' => [
                'required',
                'integer',
                'exists:service_categories,id'
            ],
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'unique:services,name',
                'regex:/^[\p{L}\p{N}\s\-_.,()]+$/u' // Chỉ cho phép chữ, số, khoảng trắng và một số ký tự đặc biệt
            ],
            'description' => [
                'nullable',
                'string',
                'max:2000'
            ],
            'price' => [
                'required',
                'numeric',
                'min:1000', // Giá tối thiểu 1000 VND
                'max:99999999' // Giá tối đa 99 triệu
            ],
            'duration' => [
                'required',
                'integer',
                'min:5', // Tối thiểu 5 phút
                'max:600' // Tối đa 10 giờ (600 phút)
            ],
            'status' => [
                'required',
                Rule::in(['active', 'inactive'])
            ]
        ];
    }

    // Validation rules cho update
    private function getUpdateValidationRules($serviceId)
    {
        return [
            'service_cate_id' => [
                'required',
                'integer',
                'exists:service_categories,id'
            ],
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('services', 'name')->ignore($serviceId),
                'regex:/^[\p{L}\p{N}\s\-_.,()]+$/u'
            ],
            'description' => [
                'nullable',
                'string',
                'max:2000'
            ],
            'price' => [
                'required',
                'numeric',
                'min:1000',
                'max:99999999'
            ],
            'duration' => [
                'required',
                'integer',
                'min:5',
                'max:600'
            ],
            'status' => [
                'required',
                Rule::in(['active', 'inactive'])
            ]
        ];
    }

    // Custom validation messages
    private function getValidationMessages()
    {
        return [
            // Service Category
            'service_cate_id.required' => 'Vui lòng chọn danh mục dịch vụ.',
            'service_cate_id.integer' => 'ID danh mục dịch vụ không hợp lệ.',
            'service_cate_id.exists' => 'Danh mục dịch vụ không tồn tại trong hệ thống.',

            // Name
            'name.required' => 'Tên dịch vụ không được để trống.',
            'name.string' => 'Tên dịch vụ phải là chuỗi ký tự.',
            'name.min' => 'Tên dịch vụ phải có ít nhất :min ký tự.',
            'name.max' => 'Tên dịch vụ không được vượt quá :max ký tự.',
            'name.unique' => 'Tên dịch vụ này đã tồn tại, vui lòng chọn tên khác.',
            'name.regex' => 'Tên dịch vụ chỉ được chứa chữ cái, số và các ký tự: - _ . , ( )',

            // Description
            'description.string' => 'Mô tả dịch vụ phải là chuỗi ký tự.',
            'description.max' => 'Mô tả dịch vụ không được vượt quá :max ký tự.',

            // Price
            'price.required' => 'Giá dịch vụ không được để trống.',
            'price.numeric' => 'Giá dịch vụ phải là số.',
            'price.min' => 'Giá dịch vụ phải tối thiểu :min VND (1.000 đ).',
            'price.max' => 'Giá dịch vụ không được vượt quá :max VND.',

            // Duration
            'duration.required' => 'Thời gian thực hiện không được để trống.',
            'duration.integer' => 'Thời gian thực hiện phải là số nguyên.',
            'duration.min' => 'Thời gian thực hiện phải ít nhất :min phút.',
            'duration.max' => 'Thời gian thực hiện không được vượt quá :max phút (10 giờ).',

            // Status
            'status.required' => 'Vui lòng chọn trạng thái hoạt động.',
            'status.in' => 'Trạng thái phải là "Kích hoạt" hoặc "Tạm ngưng".',
        ];
    }

    // Additional validation method
    private function validateBusinessRules(Request $request, $serviceId = null)
    {
        $errors = [];

        // Kiểm tra tên dịch vụ không được trùng với tên đã normalize
        $normalizedName = trim(strtolower($request->name));
        $existingService = Service::whereRaw('LOWER(TRIM(name)) = ?', [$normalizedName])
            ->when($serviceId, function ($query) use ($serviceId) {
                return $query->where('id', '!=', $serviceId);
            })
            ->first();

        if ($existingService) {
            $errors['name'] = 'Tên dịch vụ này đã tồn tại (không phân biệt chữ hoa/thường).';
        }

        // Kiểm tra logic giá theo danh mục
        if ($request->filled('service_cate_id') && $request->filled('price')) {
            $category = ServiceCategory::find($request->service_cate_id);
            if ($category && isset($category->price_range)) {
                $priceRange = json_decode($category->price_range, true);
                if (
                    $priceRange &&
                    ($request->price < $priceRange['min'] || $request->price > $priceRange['max'])
                ) {
                    $errors['price'] = "Giá dịch vụ phải trong khoảng " .
                        number_format($priceRange['min']) . "đ - " .
                        number_format($priceRange['max']) . "đ cho danh mục này.";
                }
            }
        }

        // Kiểm tra từ khóa cấm trong tên và mô tả
        $bannedWords = ['spam', 'fake', 'scam', 'xxx', 'adult']; // Có thể config từ database
        $content = strtolower($request->name . ' ' . $request->description);

        foreach ($bannedWords as $word) {
            if (strpos($content, $word) !== false) {
                $errors['name'] = 'Tên hoặc mô tả dịch vụ chứa từ khóa không được phép.';
                break;
            }
        }

        return $errors;
    }

    // Lưu mới với validation cải thiện
    public function store(Request $request)
    {
        try {
            // Validate cơ bản
            $validated = $request->validate(
                $this->getStoreValidationRules(),
                $this->getValidationMessages()
            );

            // Validate business rules
            $businessErrors = $this->validateBusinessRules($request);
            if (!empty($businessErrors)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($businessErrors);
            }

            // Xử lý dữ liệu trước khi lưu
            $validated['name'] = trim($validated['name']);
            $validated['description'] = $validated['description'] ? trim($validated['description']) : null;
            $validated['price'] = round($validated['price'], 0); // Làm tròn giá
            $validated['slug'] = str()->slug($validated['name']); // Tạo slug

            DB::beginTransaction();

            Service::create($validated);

            DB::commit();

            return redirect()->route('admin.services.index')
                ->with('success', 'Thêm dịch vụ thành công!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi hệ thống xảy ra. Vui lòng thử lại sau.');
        }
    }
    
    // Chi tiết dịch vụ
    public function show($id)
    {
        try {
            $service = Service::with(['category', 'appointments' => function ($query) {
                $query->latest()->take(10);
            }])->findOrFail($id);

            $stats = [
                'total_bookings' => $service->appointments()->count(),
                'completed_bookings' => $service->appointments()->where('status', 'completed')->count(),
                'pending_bookings' => $service->appointments()->where('status', 'pending')->count(),
                'cancelled_bookings' => $service->appointments()->where('status', 'cancelled')->count(),
                // 'total_revenue' => $service->appointments()->where('status', 'completed')->sum('total_amount'),
            ];

            return view('admin.services.show', compact('service', 'stats'));
        } catch (\Exception $e) {
            return redirect()->route('admin.services.index')
                ->with('error', 'Không tìm thấy dịch vụ hoặc có lỗi xảy ra.');
        }
    }



    // Form sửa
    public function edit($id)
    {
        try {
            $service = Service::findOrFail($id);
            $categories = ServiceCategory::orderBy('name')->get();
            return view('admin.services.edit', compact('service', 'categories'));
        } catch (\Exception $e) {
            return redirect()->route('admin.services.index')
                ->with('error', 'Không tìm thấy dịch vụ.');
        }
    }

    // Cập nhật với validation cải thiện
    public function update(Request $request, $id)
    {
        try {
            $service = Service::findOrFail($id);

            // Validate cơ bản
            $validated = $request->validate(
                $this->getUpdateValidationRules($id),
                $this->getValidationMessages()
            );

            // Validate business rules
            $businessErrors = $this->validateBusinessRules($request, $id);
            if (!empty($businessErrors)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($businessErrors);
            }

            // Xử lý dữ liệu trước khi cập nhật
            $validated['name'] = trim($validated['name']);
            $validated['description'] = $validated['description'] ? trim($validated['description']) : null;
            $validated['price'] = round($validated['price'], 0);

            // Chỉ cập nhật slug nếu tên thay đổi
            if ($service->name !== $validated['name']) {
                $validated['slug'] = str()->slug($validated['name']);
            }

            DB::beginTransaction();

            $service->update($validated);

            DB::commit();

            return redirect()->route('admin.services.index')
                ->with('success', 'Cập nhật dịch vụ thành công!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi hệ thống xảy ra. Vui lòng thử lại sau.');
        }
    }

    // Xóa
    public function destroy($id)
    {
        try {
            $service = Service::findOrFail($id);

            // Kiểm tra xem dịch vụ có đang được sử dụng không
            if ($service->bookings()->exists()) {
                return redirect()->route('admin.services.index')
                    ->with('error', 'Không thể xóa dịch vụ này vì đã có khách hàng đặt lịch.');
            }

            DB::beginTransaction();

            $service->delete();

            DB::commit();

            return redirect()->route('admin.services.index')
                ->with('success', 'Xóa dịch vụ thành công!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('admin.services.index')
                ->with('error', 'Có lỗi xảy ra khi xóa dịch vụ.');
        }
    }

    // Bulk operations và các method khác giữ nguyên như cũ...
    public function bulkDelete(Request $request)
    {
        try {
            // Validate bulk delete request

            $request->validate([
                'service_ids' => 'required|array|min:1',
                'service_ids.*' => 'integer|exists:services,id'
            ], [
                'service_ids.required' => 'Vui lòng chọn ít nhất một dịch vụ để xóa.',
                'service_ids.array' => 'Dữ liệu không hợp lệ.',
                'service_ids.min' => 'Vui lòng chọn ít nhất một dịch vụ.',
                'service_ids.*.exists' => 'Một số dịch vụ không tồn tại.'
            ]);

            $serviceIds = $request->input('service_ids');

            // Kiểm tra dịch vụ có booking không
            $servicesWithBookings = Service::whereIn('id', $serviceIds)
                ->whereHas('bookings')
                ->pluck('name')
                ->toArray();

            if (!empty($servicesWithBookings)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa các dịch vụ sau vì đã có khách hàng đặt lịch: ' . implode(', ', $servicesWithBookings)
                ]);
            }

            DB::beginTransaction();

            $deletedCount = Service::whereIn('id', $serviceIds)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Đã xóa thành công {$deletedCount} dịch vụ."
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa dịch vụ.'
            ]);
        }
    }

    // Toggle status
    public function toggleStatus($id)
    {
        try {
            $service = Service::findOrFail($id);

            DB::beginTransaction();

            $service->status = $service->status === 'active' ? 'inactive' : 'active';
            $service->save();

            DB::commit();

            $statusText = $service->status === 'active' ? 'kích hoạt' : 'vô hiệu hóa';

            return response()->json([
                'success' => true,
                'message' => "Đã {$statusText} dịch vụ thành công.",
                'new_status' => $service->status
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thay đổi trạng thái.'
            ]);
        }
    }

    // API endpoint để lấy thông tin dịch vụ
    public function getServiceInfo($id)
    {
        try {
            $service = Service::with('category')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $service->id,
                    'name' => $service->name,
                    'description' => $service->description,
                    'price' => $service->price,
                    'duration' => $service->duration,
                    'status' => $service->status,
                    'category' => $service->category ? $service->category->name : 'Chưa phân loại',
                    'formatted_price' => number_format($service->price) . 'đ',
                    'created_at' => $service->created_at->format('d/m/Y H:i'),
                    'updated_at' => $service->updated_at->format('d/m/Y H:i'),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy dịch vụ.'
            ]);
        }
    }
}
