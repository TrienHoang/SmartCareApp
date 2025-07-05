<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;

class DoctorReviewController extends Controller
{
    public function index()
    {
        $doctor = Auth::user()->doctor; // Lấy model bác sĩ từ user đăng nhập

        if (!$doctor) {
            abort(403, 'Không phải tài khoản bác sĩ.');
        }

        $reviews = Review::where('doctor_id', $doctor->id)
            ->with('service') // nếu bạn muốn hiện tên dịch vụ
            ->latest()
            ->paginate(10);

        return view('doctor.reviews.index', compact('reviews'));
    }
}
