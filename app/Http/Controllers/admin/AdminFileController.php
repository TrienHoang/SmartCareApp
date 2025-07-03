<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\FileUpload;
use App\Models\UploadHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminFileController extends Controller
{
    public function index(Request $request)
    {
        $query = FileUpload::query();

        // Tìm theo tên file hoặc ghi chú
        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('file_name', 'like', '%' . $request->keyword . '%')
                    ->orWhere('note', 'like', '%' . $request->keyword . '%');
            });
        }

        // Tìm theo loại người dùng (bác sĩ hoặc bệnh nhân)
        if ($request->filled('uploader_type')) {
            if ($request->uploader_type === 'doctor') {
                $query->whereHas('user', function ($q) {
                    $q->whereHas('doctor');
                });
            } elseif ($request->uploader_type === 'patient') {
                $query->whereHas('user', function ($q) {
                    $q->whereDoesntHave('doctor');
                });
            }
        }

        // Tìm theo ngày tải lên
        $from_input = $request->date_from;
        $to_input = $request->date_to;

        if ($request->filled('date_from') || $request->filled('date_to')) {
            $from = $request->filled('date_from') ? Carbon::parse($request->date_from)->startOfDay() : null;
            $to = $request->filled('date_to') ? Carbon::parse($request->date_to)->endOfDay() : null;

            if ($from && $to && $from->gt($to)) {
                // Hoán đổi giá trị nếu from > to
                [$from, $to] = [$to, $from];
                [$from_input, $to_input] = [$to_input, $from_input];

                return redirect()->route('admin.files.index', [
                    'date_from' => $from_input,
                    'date_to' => $to_input,
                ])->with('date_swapped', true);
            }

            if ($from && $to) {
                $query->whereBetween('uploaded_at', [$from, $to]);
            } elseif ($from) {
                $query->where('uploaded_at', '>=', $from);
            } elseif ($to) {
                $query->where('uploaded_at', '<=', $to);
            }
        }


        // Lọc theo danh mục file
        if ($request->filled('category')) {
            $query->where('file_category', $request->category);
        }

        $categories = FileUpload::select('file_category')->whereNotNull('file_category')->distinct()->pluck('file_category');

        $files = $query->with('user')->orderBy('uploaded_at', 'desc')->paginate(15);

        // Thống kê
        $totalFiles = FileUpload::count();
        $totalSize = FileUpload::sum('size'); // Nếu có cột size

        return view('admin.files.index', compact('files', 'totalFiles', 'totalSize', 'categories', 'from_input', 'to_input'));
    }

    public function show($id)
    {
        $file = FileUpload::findOrFail($id);
        $categories = FileUpload::select('file_category')->whereNotNull('file_category')->distinct()->pluck('file_category');
        return view('admin.files.show', compact('file', 'categories'));
    }

    public function download($id)
    {
        $file = FileUpload::findOrFail($id);
        $path = storage_path('app/public/' . $file->file_path);
        if (!file_exists($path)) {
            abort(404, 'File không tồn tại trên hệ thống');
        }

        $file->increment('download_count');
        return response()->download($path, $file->file_name);
    }

    public function destroy($id)
    {
        $file = FileUpload::findOrFail($id);
        DB::beginTransaction();

        try {
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }

            UploadHistory::create([
                'file_upload_id' => $file->id,
                'action' => 'deleted',
                'timestamp' => now(),
            ]);

            $file->delete();
            DB::commit();

            return redirect()->route('admin.files.index')->with('success', 'Đã xóa file thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->with('error', 'Đã xảy ra lỗi trong quá trình xóa file: ' . $e->getMessage());
        }
    }

    public function trash()
    {
        $files = FileUpload::onlyTrashed()
            ->orderBy('uploaded_at', 'desc')
            ->paginate(10);

        return view('admin.files.trash', compact('files'));
    }

    public function restore($id)
    {
        $file = FileUpload::onlyTrashed()->findOrFail($id);
        $file->restore();

        UploadHistory::create([
            'file_upload_id' => $file->id,
            'action' => 'restored',
            'timestamp' => now(),
        ]);

        return redirect()->route('admin.files.trash')->with('success', 'Đã khôi phục file!');
    }

    public function forceDelete(Request $request, $id = null)
    {
        // Nếu truyền 'all' => xóa toàn bộ trong thùng rác
        if ($id === 'all') {
            $files = FileUpload::onlyTrashed()->get();
        }
        // Nếu truyền mảng ID (qua form hoặc AJAX)
        elseif (is_array($request->input('ids'))) {
            $ids = $request->input('ids');
            $files = FileUpload::onlyTrashed()->whereIn('id', $ids)->get();
        }
        // Truyền 1 ID duy nhất
        else {
            $files = collect([FileUpload::onlyTrashed()->findOrFail($id)]);
        }

        foreach ($files as $file) {
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }

            UploadHistory::create([
                'file_upload_id' => $file->id,
                'action' => 'force_deleted',
                'timestamp' => now(),
            ]);

            $file->forceDelete();
        }

        return redirect()->route('admin.files.trash')->with('success', 'Đã xóa vĩnh viễn file!');
    }

    public function updateCategory(Request $request, $id)
    {
        $request->validate([
            'file_category' => 'required|string|max:100'
        ], [
            'file_category.required' => 'Vui lý chọn danh mục file',
        ]);

        $file = FileUpload::findOrFail($id);

        $oldCategory = $file->file_category;
        $file->update(['file_category' => $request->file_category]);

        UploadHistory::create([
            'file_upload_id' => $file->id,
            'action' => 'category_updated',
            'timestamp' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật danh mục file thành công!'
        ]);
    }
}
