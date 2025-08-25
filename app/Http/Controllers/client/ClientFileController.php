<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClientFileController extends Controller
{
    public function index()
    {
        $query = FileUpload::where('user_id', Auth::id());
        
        if (request()->filled('keyword')) {
            $keyword = request()->get('keyword');
            $query->where(function ($q) use ($keyword) {
                $q->where('file_name', 'like', '%' . $keyword . '%')
                    ->orWhere('note', 'like', '%' . $keyword . '%');
            });
        }

        $files = $query->orderBy('uploaded_at', 'desc')->paginate(5)->withQueryString();

        $allFilesQuery = FileUpload::where('user_id', Auth::id());
        $totalDocuments = $allFilesQuery->count();
        $totalSize = $allFilesQuery->sum('size');
        $latestUploaded = $allFilesQuery->orderByDesc('uploaded_at')->first();

        return view('client.uploads.index', compact('files', 'totalDocuments', 'totalSize', 'latestUploaded'));
    }

    public function create()
    {
        $appointments = Appointment::with('doctor')
            ->where('patient_id', Auth::id())
            ->orderByDesc('appointment_time')
            ->get();

        return view('client.uploads.create', compact('appointments'));
    }

    public function store(Request $request)
    {
        try {
            $rules = [
                'file_category' => 'required|string|max:255',
                'files' => 'required|array|min:1',
                'files.*' => 'required|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,gif',
                'note' => 'nullable|string|max:1000',
                'appointment_id' => 'required|exists:appointments,id',
            ];

            $messages = [
                'file_category.required' => 'Vui lòng chọn danh mục file',
                'files.required' => 'Vui lòng chọn file tải lên',
                'files.array' => 'Dữ liệu file không hợp lệ',
                'files.min' => 'Vui lòng chọn ít nhất 1 file',
                'files.*.required' => 'File không được để trống',
                'files.*.file' => 'Dữ liệu tải lên phải là file',
                'files.*.max' => 'Mỗi file không được vượt quá 10MB',
                'files.*.mimes' => 'File phải là định dạng hợp lệ: PDF, Word, ảnh (JPG, PNG, GIF)',
                'appointment_id.required' => 'Vui lòng chọn lịch hẹn',
                'appointment_id.exists' => 'Lịch hẹn không tồn tại',
            ];

            // Thêm rule cho custom_category nếu file_category là "Khác"
            if ($request->file_category === 'Khác') {
                $rules['custom_category'] = 'required|string|max:255';
                $messages['custom_category.required'] = 'Vui lòng nhập loại tài liệu khác';
            }

            $request->validate($rules, $messages);

            // Kiểm tra thêm - đảm bảo có file được upload
            if (!$request->hasFile('files') || count($request->file('files')) === 0) {
                throw new \Illuminate\Validation\ValidationException(
                    validator([], []),
                    response()->json(['errors' => ['files' => ['Vui lòng chọn ít nhất 1 file']]], 422)
                );
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'errors' => $e->errors(),
                    'message' => 'Dữ liệu không hợp lệ'
                ], 422);
            }
            throw $e;
        }

        try {
            // Xử lý category
            $category = $request->file_category;
            if ($category === 'Khác' && $request->custom_category) {
                $category = $request->custom_category;
            }

            // Lưu từng file
            foreach ($request->file('files') as $file) {
                $path = $file->store('uploads/files', 'public');

                FileUpload::create([
                    'user_id' => Auth::id(),
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_category' => $category,
                    'note' => $request->note,
                    'size' => $file->getSize(),
                    'appointment_id' => $request->appointment_id,
                    'uploaded_at' => now(),
                ]);
            }

            if ($request->ajax()) {
                return response()->json(['message' => 'Tải lên thành công'], 200);
            }

            return redirect()->route('client.uploads.index')->with('success', 'Tải lên thành công');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'errors' => ['general' => ['Có lỗi xảy ra trong quá trình tải lên: ' . $e->getMessage()]],
                    'message' => 'Lỗi hệ thống'
                ], 500);
            }

            return back()->withErrors(['general' => 'Có lỗi xảy ra trong quá trình tải lên'])->withInput();
        }
    }

    public function download($id)
    {
        $file = FileUpload::where('user_id', Auth::id())->findOrFail($id);

        if(!Storage::disk('public')->exists($file->file_path)) {
            return back()->with('error', 'File không tìm thấy');
        }

        return Storage::disk('public')->download($file->file_path, $file->file_name);
    }
}
