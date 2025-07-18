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
use Illuminate\Validation\Rule;

class ReviewReplyController extends Controller
{
    /**
     * Gửi đánh giá bác sĩ.
     */
public function store(Request $request, $doctorId)
{
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
     * Gửi phản hồi đánh giá.
     */
    public function storeReply(Request $request, $reviewId)
    {
        try {
            $request->validate([
                'content' => 'required|string|max:1000',
            ]);

            $review = Review::findOrFail($reviewId);

            ReviewReply::create([
                'review_id' => $review->id,
                'user_id' => Auth::id(),
                'content' => $request->content,
            ]);

            return back()->with('success', 'Phản hồi của bạn đã được gửi.');
        } catch (\Exception $e) {
            Log::error('Lỗi khi gửi phản hồi: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi gửi phản hồi. Vui lòng thử lại.');
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
}
