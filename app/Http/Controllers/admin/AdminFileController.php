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
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('uploaded_at', [
                Carbon::parse($request->date_from)->startOfDay(),
                Carbon::parse($request->date_to)->endOfDay(),
            ]);
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

        return view('admin.files.index', compact('files', 'totalFiles', 'totalSize', 'categories'));
    }

    public function show($id)
    {
        $file = FileUpload::findOrFail($id);
        return view('admin.files.show', compact('file'));
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

            return redirect()->back()->with('success', 'Đã xóa file thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->with('error', 'Đã xảy ra lỗi trong quá trình xóa file: ' . $e->getMessage());
        }
    }

    public function trash(){
        $files = FileUpload::onlyTrashed()
                ->orderBy('uploaded_at', 'desc')
                ->paginate(10);

        return view('admin.files.trash', compact('files'));
    }
}
