<?php





namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\ReviewReply;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        if (!Auth::check()) {
            return back()->with('error', 'Vui lòng đăng nhập để gửi đánh giá.');
        }

        try {
            $doctor = Doctor::findOrFail($doctorId);

            // Validate đầu vào
            $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string|max:1000',
                'appointment_id' => [
                    'required',
                    Rule::exists('appointments', 'id')->where(function ($query) use ($doctor) {
                        $query->where('doctor_id', $doctor->id)
                            ->where('patient_id', Auth::id())
                            ->where('status', 'completed');
                    }),
                ],
                'service_id' => [
                    'required',
                    Rule::exists('services', 'id')->where(function ($query) use ($request, $doctor) {
                        $query->whereIn('id', Appointment::where('doctor_id', $doctor->id)
                            ->where('patient_id', Auth::id())
                            ->where('status', 'completed')
                            ->where('id', $request->appointment_id)
                            ->pluck('service_id'));
                    }),
                ],
            ]);

            // Kiểm tra lại cuộc hẹn
            $appointment = Appointment::where('id', $request->appointment_id)
                ->where('doctor_id', $doctor->id)
                ->where('patient_id', Auth::id())
                ->where('status', 'completed')
                ->first();

            if (!$appointment) {
                return back()->with('error', 'Cuộc hẹn không hợp lệ hoặc không thuộc về bạn.');
            }

            $review = Review::create([
                'appointment_id' => $appointment->id,
                'patient_id' => Auth::id(),
                'doctor_id' => $doctor->id,
                'service_id' => $request->service_id,
                'rating' => $request->rating,
                'comment' => $request->comment ?? null,
                'is_visible' => true,
            ]);

            // Cập nhật average_rating và review_count
            if (
                Schema::hasColumn('doctors', 'average_rating') &&
                Schema::hasColumn('doctors', 'review_count')
            ) {
                $doctor->average_rating = Review::where('doctor_id', $doctor->id)
                    ->where('is_visible', true)
                    ->avg('rating') ?? 5;
                $doctor->review_count = Review::where('doctor_id', $doctor->id)
                    ->where('is_visible', true)
                    ->count();
                $doctor->save();
            }

            Log::info('Đánh giá được tạo', [
                'review_id' => $review->id,
                'doctor_id' => $doctor->id,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('doctor.show', ['id' => $doctorId, 'tab' => 'reviews'])
                ->with('success', 'Thêm đánh giá thành công!');
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
    public function markUseful($id)
    {
        $review = Review::findOrFail($id);

        // Không cho user tự vote review của mình
        if ($review->patient_id == Auth::id()) {
            return response()->json(['message' => 'Không thể tự đánh dấu hữu ích review của mình'], 403);
        }

        $review->increment('useful_count'); // +1
        return response()->json([
            'success' => true,
            'new_count' => $review->useful_count,
        ]);
    }
    /**
     * Cập nhật bình luận đã chỉnh sửa trực tiếp tại trang chi tiết bác sĩ.
     * Đảm bảo nhận đúng $doctorId và $id từ route.
     */
    public function update(Request $request, $doctorId, $id)
    {
        // dd($request->all());     
        if (!Auth::check()) {
            return back()->with('error', 'Vui lòng đăng nhập để chỉnh sửa đánh giá.');
        }

        $review = Review::where('id', $id)
            ->where('doctor_id', $doctorId)
            ->where('patient_id', Auth::id())
            ->where('is_visible', true)
            ->firstOrFail();

        if (\Carbon\Carbon::parse($review->created_at)->diffInMinutes(now()) > 60) {
            return redirect()->back()->with('error', 'Thời gian chỉnh sửa đã hết.');
        }

        $request->validate([
            'comment' => 'nullable|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $review->comment = $request->comment;
        $review->rating = $request->rating;
        $review->appointment_id = $request->appointment_id;
        $review->service_id = $request->service_id;
        $review->save();

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

        return redirect()->route('doctor.show', ['id' => $doctorId, 'tab' => 'reviews'])
            ->with('success', 'Cập nhật đánh giá thành công!');
    }

    /**
     * Hiển thị chi tiết bác sĩ (trang bác sĩ).
     */
    public function show($id)
    {
        $user = Auth::user();
        $doctor = Doctor::with('specialty')->findOrFail($id);
        $reviews = Review::where('doctor_id', $doctor->id)
            ->where('is_visible', true)
            ->with(['patient', 'service'])
            ->latest()
            ->get();

        $averageRating = $reviews->avg('rating');
        $reviewCount = $reviews->count();

        $userReview = null;
        $userReviewEditable = false;
        $completedAppointments = collect();
        $alreadyReviewed = false;

        if ($user) {
            $userReview = Review::where('doctor_id', $doctor->id)
                ->where('patient_id', $user->id)
                ->latest()
                ->first();

            if ($userReview) {
                $userReviewEditable = \Carbon\Carbon::parse($userReview->created_at)->diffInMinutes(now()) <= 60;
                $alreadyReviewed = true;
            } else {
                $completedAppointments = Appointment::where('doctor_id', $doctor->id)
                    ->where('patient_id', $user->id)
                    ->where('status', 'completed')
                    ->with('service')
                    ->get();
            }
        }

        return view('client.doctors_detail', compact(
            'doctor',
            'reviews',
            'averageRating',
            'reviewCount',
            'userReview',
            'userReviewEditable',
            'user',
            'completedAppointments',
            'alreadyReviewed'
        ));
    }

    /**
     * Hiển thị danh sách bình luận của người dùng (trang danh sách bình luận).
     */
    public function index()
    {
        $user = Auth::user();
        $reviews = Review::where('patient_id', Auth::id())
            ->latest()
            ->get();

        // Gắn thuộc tính editable cho từng review
        foreach ($reviews as $review) {
            $review->editable = \Carbon\Carbon::parse($review->created_at)->diffInMinutes(now()) <= 60;
        }

        return view('client.review.index', compact('reviews', 'user'));
    }
}
