<?php

namespace App\Http\Controllers\Admin;


use App\Models\Medicine;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    // Hiển thị danh sách thuốc
    public function index(Request $request)
    {
        $query = Medicine::query();
    
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
    
        $medicines = $query->latest()->paginate(10);
    
        // Giữ lại query trong pagination
        $medicines->appends($request->only('search'));
    
        return view('admin.medicines.index', compact('medicines'));
    }

    // Hiển thị form thêm mới
    public function create()
    {
        return view('admin.medicines.create');
    }

    // Lưu thuốc mới
    public function store(Request $request)
    {
       $validates = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'unit' => 'nullable|string|max:20',
       ],[
            'name.required' => 'Vui lòng nhập tên thuốc.',
            'name.max' => 'Tên thuốc không được vượt quá 100 ký tự.',
            'description.max' => 'Mô tả không vượt quá 1000 ký tự.',
            'unit.max' => 'Đơn vị không được vượt quá 20 ký tự.',
       ]
    );

        

        Medicine::create([
            'name' => $validates['name'],
            'description' => $validates['description'],
            'unit' => $validates['unit'],
        ]);

        return redirect()->route('admin.medicines.index')->with('success', 'Thêm thuốc thành công!');
    }

    // Hiển thị form sửa
    public function edit($id)
    {
        $medicine = Medicine::findOrFail($id);
        return view('admin.medicines.edit', compact('medicine'));
    }

    public function show($id)
    {
        $medicine = Medicine::findOrFail($id);
        return view('admin.medicines.show', compact('medicine'));
    }

    // Cập nhật thuốc
    public function update(Request $request, $id)
    {
        $medicine = Medicine::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'unit' => 'nullable|string|max:20',
            'dosage' => 'nullable|string|max:30',
            'price' => 'nullable|numeric|min:0',
        ]);

        $medicine->update($request->only([
            'name',
            'description',
            'unit',
            'dosage',
            'price'
        ]));

        return redirect()->route('admin.medicines.index')->with('success', 'Cập nhật thuốc thành công!');
    }

    // Xoá thuốc
    public function destroy($id)
    {
        $medicine = Medicine::findOrFail($id);
        $medicine->delete();

        return redirect()->route('admin.medicines.index')->with('success', 'Xoá thuốc thành công!');
    }

    // Hiển thị danh sách thuốc đã xoá mềm
    public function trash()
    {
        $medicines = Medicine::onlyTrashed()->orderBy('deleted_at', 'desc')->get();

        return view('admin.medicines.trash', compact('medicines'));
    }

    // Khôi phục thuốc
    public function restore($id)
    {
        $medicine = Medicine::withTrashed()->findOrFail($id);
        $medicine->restore();

        return redirect()->route('admin.medicines.trash')->with('success', 'Khôi phục thuốc thành công!');
    }
}
