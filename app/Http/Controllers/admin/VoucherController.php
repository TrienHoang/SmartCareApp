<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;

class VoucherController extends Controller
{
    // Hiển thị danh sách voucher
    public function index()
    {
        $vouchers = Voucher::orderBy('created_at', 'desc')->paginate(10);
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
