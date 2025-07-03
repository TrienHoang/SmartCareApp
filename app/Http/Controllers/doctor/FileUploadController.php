<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\FileUpload;
use App\Models\UploadHistory;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Svg\Gradient\Stop;

class FileUploadController extends Controller
{

    public function index(Request $request)
    {
        $doctorId = Auth::user()->doctor->id;

        $query = FileUpload::with(['appointment.patient'])
            ->whereHas('appointment', function ($q) use ($doctorId) {
                $q->where('doctor_id', $doctorId);
            });

        // Lọc theo category
        if ($request->filled('category')) {
            $query->where('file_category', $request->category);
        }

        // Lọc theo appointment
        if ($request->filled('appointment_id')) {
            $query->where('appointment_id', $request->appointment_id);
        }

        // Tìm kiếm theo tên file
        if ($request->filled('search')) {
            $query->where('file_name', 'like', '%' . $request->search . '%');
        }

        $files = $query->orderBy('uploaded_at', 'desc')->paginate(15);

        // Lấy danh sách appointments của doctor để filter
        $appointments = Appointment::where('doctor_id', $doctorId)
            ->with('patient:id,full_name')
            ->get(['id', 'patient_id', 'appointment_time']);

        // Danh sách categories
        $categories = FileUpload::whereHas('appointment', function ($q) use ($doctorId) {
            $q->where('doctor_id', $doctorId);
        })->distinct()->pluck('file_category')->filter();

        return view('doctor.files.index', compact('files', 'appointments', 'categories'));
    }

    public function create(Request $request)
    {
        $doctorId = Auth::user()->doctor->id;

        // Lấy danh sách appointments của doctor
        $appointments = Appointment::where('doctor_id', $doctorId)
            ->whereIn('status', ['confirmed', 'completed'])
            ->with('patient:id,full_name')
            ->orderBy('appointment_time', 'desc')
            ->get();

        $selectedAppointment = null;
        if ($request->filled('appointment_id')) {
            $selectedAppointment = $appointments->find($request->appointment_id);
        }

        return view('doctor.files.create', compact('appointments', 'selectedAppointment'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
<<<<<<< HEAD
            'files.*' => 'required|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,gif',
            'file_category' => 'required|string|max:100',
            'note' => 'nullable|string|max:500'
        ], [
            'files.*.max' => 'Mỗi file không được vượt quá 10MB',
            'files.*.mimes' => 'Chỉ chấp nhận file: PDF, DOC, DOCX, JPG, JPEG, PNG, GIF'
        ]);

        $doctorId = Auth::user()->doctor->id;

        // Kiểm tra appointment có thuộc về doctor này không
        $appointment = Appointment::where('id', $request->appointment_id)
            ->where('doctor_id', $doctorId)
            ->firstOrFail();

        DB::beginTransaction();
        try {
            $uploadedFiles = [];

            foreach ($request->file('files') as $file) {
                // Tạo tên file unique
                $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

                // Lưu file vào storage
                $filePath = $file->storeAs('uploads/appointments/' . $request->appointment_id, $fileName, 'public');

                // Lưu thông tin vào database
                $fileUpload = FileUpload::create([
                    'user_id' => Auth::id(),
                    'appointment_id' => $request->appointment_id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_category' => $request->file_category,
=======
            'file_category' => 'required|string|max:255',
            'files.*' => 'required|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,gif',
            'custom_category' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:1000'
        ], [
            'appointment_id.required' => 'Vui lòng chọn lịch hẹn',
            'appointment_id.exists' => 'Lịch hẹn không tồn tại',
            'file_category.required' => 'Vuiện chọn danh mục file',
            'file_category.max' => 'Danh mục file khó qua 255 ký tự',
            'files.*.required' => 'Vui lòng chọn file',
            'files.*.max' => 'File khó qua 10MB',
            'files.*.mimes' => 'Chỉ nhập hợp lệ: file pdf, doc, docx, jpg, jpeg, png, gif',
        ]);

        try {
            // ✅ Lấy thông tin cuộc hẹn và user_id an toàn
            $appointment = Appointment::with('patient')->findOrFail($request->appointment_id);
            $userId = $appointment->patient_id;

            $uploadedFiles = [];

            foreach ($request->file('files') as $file) {
                // Tạo tên file ngẫu nhiên
                $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

                // Đường dẫn lưu file
                $filePath = $file->storeAs(
                    'uploads/appointments/' . $appointment->id,
                    $fileName,
                    'public'
                );

                // Tạo bản ghi FileUpload
                $fileUpload = FileUpload::create([
                    'user_id' => $userId,
                    'appointment_id' => $appointment->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'size' => $file->getSize(),
                    'file_category' => $request->file_category === 'Khác' ? $request->custom_category : $request->file_category,
>>>>>>> 8b50d06627551d61ef7f0f455357c188c304bd94
                    'note' => $request->note,
                    'uploaded_at' => now()
                ]);

<<<<<<< HEAD
                // Ghi log upload history
=======
                // Lưu lịch sử upload
>>>>>>> 8b50d06627551d61ef7f0f455357c188c304bd94
                UploadHistory::create([
                    'file_upload_id' => $fileUpload->id,
                    'action' => 'uploaded',
                    'timestamp' => now()
                ]);

                $uploadedFiles[] = $fileUpload;
            }

<<<<<<< HEAD
            DB::commit();

            return response()->json([
                'success' => true,
                'redirect_url' => route('doctor.files.index'),
                'message' => 'Đã tải lên ' . count($uploadedFiles) . ' file thành công!'
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi trong quá trình tải file: ' . $e->getMessage(),
=======
            return response()->json([
                'success' => true,
                'message' => 'Tải file thành công!',
                'redirect_url' => route('doctor.files.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi trong quá trình tải file: ' . $e->getMessage()
>>>>>>> 8b50d06627551d61ef7f0f455357c188c304bd94
            ], 500);
        }
    }

    public function show($id)
    {
        $doctorId = Auth::user()->doctor->id;

        $file = FileUpload::with(['appointment.patient', 'uploadHistories'])
            ->whereHas('appointment', function ($q) use ($doctorId) {
                $q->where('doctor_id', $doctorId);
            })
            ->findOrFail($id);

        return view('doctor.files.show', compact('file'));
    }

    public function download($id)
    {
        $doctorId = Auth::user()->doctor->id;

        $file = FileUpload::whereHas('appointment', function ($query) use ($doctorId) {
            $query->where('doctor_id', $doctorId);
        })->findOrFail($id);

        $filePath = storage_path('app/public/' . $file->file_path);

        if (!file_exists($filePath)) {
            return back()->with('error', 'File không tồn tại hoặc đã bị xóa!');
        }

        UploadHistory::create([
            'file_upload_id' => $file->id,
            'action' => 'downloaded',
            'timestamp' => now(),
        ]);

        return response()->download($filePath, $file->file_name);
    }

    public function destroy($id)
    {
        $doctorId = Auth::user()->doctor->id;

        $file = FileUpload::whereHas('appointment', function ($q) use ($doctorId) {
            $q->where('doctor_id', $doctorId);
        })->findOrFail($id);

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

<<<<<<< HEAD
            return back()->with('success', 'Đã xóa file thành công!');
=======
            return redirect()->route('doctor.files.index')->with('success', 'Đã xóa file thành công!');
>>>>>>> 8b50d06627551d61ef7f0f455357c188c304bd94
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Đã xảy ra lỗi trong quá trình xóa file: ' . $e->getMessage());
        }
    }

    public function getByAppointment($appointmentId)
    {
        $doctorId = Auth::user()->doctor->id;

        $appointment = Appointment::where('id', $appointmentId)
            ->where('doctor_id', $doctorId)
            ->first();

        if (!$appointment) {
            return response()->json([
                'error' => 'Không có quyền truy cập'
            ], 403);
        }

        $file = FileUpload::where('appointment_id', $appointmentId)
            ->orderBy('uploaded_at', 'desc')
            ->get(['id', 'file_name', 'file_category', 'uploaded_at']);

        return response()->json([
            'files' => $file
        ]);
    }

    public function updateCategory(Request $request, $id)
    {
        $request->validate([
            'file_category' => 'required|string|max:100'
        ]);

        $doctorId = Auth::user()->doctor->id;

        $file = FileUpload::whereHas('appointment', function ($q) use ($doctorId) {
            $q->where('doctor_id', $doctorId);
        })->findOrFail($id);

        $oldCategory = $file->file_category;
        $file->update(['file_category' => $request->file_category]);

        // Ghi log update
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

    // Hiển thị thùng rác
    public function trash()
    {
        $doctorId = Auth::user()->doctor->id;

        $files = FileUpload::onlyTrashed()
            ->whereHas('appointment', function ($q) use ($doctorId) {
                $q->where('doctor_id', $doctorId);
            })
            ->orderBy('uploaded_at', 'desc')
            ->paginate(10);

        return view('doctor.files.trash', compact('files'));
    }

    // Khôi phục file đã xóa
    public function restore($id)
    {
        $doctorId = Auth::user()->doctor->id;

        $file = FileUpload::onlyTrashed()
            ->whereHas('appointment', function ($q) use ($doctorId) {
                $q->where('doctor_id', $doctorId);
            })->findOrFail($id);

        $file->restore();

        UploadHistory::create([
            'file_upload_id' => $file->id,
            'action' => 'restored',
            'timestamp' => now(),
        ]);

        return redirect()->route('doctor.files.trash')->with('success', 'Đã khôi phục file!');
    }

    // Xóa vĩnh viễn file
    public function forceDelete($id)
    {
        $doctorId = Auth::user()->doctor->id;

<<<<<<< HEAD
=======
        // Nếu là xóa toàn bộ
        if ($id === 'all') {
            $files = FileUpload::onlyTrashed()
                ->whereHas('appointment', function ($q) use ($doctorId) {
                    $q->where('doctor_id', $doctorId);
                })->get();

            foreach ($files as $file) {
                // Xóa vật lý file khỏi ổ đĩa nếu tồn tại
                if (Storage::disk('public')->exists($file->file_path)) {
                    Storage::disk('public')->delete($file->file_path);
                }

                // Lưu lịch sử xóa
                UploadHistory::create([
                    'file_upload_id' => $file->id,
                    'action' => 'force_deleted',
                    'timestamp' => now(),
                ]);

                $file->forceDelete();
            }

            return redirect()->route('doctor.files.trash')->with('success', 'Đã xóa vĩnh viễn tất cả file!');
        }

>>>>>>> 8b50d06627551d61ef7f0f455357c188c304bd94
        $file = FileUpload::onlyTrashed()
            ->whereHas('appointment', function ($q) use ($doctorId) {
                $q->where('doctor_id', $doctorId);
            })->findOrFail($id);

<<<<<<< HEAD
        // Xóa vật lý file khỏi ổ đĩa nếu tồn tại
=======
>>>>>>> 8b50d06627551d61ef7f0f455357c188c304bd94
        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }

        UploadHistory::create([
            'file_upload_id' => $file->id,
            'action' => 'force_deleted',
            'timestamp' => now(),
        ]);

        $file->forceDelete();

        return redirect()->route('doctor.files.trash')->with('success', 'Đã xóa vĩnh viễn file!');
    }
}
