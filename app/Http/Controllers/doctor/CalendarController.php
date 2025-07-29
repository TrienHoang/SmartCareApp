<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CalendarController extends Controller
{
public function index()
    {
        return view('doctor.calendar.index');
    }

public function events(Request $request)
    {
        if (!Auth::check() || Auth::user()->role_id != 2) {
            Log::warning('Truy cập events không hợp lệ: ' . (Auth::check() ? Auth::user()->email : 'Chưa đăng nhập'));
            return response()->json(['error' => 'Bạn không có quyền truy cập. Chỉ có bác sĩ mới xem được.'], 403);
        }

        $doctor = Auth::user()->doctor;
        if (!$doctor) {
            Log::warning('Không tìm thấy thông tin bác sĩ cho user: ' . Auth::id());
            return response()->json(['error' => 'Không tìm thấy thông tin bác sĩ.'], 403);
        }
        $doctorId = $doctor->id;

        $start = Carbon::parse($request->input('start'))->timezone('Asia/Ho_Chi_Minh');
        $end = Carbon::parse($request->input('end'))->timezone('Asia/Ho_Chi_Minh')->endOfDay();

        Log::info('Yêu cầu lịch hẹn:', [
            'doctor_id' => $doctorId,
            'start' => $start->toDateTimeString(),
            'end' => $end->toDateTimeString()
        ]);

        $appointments = Appointment::with(['patient', 'service'])
            ->where('doctor_id', $doctorId)
            ->whereBetween('appointment_time', [$start, $end])
            ->get();

        Log::info('Danh sách cuộc hẹn:', $appointments->toArray());

        $events = $appointments->map(function ($appointment) {
            $statusColor = match ($appointment->status) {
                'pending' => '#ffc107',
                'confirmed' => '#28a745',
                'completed' => '#007bff',
                'cancelled' => '#dc3545',
                default => '#6c757d',
            };

            return [
                'id' => 'appt_' . $appointment->id,
                'title' => '🩺 ' . ($appointment->patient->full_name ?? 'Bệnh nhân') . ' - ' . ($appointment->service->name ?? 'Dịch vụ'),
                'start' => $appointment->appointment_time->timezone('Asia/Ho_Chi_Minh')->toIso8601String(),
                'end' => $appointment->end_time
                    ? $appointment->end_time->timezone('Asia/Ho_Chi_Minh')->toIso8601String()
                    : $appointment->appointment_time->addMinutes(30)->toIso8601String(),
                'color' => $statusColor,
                'url' => route('doctor.appointments.show', $appointment->id),
                'extendedProps' => [
                    'status' => $appointment->status_text ?? $appointment->status,
                    'reason' => $appointment->reason,
                    'check_in_time' => $appointment->check_in_time,
                    'patient' => $appointment->patient->full_name ?? null,
                    'service' => $appointment->service->name ?? null,
                    'cancel_reason' => $appointment->cancel_reason,
                ],
            ];
        });

        return response()->json($events->toArray());
    }

    public function testDb()
    {
        $appointments = Appointment::with(['patient', 'service'])
            ->latest()
            ->take(5)
            ->get();

        Log::info('Kiểm tra dữ liệu lịch hẹn:', $appointments->toArray());

        return response()->json([
            'status' => 'success',
            'message' => 'Dữ liệu đã được truy xuất',
            'data' => $appointments,
        ]);
    }
}
