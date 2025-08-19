<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\DoctorLeave;
use App\Models\Review;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\WorkingSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;


class HomeController extends Controller
{
    public function index()
    {
        $testimonials = Review::where('is_visible', 1)
            ->with(['patient', 'doctor'])
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        $departments = Department::all();

        $dich_vu = Appointment::whereNotIn('status', ['pending', 'cancelled'])
            ->select('service_id', \DB::raw('COUNT(*) as total_bookings'))
            ->groupBy('service_id')
            ->orderByDesc('total_bookings')
            ->with(['service' => function ($query) {
                $query->select('id', 'service_cate_id', 'department_id', 'name', 'description', 'image', 'price', 'duration', 'status')
                    ->where('status', 'active');
            }])
            ->limit(6)
            ->get();

        $doctors = Doctor::whereHas('user', function ($query) {
            $query->where('role_id', 2);
        })
            ->with(['user', 'department', 'reviews'])
            ->withCount('reviews')
            // ->having('reviews_count', '>', 0) // Chỉ lấy bác sĩ có đánh giá
            ->get()
            ->map(function ($doctor) {
                $doctor->average_rating = round($doctor->reviews->avg('rating'), 1);
                return $doctor;
            })
            ->sortByDesc('average_rating')
            ->take(8)
            ->values()
            ->take(7);
        foreach ($doctors as $doctor) {
            $startYear = $doctor->experiences->min('start_year');
            $endYears = $doctor->experiences->map(function ($exp) {
                return $exp->end_year ?? now()->year;
            });
            $endYear = $endYears->max();
            $experienceYears = 0;

            if ($startYear) {
                $experienceYears = $endYear - $startYear;
            }

            $doctor->experience_years = $experienceYears > 0 ? $experienceYears : null;
        }

        $visibleReviews = $doctors->pluck('reviews')->flatten();
        $average_rating_all = round($visibleReviews->avg('rating'), 1);

        return view('client.home', compact('testimonials', 'departments', 'doctors', 'dich_vu'));
    }

    public function searchServices(Request $request)
    {
        $query = $request->query('query', '');
        \Log::info('Searching services', ['query' => $query]);

        $services = Service::where('name', 'like', '%' . $query . '%')
            ->where('status', 'active')
            ->get(['id', 'name', 'duration'])
            ->take(10);

        \Log::info('Services found', ['count' => $services->count(), 'query' => $query]);

        return response()->json([
            'success' => true,
            'services' => $services,
        ]);
    }

    public function searchAvailableSlots(Request $request)
    {
        \Log::info('Searching available slots in HomeController', [
            'service_id' => $request->service_id,
            'appointment_date' => $request->appointment_date,
        ]);

        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date|after_or_equal:today',
        ]);

        $service = Service::findOrFail($validated['service_id']);
        $date = Carbon::parse($validated['appointment_date'])->startOfDay();
        $hasSlots = false;

        // Lấy danh sách bác sĩ cung cấp dịch vụ
        $doctors = Doctor::whereHas('services', function ($q) use ($service) {
            $q->where('service_id', $service->id);
        })->get();

        if ($doctors->isEmpty()) {
            \Log::error('No doctors available for service', ['service_id' => $service->id]);
            return response()->json([
                'success' => false,
                'message' => 'Không có bác sĩ nào cung cấp dịch vụ này.',
            ], 400);
        }

        $workingSchedules = WorkingSchedule::with('shift')
            ->where('day', $date->toDateString())
            ->whereIn('doctor_id', $doctors->pluck('id'))
            ->get()
            ->groupBy('doctor_id');


        $duration = $service->duration;
        $availableSlots = [];

        foreach ($doctors as $doctor) {
            $schedules = $workingSchedules->get($doctor->id);
            if (!$schedules) continue;

            foreach ($schedules as $schedule) {
                $shift = $schedule->shift;
                if (!$shift) continue;

                $shiftStart = Carbon::parse($date->toDateString() . ' ' . $shift->start_time);
                $shiftEnd = Carbon::parse($date->toDateString() . ' ' . $shift->end_time);
                $currentTime = $shiftStart->copy();

                while ($currentTime < $shiftEnd) {
                    $slotTime = $currentTime->copy();
                    $slotEndTime = $slotTime->copy()->addMinutes($duration);

                    if ($slotEndTime > $shiftEnd) break;

                    // Kiểm tra slot đã đặt
                    $isBooked = Appointment::where('doctor_id', $doctor->id)
                        ->where('status', '!=', 'cancelled')
                        ->where(function ($query) use ($slotTime, $slotEndTime) {
                            $query->whereBetween('appointment_time', [$slotTime, $slotEndTime->subSecond()])
                                ->orWhereBetween('end_time', [$slotTime->addSecond(), $slotEndTime]);
                        })
                        ->exists();

                    // Kiểm tra lịch nghỉ
                    $isOnLeave = DoctorLeave::where('doctor_id', $doctor->id)
                        ->where('approved', true)
                        ->whereNull('deleted_at')
                        ->where(function ($query) use ($slotTime, $slotEndTime) {
                            $query->whereBetween('start_date', [$slotTime, $slotEndTime->subSecond()])
                                ->orWhereBetween('end_date', [$slotTime->addSecond(), $slotEndTime])
                                ->orWhere(function ($q) use ($slotTime, $slotEndTime) {
                                    $q->where('start_date', '<=', $slotTime)
                                        ->where('end_date', '>=', $slotEndTime);
                                });
                        })
                        ->exists();

                    $hoursDiff = Carbon::now()->diffInHours($slotTime, false);
                    $isTooSoon = $hoursDiff < $service->min_booking_hours;

                    if (!$isBooked && !$isOnLeave && !$isTooSoon) {
                        $availableSlots[] = [
                            'doctor_id' => $doctor->id,
                            'doctor_name' => $doctor->user->full_name ?? 'Bác sĩ không tên',
                            'start_time' => $slotTime->format('H:i'),
                            'end_time' => $slotEndTime->format('H:i'),
                        ];
                    }

                    $currentTime->addMinutes($duration);
                }
            }
        }


        \Log::info('Available slots check result', [
            'service_id' => $service->id,
            'date' => $date->toDateString(),
            'has_slots' => $hasSlots,
        ]);

        return response()->json([
            'success' => true,
            'has_slots' => count($availableSlots) > 0,
            'date_formatted' => $date->format('d/m/Y'),
            'booking_url' => route('booking.showService', $service->id),
            'message' => count($availableSlots) > 0 ? 'Có khung giờ trống.' : 'Không có khung giờ trống trong ngày này.',
            'available_slots' => $availableSlots,
        ]);
    }
}
