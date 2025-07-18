<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\DoctorLeave;
use App\Models\WorkingSchedule;
use Carbon\Carbon;

class DoctorAppointmentController extends Controller
{
    public function index(Request $request)
    {
        $doctor = Auth::user()->doctor;

        if (!$doctor) {
            abort(403, 'Không phải tài khoản bác sĩ.');
        }

        $appointments = Appointment::with(['patient', 'service'])
            ->where('doctor_id', $doctor->id)
            ->orderBy('appointment_time', 'desc')
            ->paginate(10);

        return view('doctor.appointments.index', compact('appointments'));
    }

    public function checkAppointmentAvailability(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'appointment_time' => 'required|date_format:Y-m-d\TH:i',
                'doctor_id' => 'required|exists:doctors,id',
                'appointment_id' => 'nullable|exists:appointments,id',
            ]);

            $appointmentTime = Carbon::parse($validated['appointment_time']);
            $doctorId = $validated['doctor_id'];
            $appointmentId = $validated['appointment_id'] ?? null;

            // Check for overlapping appointments
            $isBooked = Appointment::where('doctor_id', $doctorId)
                ->where('appointment_time', $appointmentTime)
                ->when($appointmentId, function ($query) use ($appointmentId) {
                    return $query->where('id', '!=', $appointmentId);
                })
                ->exists();

            if ($isBooked) {
                return response()->json([
                    'booked' => true,
                    'message' => 'Thời gian này đã được đặt bởi một lịch hẹn khác.'
                ], 422);
            }

            // Check working schedule
            $dayOfWeek = $appointmentTime->format('Y-m-d'); 
            $timeOfDay = $appointmentTime->format('H:i:s');

            $isWithinSchedule = WorkingSchedule::where('doctor_id', $doctorId)
                ->where('day', $dayOfWeek)
                ->where('start_time', '<=', $timeOfDay)
                ->where('end_time', '>=', $timeOfDay)
                ->exists();

            if (!$isWithinSchedule) {
                return response()->json([
                    'booked' => true,
                    'message' => 'Thời gian này nằm ngoài lịch làm việc của bác sĩ.'
                ], 422);
            }

            // Check doctor leave (only approved leaves)
            $isOnLeave = DoctorLeave::where('doctor_id', $doctorId)
                ->where('approved', 1)
                ->where('start_date', '<=', $appointmentTime)
                ->where('end_date', '>=', $appointmentTime)
                ->exists();

            if ($isOnLeave) {
                return response()->json([
                    'booked' => true,
                    'message' => 'Bác sĩ đang nghỉ phép vào thời gian này.'
                ], 422);
            }

            // Optional: Check room availability (if needed)
            $schedule = WorkingSchedule::where('doctor_id', $doctorId)
                ->where('day_of_week', $dayOfWeek)
                ->where('start_time', '<=', $timeOfDay)
                ->where('end_time', '>=', $timeOfDay)
                ->first();

            if ($schedule && $schedule->room_id) {
                $isRoomBooked = Appointment::where('room_id', $schedule->room_id)
                    ->where('appointment_time', $appointmentTime)
                    ->when($appointmentId, function ($query) use ($appointmentId) {
                        return $query->where('id', '!=', $appointmentId);
                    })
                    ->exists();

                if ($isRoomBooked) {
                    return response()->json([
                        'booked' => true,
                        'message' => 'Phòng khám đã được đặt cho thời gian này.'
                    ], 422);
                }
            }

            return response()->json([
                'booked' => false,
                'message' => 'Thời gian này hợp lệ.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'booked' => true,
                'message' => 'Đã có lỗi xảy ra khi kiểm tra lịch hẹn: ' . $e->getMessage()
            ], 500);
        }
    }
}
