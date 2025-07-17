<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoctorReviewController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user->doctor) {
            return redirect()->route('doctor.dashboard')->with('error', 'Tài khoản chưa được liên kết với bác sĩ.');
        }

        $query = Review::with(['patient', 'appointment', 'service'])
            ->where('doctor_id', $user->doctor->id);

        // Lọc theo tên bệnh nhân
        if ($request->filled('patient_name')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->patient_name . '%');
            });
        }

        // Lọc theo số sao
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Lọc theo trạng thái hiển thị
        if ($request->has('is_visible') && $request->is_visible !== null && $request->is_visible !== '') {
            $query->where('is_visible', $request->is_visible);
        }

        // Lọc theo tên dịch vụ
        if ($request->filled('service_name')) {
            $query->whereHas('service', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->service_name . '%');
            });
        }

        $reviews = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

        // Thống kê số lượng đánh giá theo trạng thái
        $statusCounts = [
            'visible' => (clone $query)->where('is_visible', 1)->count(),
            'hidden'  => (clone $query)->where('is_visible', 0)->count(),
            'total'   => (clone $query)->count(),
        ];

        // Thống kê theo dịch vụ


        $serviceStats = Review::select('service_id', DB::raw('count(*) as total'))
            ->where('doctor_id', $user->doctor->id)
            ->groupBy('service_id')
            ->with('service')
            ->get();

        return view('doctor.reviews.index', compact('reviews', 'statusCounts', 'serviceStats'));
    }
    // Trong DoctorReviewController.php
    public function toggleVisibility(Review $review)
    {
        $user = Auth::user();

        if (!$user->doctor || $review->doctor_id !== $user->doctor->id) {
            return back()->with('error', 'Không có quyền thao tác với đánh giá này.');
        }

        $review->is_visible = !$review->is_visible;
        $review->save();

        return back()->with('success', 'Cập nhật trạng thái hiển thị đánh giá thành công.');
    }
}
