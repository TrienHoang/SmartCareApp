<?php

namespace App\Http\Controllers\reception;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\WorkingSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ReceptionistController extends Controller
{
    public function index()
    {
        return view('reception.scan_qr', ['title' => 'Bảng điều khiển lễ tân']);
    }

    public function checkinView()
    {
        return view('reception.checkin');
    }

    public function getAppointmentByQR(Request $request)
    {
        $qrCodeData = $request->validate([
            'qr_code_data' => 'required|string|max:255',
        ])['qr_code_data'];

        try {
            $appointment = Cache::remember("appointment_qr_{$qrCodeData}", 600, function () use ($qrCodeData) {
                return Appointment::query()
                    ->where('qr_code', $qrCodeData)
                    ->with(['patient', 'doctor.user', 'service.department'])
                    ->first();
            });

            if (!$appointment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã QR không hợp lệ hoặc không tìm thấy cuộc hẹn.'
                ], 404);
            }

            $room = Cache::remember("doctor_room_{$appointment->doctor_id}", 600, function () use ($appointment) {
                $schedule = WorkingSchedule::where('doctor_id', $appointment->doctor_id)->first();
                return $schedule && $schedule->room ? $schedule->room->name : 'Chưa xác định';
            });

            return response()->json([
                'success' => true,
                'appointment' => [
                    'id' => $appointment->id,
                    'patient_name' => $appointment->patient ? $appointment->patient->full_name : 'N/A',
                    'patient_phone' => $appointment->patient ? $appointment->patient->phone : 'N/A',
                    'doctor_name' => $appointment->doctor && $appointment->doctor->user ? $appointment->doctor->user->full_name : 'N/A',
                    'service_name' => $appointment->service ? $appointment->service->name : 'N/A',
                    'room' => $room,
                    'department' => $appointment->service && $appointment->service->department ? $appointment->service->department->name : 'N/A',
                    'appointment_time' => $appointment->appointment_time ? $appointment->appointment_time->format('H:i d/m/Y') : 'N/A',
                    'status' => $appointment->status,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error("QR get error: {$e->getMessage()}", [
                'qr_code' => $qrCodeData,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi lấy thông tin cuộc hẹn.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function confirmCheckin(Request $request)
    {
        $appointmentId = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
        ])['appointment_id'];

        try {
            $appointment = Appointment::findOrFail($appointmentId);

            if (in_array($appointment->status, ['cancelled', 'checked_in', 'completed'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Trạng thái không cho phép check-in.'
                ], 400);
            }

            if ($appointment->appointment_time->toDateString() !== now()->toDateString()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cuộc hẹn này không phải cho hôm nay.'
                ], 400);
            }

            $appointment->update([
                'status' => 'checked_in',
                'check_in_time' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Check-in thành công.'
            ]);
        } catch (\Exception $e) {
            Log::error("Confirm check-in error: {$e->getMessage()}", ['appointment_id' => $appointmentId]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi check-in.'
            ], 500);
        }
    }
}
