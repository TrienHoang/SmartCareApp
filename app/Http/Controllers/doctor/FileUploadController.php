<?php

namespace App\Http\Controllers\doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
