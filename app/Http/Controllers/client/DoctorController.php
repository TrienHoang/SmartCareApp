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

    // Tính số năm kinh nghiệm
    $startYear = $doctor->experiences->min('start_year');
    $endYear = $doctor->experiences->max('end_year') ?? now()->year;

    $experienceYears = 0;
    if ($startYear) {
        $experienceYears = ($endYear ?? now()->year) - $startYear;
    }
    $doctor->experience_years = $experienceYears > 0 ? $experienceYears : null;

    // Tính đánh giá
    $visibleReviews = $doctor->reviews;
    $doctor->average_rating = round($visibleReviews->avg('rating'), 1);
    $doctor->review_count = $visibleReviews->count();

    $ratingBreakdown = collect([5, 4, 3, 2, 1])->mapWithKeys(function ($star) use ($visibleReviews) {
        return [$star => $visibleReviews->where('rating', $star)->count()];
    });

    // Kiểm tra người dùng
    $appointment = null;
    $alreadyReviewed = false;
    $userReview = null;

    if (Auth::check()) {
        $appointment = Appointment::where('doctor_id', $doctor->id)
            ->where('patient_id', Auth::id())
            ->where('status', 'completed')
            ->latest()
            ->first();

        if ($appointment) {
            $alreadyReviewed = Review::where('appointment_id', $appointment->id)
                ->where('patient_id', Auth::id())
                ->exists();

            if ($alreadyReviewed) {
                $userReview = Review::where('appointment_id', $appointment->id)
                    ->where('patient_id', Auth::id())
                    ->with('replies.user')
                    ->first();
            }
        }
    }

    return view('client.doctors_detail', compact(
        'doctor',
        'ratingBreakdown',
        'appointment',
        'alreadyReviewed',
        'userReview'
    ));
}

}
