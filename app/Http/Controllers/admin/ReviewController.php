<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        $reviews = Review::with(['patient', 'doctor.user', 'service'])
            ->when($keyword, function ($query, $keyword) {
                $query->where('comment', 'like', "%$keyword%")
                    ->orWhereHas('patient', function ($q) use ($keyword) {
                        $q->where('full_name', 'like', "%$keyword%");
                    })
                    ->orWhereHas('doctor.user', function ($q) use ($keyword) {
                        $q->where('full_name', 'like', "%$keyword%");
                    });
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.reviews.index', compact('reviews'));
        /**
         * Hiển thị chi tiết đánh giá.
         */
    }
    public function show(int $id): View
    {
        $review = Review::with(['patient', 'doctor', 'service'])->findOrFail($id);
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

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!');
    }
}
