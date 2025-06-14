<?php

namespace App\Helpers;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\Doctor;
use Carbon\Carbon;

class AppointmentHelper
{
    public static function isConflict($doctorId, $appointmentTime, $serviceId, $appointmentId = null)
    {
        $startTime = Carbon::parse($appointmentTime);
        $duration = Service::findOrFail($serviceId)->duration;
        $endTime = $startTime->copy()->addMinutes($duration);

        $day = $startTime->format('Y-m-d');

        // Kiểm tra trùng lịch của bác sĩ
        $doctorConflict = Appointment::where('doctor_id', $doctorId)
            ->where('appointment_time', '<', $endTime)
            ->whereHas('service', function ($q) use ($startTime) {
                $q->whereRaw('DATE_ADD(appointment_time, INTERVAL duration MINUTE) > ?', [$startTime]);
            })
            ->when($appointmentId, function ($q) use ($appointmentId) {
                $q->where('id', '!=', $appointmentId);
            })
            ->exists();

        // Lấy room_id của bác sĩ
        $roomId = Doctor::findOrFail($doctorId)->room_id;

        // Kiểm tra trùng lịch phòng
        $roomConflict = Appointment::whereHas('doctor', function ($q) use ($roomId) {
                $q->where('room_id', $roomId);
            })
            ->where('appointment_time', '<', $endTime)
            ->whereHas('service', function ($q) use ($startTime) {
                $q->whereRaw('DATE_ADD(appointment_time, INTERVAL duration MINUTE) > ?', [$startTime]);
            })
            ->when($appointmentId, function ($q) use ($appointmentId) {
                $q->where('id', '!=', $appointmentId);
            })
            ->exists();

        return [
            'doctor_conflict' => $doctorConflict,
            'room_conflict' => $roomConflict
        ];
    }
}
