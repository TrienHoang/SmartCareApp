<?php





namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\ReviewReply;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class ReviewReplyController extends Controller
{
    /**
     * Gửi đánh giá bác sĩ.
     */
    public function store(Request $request, $doctorId)
    {

        // dd($request->all());
        try {
            // ✅ Tìm bác sĩ
            $doctor = Doctor::findOrFail($doctorId);

            // ✅ Validate đầu vào
            $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'required|string|max:1000',
                'appointment_id' => [
                    'required',
                    Rule::exists('appointments', 'id')->where(function ($query) use ($doctor) {
                        $query->where('doctor_id', $doctor->id)
                            ->where('patient_id', Auth::id());
                    }),
                ],
                'service_id' => 'nullable|exists:services,id',
            ]);

            // ✅ Kiểm tra lại cuộc hẹn
            $appointment = Appointment::where('id', $request->appointment_id)
                ->where('doctor_id', $doctor->id)
                ->where('patient_id', Auth::id())
                ->first();

            if (!$appointment) {
                return back()->with('error', 'Cuộc hẹn không hợp lệ hoặc không thuộc về bạn.');
            }

            // ✅ Kiểm tra đã đánh giá chưa (chỉ 1 lần cho mỗi appointment)
            $alreadyReviewed = Review::where('appointment_id', $appointment->id)
                ->where('patient_id', Auth::id())
                ->exists();

            if ($alreadyReviewed) {
                return back()->with('error', 'Bạn đã đánh giá cuộc hẹn này rồi.');
            }

            // ✅ Tạo đánh giá mới
            $review = Review::create([
                'appointment_id' => $appointment->id,
                'patient_id'     => Auth::id(),
                'doctor_id'      => $doctor->id,
                'service_id'     => $request->service_id,
                'rating'         => $request->rating,
                'comment'        => $request->comment,
                'is_visible'     => true,
            ]);

            Log::info('Đánh giá được tạo', [
                'review_id' => $review->id,
                'doctor_id' => $doctor->id,
                'user_id'   => Auth::id(),
            ]);

            return back()->with('success', 'Đánh giá của bạn đã được gửi thành công.');
        } catch (\Exception $e) {
            Log::error('Lỗi khi tạo đánh giá: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);

            return back()->with('error', 'Có lỗi xảy ra khi gửi đánh giá. Vui lòng thử lại.');
        }
    }
    /**
     * Đánh dấu đánh giá là hữu ích.
     */
    public function markUseful($reviewId)
    {
        try {
            $review = Review::findOrFail($reviewId);
            $review->useful_count = ($review->useful_count ?? 0) + 1;
            $review->save();

            return back()->with('success', 'Cảm ơn bạn đã đánh giá hữu ích.');
        } catch (\Exception $e) {
            Log::error('Lỗi khi đánh dấu hữu ích: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra. Vui lòng thử lại.');
        }
    }

    /**
     * Cập nhật bình luận đã chỉnh sửa trực tiếp tại trang chi tiết bác sĩ.
     * Đảm bảo nhận đúng $doctorId và $id từ route.
     */
    public function update(Request $request, $doctorId, $id)
    {
        $review = Review::where('id', $id)
            ->where('doctor_id', $doctorId)
            ->where('patient_id', Auth::id())
            ->firstOrFail();

        // Chỉ cho sửa trong 1 giờ đầu
        if (\Carbon\Carbon::parse($review->created_at)->diffInMinutes(now()) > 60) {
            return redirect()->back()->with('error', 'Thời gian chỉnh sửa đã hết.');
        }

        $request->validate([
            'comment' => 'required|string|max:1000',
            'rating'  => 'required|integer|min:1|max:5',
        ]);

        // Đảm bảo cập nhật đúng rating
        $review->comment = $request->comment;
        $review->rating = intval($request->rating); // ép kiểu số nguyên
        $review->save();

        // Cập nhật lại rating trung bình cho bác sĩ (tính lại từ bảng reviews)
        // Nếu chưa có cột average_rating và review_count thì bỏ qua cập nhật này
        if (
            Schema::hasColumn('doctors', 'average_rating') &&
            Schema::hasColumn('doctors', 'review_count')
        ) {
            $doctor = Doctor::findOrFail($doctorId);
            $doctor->average_rating = Review::where('doctor_id', $doctorId)
                ->where('is_visible', true)
                ->avg('rating') ?? 5;
            $doctor->review_count = Review::where('doctor_id', $doctorId)
                ->where('is_visible', true)
                ->count();
            $doctor->save();
        }

        return redirect()->route('doctor.show', $doctorId)
            ->with('success', 'Bình luận đã được cập nhật.')
            ->with('tab', 'reviews');
    }

    /**
     * Hiển thị chi tiết bác sĩ (trang bác sĩ).
     */
    public function show($id)
    {
        $doctor = Doctor::with('specialty')->findOrFail($id); // load cả chuyên khoa nếu cần

        $reviews = Review::where('doctor_id', $doctor->id)
            ->where('is_visible', true)
            ->with('patient')
            ->latest()
            ->get();

        $averageRating = $reviews->avg('rating');
        $reviewCount = $reviews->count();

        $userReview = Review::where('doctor_id', $doctor->id)
            ->where('patient_id', Auth::id())
            ->latest()
            ->first();

        $userReviewEditable = false;
        if ($userReview) {
            $userReviewEditable = \Carbon\Carbon::parse($userReview->created_at)->diffInMinutes(now()) <= 60;
        }

        return view('client.doctors_detail', compact(
            'doctor',
            'reviews',
            'averageRating',
            'reviewCount',
            'userReview',
            'userReviewEditable'
        ));
    }



    /**
     * Hiển thị danh sách bình luận của người dùng (trang danh sách bình luận).
     */
    public function index()
    {
        $reviews = Review::where('patient_id', Auth::id())
            ->latest()
            ->get();

        // Gắn thuộc tính editable cho từng review
        foreach ($reviews as $review) {
            $review->editable = \Carbon\Carbon::parse($review->created_at)->diffInMinutes(now()) <= 60;
        }

        return view('client.review.index', compact('reviews'));
    }
}