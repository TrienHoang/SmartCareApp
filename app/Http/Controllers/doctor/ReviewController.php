<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Hiển thị danh sách đánh giá dành cho bác sĩ đang đăng nhập
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user->doctor) {
            return redirect()->route('doctor.dashboard')->with('error', 'Tài khoản chưa được liên kết với bác sĩ.');
        }

        $reviews = Review::with(['patient', 'appointment', 'service'])
            ->where('doctor_id', $user->doctor->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('doctor.reviews.index', compact('reviews'));
    }

    /**
     * Toggle hiển thị đánh giá (ẩn/hiện)
     */
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
