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
            'services',
            'reviews' => function ($query) {
                $query->where('is_visible', true)
                    ->latest()
                    ->with([
                        'patient',
                        'replies.user',
                        'appointment.order.services'
                    ]);
            },
        ])->findOrFail($id);

        // Lấy năm bắt đầu sớm nhất
        $startYear = $doctor->experiences->min('start_year');

        // Xử lý end_year: nếu có null => coi là năm hiện tại
        $endYears = $doctor->experiences->map(function ($exp) {
            return $exp->end_year ?? now()->year;
        });

        // Lấy năm kết thúc muộn nhất
        $endYear = $endYears->max();

        // Tính số năm kinh nghiệm
        $experienceYears = 0;
        if ($startYear) {
            $experienceYears = $endYear - $startYear;
        }

        $doctor->experience_years = $experienceYears > 0 ? $experienceYears : null;

        // ✅ Tính điểm trung bình và phân bổ đánh giá
        $visibleReviews = $doctor->reviews;
        $doctor->average_rating = round($visibleReviews->avg('rating'), 1);
        $doctor->review_count = $visibleReviews->count();

        $ratingBreakdown = collect([5, 4, 3, 2, 1])->mapWithKeys(function ($star) use ($visibleReviews) {
            return [$star => $visibleReviews->where('rating', $star)->count()];
        });

        // ✅ Kiểm tra người dùng đăng nhập và lấy lịch hẹn đã hoàn thành
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
                        ->with([
                            'replies.user',
                            'appointment.order.services'
                        ])
                        ->first();
                }
            }
        }

        // Danh sách các bác sĩ cùng chuyên khoa
        $doctor->related_doctors = Doctor::where('department_id', $doctor->department_id)
            ->where('id', '!=', $doctor->id)
            ->with([
                'user',
                'department',
                'experiences',
            ])
            ->get();


        return view('client.doctors_detail', compact(
            'doctor',
            'ratingBreakdown',
            'appointment',
            'alreadyReviewed',
            'userReview'
        ));
    }
}
