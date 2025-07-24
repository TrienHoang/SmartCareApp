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
            'rating' => 'required|numeric|min:0.5|max:5',
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

        // ✅ Kiểm tra cuộc hẹn hợp lệ
        $appointment = Appointment::where('id', $request->appointment_id)
            ->where('doctor_id', $doctor->id)
            ->where('patient_id', Auth::id())
            ->first();

        if (!$appointment) {
            return back()->with('error', 'Cuộc hẹn không hợp lệ hoặc không thuộc về bạn.');
        }

        // ✅ Kiểm tra đã đánh giá chưa
        $alreadyReviewed = Review::where('appointment_id', $appointment->id)
            ->where('patient_id', Auth::id())
            ->exists();

        if ($alreadyReviewed) {
            return back()->with('error', 'Bạn đã đánh giá cuộc hẹn này rồi.');
        }

        // ✅ Tạo đánh giá
        $review = Review::create([
            'appointment_id' => $appointment->id,
            'patient_id'     => Auth::id(),
            'doctor_id'      => $doctor->id,
            'service_id'     => $request->service_id,
            'rating'         => $request->rating,
            'comment'        => $request->comment,
            'is_visible'     => true,
        ]);

        // ✅ Cập nhật điểm trung bình và tổng số đánh giá
        $average = Review::where('doctor_id', $doctor->id)->avg('rating');
        $total = Review::where('doctor_id', $doctor->id)->count();

        $doctor->average_rating = round($average, 2);
        $doctor->total_ratings = $total;
        $doctor->save();

        // ✅ Log
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

}
