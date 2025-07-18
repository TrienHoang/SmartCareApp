<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Models\Appointment;


class DoctorController extends Controller
{
public function show($id)
{
    // ✅ Lấy thông tin bác sĩ và các quan hệ liên quan
    $doctor = Doctor::with([
        'user',
        'department',
        'educations',
        'experiences',
        'achievements',
        'specialties',
        'reviews' => function ($query) {
            $query->where('is_visible', true)
                ->latest()
                ->with(['patient', 'replies.user']);
        },
    ])->findOrFail($id);

    // ✅ Tính toán đánh giá
    $visibleReviews = $doctor->reviews;
    $averageRating = round($visibleReviews->avg('rating'), 1);
    $reviewCount = $visibleReviews->count();

    $ratingBreakdown = collect([5, 4, 3, 2, 1])->mapWithKeys(function ($star) use ($visibleReviews) {
        return [$star => $visibleReviews->where('rating', $star)->count()];
    });

    // ✅ Kiểm tra xem user đã có cuộc hẹn hoàn tất chưa
    $appointment = null;
    $alreadyReviewed = false;
    $userReview = null;

    if (Auth::check()) {
        $appointment = Appointment::where('doctor_id', $doctor->id)
            ->where('patient_id', Auth::id())
            ->where('status', 'completed')
            ->latest()
            ->first();

        // ✅ Nếu có cuộc hẹn => kiểm tra đã đánh giá chưa
        if ($appointment) {
            $alreadyReviewed = Review::where('appointment_id', $appointment->id)
                ->where('patient_id', Auth::id())
                ->exists();

            if ($alreadyReviewed) {
                $userReview = Review::where('appointment_id', $appointment->id)
                    ->where('patient_id', Auth::id())
                    ->with('replies.user') // nếu có phản hồi
                    ->first();
            }
        }
    }

    // ✅ Truyền tất cả sang view
    return view('client.doctors_detail', compact(
        'doctor',
        'averageRating',
        'reviewCount',
        'ratingBreakdown',
        'appointment',
        'alreadyReviewed',
        'userReview'
    ));
}

}
