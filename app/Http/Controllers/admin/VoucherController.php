<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promotion;
use Carbon\Carbon;

class VoucherController extends Controller
{
    // Hiển thị danh sách promotion
    public function index(Request $request)
    {
        $query = Promotion::query();

        // Tìm theo mã promotion (code) với tìm kiếm tương đối (like)
        if ($request->filled('code')) {
            $query->where('code', 'like', '%' . $request->code . '%');
        }

        // Lọc theo phần trăm giảm giá với phạm vi (tối thiểu và tối đa)
        if ($request->filled('discount_percentage_min')) {
            $request->validate([
                'discount_percentage_min' => 'numeric|min:1',
            ]);
            $query->where('discount_percentage', '>=', $request->discount_percentage_min);
        }
        if ($request->filled('discount_percentage_max')) {
            $request->validate([
                'discount_percentage_max' => 'numeric|min:0',
            ]);
            $query->where('discount_percentage', '<=', $request->discount_percentage_max);
        }

        $promotions = $query->orderBy('valid_from', 'desc')->paginate(10)->appends($request->query());

        return view('admin.vouchers.index', compact('promotions'));
    }

    public function show($id)
    {
        $promotion = Promotion::findOrFail($id);
        return view('admin.vouchers.show', compact('promotion'));
    }

    // Hiển thị form tạo mới
    // Hiển thị form tạo mới
    public function create()
    {
        return view('admin.vouchers.create');
    }

    // Lưu voucher mới
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:promotions,code|max:255',
            'discount_percentage' => 'required|numeric|min:1|max:100',
            'date_range' => [
                'required',
                'regex:/^\d{4}-\d{2}-\d{2} to \d{4}-\d{2}-\d{2}$/'
            ],
            'description' => 'nullable|string|max:1000',
        ], [
            'code.required' => 'Mã voucher là bắt buộc.',
            'code.unique' => 'Mã voucher đã tồn tại.',
            'code.max' => 'Mã voucher không được vượt quá 255 ký tự.',
            'discount_percentage.required' => 'Phần trăm giảm giá là bắt buộc.',
            'discount_percentage.numeric' => 'Phần trăm giảm giá phải là số.',
            'discount_percentage.min' => 'Phần trăm giảm giá phải lớn hơn hoặc bằng 1.',
            'discount_percentage.max' => 'Phần trăm giảm giá không được vượt quá 100.',
            'date_range.required' => 'Bạn phải chọn khoảng thời gian.',
            'date_range.regex' => 'Khoảng thời gian không hợp lệ. Vui lòng chọn lại.',
            'description.string' => 'Mô tả phải là chuỗi ký tự.',
            'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',
        ]);

        // Parse ngày
        [$valid_from, $valid_until] = explode(' to ', $request->date_range);

        try {
            $validFrom = Carbon::parse($valid_from)->startOfDay();
            $validUntil = Carbon::parse($valid_until)->startOfDay();
        } catch (\Exception $e) {
            return back()->withInput()->withErrors([
                'date_range' => 'Định dạng ngày không hợp lệ. Vui lòng chọn lại.'
            ]);
        }

        // Kiểm tra logic ngày
        if ($validFrom->lt(now()->startOfDay())) {
            return back()->withInput()->withErrors([
                'date_range' => 'Ngày bắt đầu phải từ hôm nay trở đi.'
            ]);
        }

        if ($validUntil->lte($validFrom)) {
            return back()->withInput()->withErrors([
                'date_range' => 'Ngày kết thúc phải sau ngày bắt đầu.'
            ]);
        }

        // Lưu vào DB
        Promotion::create([
            'code' => $request->code,
            'discount_percentage' => $request->discount_percentage,
            'valid_from' => $validFrom,
            'valid_until' => $validUntil,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.vouchers.index')
            ->with('message', 'Tạo voucher thành công!');
    }
    // Hiển thị form chỉnh sửa
    public function edit($id)
    {
        $voucher = Promotion::findOrFail($id);

        // Convert string -> Carbon instance
        $voucher->valid_from = Carbon::parse($voucher->valid_from);
        $voucher->valid_until = Carbon::parse($voucher->valid_until);

        return view('admin.vouchers.edit', compact('voucher'));
    }

    // Cập nhật promotion
    public function update(Request $request, $id)
    {
        $promotion = Promotion::findOrFail($id);

        // Validate dữ liệu
        $request->validate([
            'code' => 'required|max:255|unique:promotions,code,' . $promotion->id,
            'description' => 'nullable|string',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'date_range' => 'required|string',
        ], [
            'code.required' => 'Mã voucher là bắt buộc.',
            'code.unique' => 'Mã voucher đã tồn tại.',
            'description.string' => 'Mô tả phải là chuỗi ký tự.',
            'discount_percentage.required' => 'Phần trăm giảm giá là bắt buộc.',
            'discount_percentage.numeric' => 'Phần trăm giảm giá phải là số.',
            'discount_percentage.min' => 'Phần trăm giảm giá không được nhỏ hơn 0.',
            'discount_percentage.max' => 'Phần trăm giảm giá không được lớn hơn 100.',
            'date_range.required' => 'Thời gian hiệu lực là bắt buộc.',
        ]);

        // Xử lý date_range
        $dates = explode(' to ', $request->date_range);
        $valid_from = isset($dates[0]) ? trim($dates[0]) : null;
        $valid_until = isset($dates[1]) ? trim($dates[1]) : null;

        // Kiểm tra logic ngày
        if (!$valid_from || !$valid_until || strtotime($valid_until) <= strtotime($valid_from)) {
            return back()->withErrors(['date_range' => 'Khoảng thời gian không hợp lệ.'])->withInput();
        }

        // Cập nhật dữ liệu
        $promotion->update([
            'code' => $request->code,
            'description' => $request->description,
            'discount_percentage' => $request->discount_percentage,
            'valid_from' => $valid_from,
            'valid_until' => $valid_until
        ]);

        return redirect()->route('admin.vouchers.index')
            ->with('message', 'Cập nhật voucher thành công!');
    }


    // Xóa promotion
    public function destroy($id)
    {
        $voucher = Promotion::findOrFail($id);
        $voucher->delete();

        return redirect()->route('admin.vouchers.index')
            ->with('message', 'Xóa voucher thành công!');
    }
}
