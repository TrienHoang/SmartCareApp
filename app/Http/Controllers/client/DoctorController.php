<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Models\Appointment;
use App\Models\Department;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::whereHas('user', function ($query) {
                $query->where('status', 'online'); // chỉ lấy user online
            })
            ->with([
                'user',
                'department',
                'educations',
                'experiences',
                'achievements',
                'specialties',
                'services',
            ])
            ->get();
    
        // Tính số năm kinh nghiệm
        foreach ($doctors as $doctor) {
            $startYear = $doctor->experiences->min('start_year');
            $endYears = $doctor->experiences->map(fn($exp) => $exp->end_year ?? now()->year);
            $endYear = $endYears->max();
            $experienceYears = 0;
    
            if ($startYear) {
                $experienceYears = $endYear - $startYear;
            }
    
            $doctor->experience_years = $experienceYears > 0 ? $experienceYears : null;
        }
    
        $departments = Department::get();
    
        \Log::info('Danh sách bác sĩ online:', [
            'count' => $doctors->count(),
        ]);
    
        return view('client.doctors', compact('doctors', 'departments'));
    }  

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
    
        // 🔹 Lấy năm bắt đầu sớm nhất
        $startYear = $doctor->experiences->min('start_year');
    
        // 🔹 Xử lý end_year: nếu null thì coi là năm hiện tại
        $endYears = $doctor->experiences->map(function ($exp) {
            return $exp->end_year ?? now()->year;
        });
    
        // 🔹 Lấy năm kết thúc muộn nhất
        $endYear = $endYears->max();
    
        // 🔹 Tính số năm kinh nghiệm
        $experienceYears = 0;
        if ($startYear) {
            $experienceYears = $endYear - $startYear;
        }
        $doctor->experience_years = $experienceYears > 0 ? $experienceYears : null;
    
        // 🔹 Tính điểm trung bình & phân bổ đánh giá
        $visibleReviews = $doctor->reviews;
        $doctor->average_rating = $visibleReviews->isNotEmpty()
            ? round($visibleReviews->avg('rating'), 1)
            : null;
        $doctor->review_count = $visibleReviews->count();
    
        $ratingBreakdown = collect([5, 4, 3, 2, 1])->mapWithKeys(function ($star) use ($visibleReviews) {
            return [$star => $visibleReviews->where('rating', $star)->count()];
        });
    
        $appointmentsToReview = collect();
        $userReviews = collect();
    
        if (Auth::check()) {
            // 🔹 Lấy tất cả review của user với bác sĩ này
            $userReviews = Review::where('doctor_id', $doctor->id)
                ->where('patient_id', Auth::id())
                ->with([
                    'replies.user',
                    'appointment.order.services'
                ])
                ->get();
    
            // 🔹 Lấy danh sách các cuộc hẹn hoàn thành
            $completedAppointments = Appointment::where('doctor_id', $doctor->id)
                ->where('patient_id', Auth::id())
                ->where('status', 'completed')
                ->with(['service', 'order.services'])
                ->get();
    
            // 🔹 Lấy id các appointment đã được review
            $reviewedAppointmentIds = $userReviews->pluck('appointment_id')->toArray();
    
            // 🔹 Lọc ra những appointment chưa được review
            $appointmentsToReview = $completedAppointments->whereNotIn('id', $reviewedAppointmentIds);
        }
    
        // 🔹 Danh sách bác sĩ cùng chuyên khoa
        $doctor->related_doctors = Doctor::where('department_id', $doctor->department_id)
            ->where('id', '!=', $doctor->id)
            ->with([
                'user',
                'department',
                'experiences',
            ])
            ->limit(4)
            ->get();
    
        return view('client.doctors_detail', compact(
            'doctor',
            'ratingBreakdown',
            'appointmentsToReview', // 🔹 Các lịch hẹn hoàn thành nhưng chưa có review
            'userReviews'           // 🔹 Tất cả review của user (theo từng appointment)
        ));
    }
    
}
