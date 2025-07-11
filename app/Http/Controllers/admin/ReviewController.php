<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    /**
     * Hiển thị danh sách đánh giá với bộ lọc.
     */
    public function index(Request $request): View
    {
        $query = Review::with(['patient', 'doctor.user', 'service']);

        // Lọc theo tên bệnh nhân
        if ($request->filled('patient_name')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->patient_name . '%');
            });
        }

        // Lọc theo tên bác sĩ
        if ($request->filled('doctor_name')) {
            $query->whereHas('doctor.user', function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->doctor_name . '%');
            });
        }

        // Lọc theo rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Lọc theo trạng thái hiển thị
        if ($request->filled('is_visible')) {
            $query->where('is_visible', $request->is_visible);
        }
        // Lấy tất cả đánh giá để thống kê
        $reviewsQuery = clone $query;
        $reviews = $query->orderBy('created_at', 'desc')->paginate(10);

        // Thống kê trạng thái đánh giá (mỗi truy vấn phải clone lại)
        $statusCounts = [
            'visible' => (clone $reviewsQuery)->where('is_visible', 1)->count(),
            'hidden' => (clone $reviewsQuery)->where('is_visible', 0)->count(),
            'total' => (clone $reviewsQuery)->count(),
            'star_5' => (clone $reviewsQuery)->where('rating', 5)->count(),
            'star_4' => (clone $reviewsQuery)->where('rating', 4)->count(),
            'star_3' => (clone $reviewsQuery)->where('rating', 3)->count(),
            'star_2' => (clone $reviewsQuery)->where('rating', 2)->count(),
            'star_1' => (clone $reviewsQuery)->where('rating', 1)->count(),
        ];

        return view('admin.reviews.index', compact('reviews', 'statusCounts',));
    }

    /**
     * Hiển thị chi tiết một đánh giá.
     */
    public function show(int $id): View
    {
        $review = Review::with(['patient', 'doctor.user', 'service'])->findOrFail($id);
        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Ẩn hoặc hiện đánh giá.
     */
    public function toggleVisibility(int $id): RedirectResponse
    {
        $review = Review::findOrFail($id);
        $review->update([
            'is_visible' => !$review->is_visible,
        ]);

        return redirect()->back()->with('success', 'Cập nhật trạng thái hiển thị thành công!');
    }
}
