<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;

class VoucherController extends Controller
{
    // Hiển thị danh sách voucher
    public function index(Request $request)
    {
        $query = Voucher::query();

        // Tìm theo mã voucher (code)
        if ($request->filled('code')) {
            $query->where('code', 'like', '%' . $request->code . '%');
        }

        // Lọc theo giảm giá
        if ($request->filled('discount')) {
            $query->where('discount', $request->discount);
        }

        // Lọc theo số lượng
        if ($request->filled('quantity')) {
            $query->where('quantity', '>=', $request->quantity);
        }

        $vouchers = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->query());

        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function show($id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('admin.vouchers.show', compact('voucher'));
    }
    // Hiển thị form tạo mới
    public function create()
    {
        // Kiểm tra quyền truy cập
        // if (!auth()->user()->can('create', Voucher::class)) {
        //     return redirect()->route('admin.vouchers.index')
        //         ->with('error', 'Bạn không có quyền tạo voucher.');
        // }

        // Trả về view tạo voucher mới
        return view('admin.vouchers.create');
    }

    // Lưu voucher mới
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:vouchers,code|max:255',
            'discount' => 'required|numeric|min:0|max:100',
            'quantity' => 'required|integer|min:1',
            'min_price' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ], [
            'code.required' => 'Mã voucher là bắt buộc.',
            'code.unique' => 'Mã voucher đã tồn tại.',
            'discount.required' => 'Giảm giá là bắt buộc.',
            'discount.numeric' => 'Giảm giá phải là số.',
            'discount.min' => 'Giảm giá không được nhỏ hơn 0.',
            'discount.max' => 'Giảm giá không được lớn hơn 100.',
            'quantity.required' => 'Số lượng là bắt buộc.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'quantity.min' => 'Số lượng phải lớn hơn hoặc bằng 1.',
            'min_price.required' => 'Giá tối thiểu là bắt buộc.',
            'min_price.numeric' => 'Giá tối thiểu phải là số.',
            'min_price.min' => 'Giá tối thiểu không được nhỏ hơn 0.',
            'description.string' => 'Mô tả phải là chuỗi ký tự.'
        ]);

        Voucher::create($request->all());

        return redirect()->route('admin.vouchers.index')
            ->with('message', 'Tạo voucher thành công!');
    }

    // Hiển thị form chỉnh sửa
    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('admin.vouchers.edit', compact('voucher'));
    }

    // Cập nhật voucher
    public function update(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id);

        $request->validate([
            'code' => 'required|max:255|unique:vouchers,code,' . $voucher->id,
            'discount' => 'required|numeric|min:0|max:100',
            'quantity' => 'required|integer|min:1',
            'min_price' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ], [
            'code.required' => 'Mã voucher là bắt buộc.',
            'code.unique' => 'Mã voucher đã tồn tại.',
            'discount.required' => 'Giảm giá là bắt buộc.',
            'discount.numeric' => 'Giảm giá phải là số.',
            'discount.min' => 'Giảm giá không được nhỏ hơn 0.',
            'discount.max' => 'Giảm giá không được lớn hơn 100.',
            'quantity.required' => 'Số lượng là bắt buộc.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'quantity.min' => 'Số lượng phải lớn hơn hoặc bằng 1.',
            'min_price.required' => 'Giá tối thiểu là bắt buộc.',
            'min_price.numeric' => 'Giá tối thiểu phải là số.',
            'min_price.min' => 'Giá tối thiểu không được nhỏ hơn 0.',
            'description.string' => 'Mô tả phải là chuỗi ký tự.'
        ]);

        $voucher->update($request->all());

        return redirect()->route('admin.vouchers.index')
            ->with('message', 'Cập nhật voucher thành công!');
    }

    // Xóa voucher
    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();

        return redirect()->route('admin.vouchers.index')
            ->with('message', 'Xóa voucher thành công!');
    }
}
