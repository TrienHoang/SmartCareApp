<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Service;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = Doctor::all();
        $patients = User::whereHas('role', fn($q) => $q->where('name', 'patient'))->get();
        $services = Service::all();

        if ($doctors->isEmpty() || $patients->isEmpty() || $services->isEmpty()) {
            $this->command->warn("⚠️ Cần có dữ liệu bác sĩ, bệnh nhân và dịch vụ để tạo lịch hẹn.");
            return;
        }

        foreach ($patients as $patient) {
            // ✅ Random bác sĩ, dịch vụ, thời gian khám
            $doctor = $doctors->random();
            $service = $services->random();

            $start = Carbon::now()->subDays(rand(5, 30))->setTime(rand(8, 15), rand(0, 59));
            $checkIn = (clone $start)->addMinutes(5);
            $end = (clone $checkIn)->addMinutes(20);

            Appointment::create([
                'patient_id'       => $patient->id,
                'doctor_id'        => $doctor->id,
                'service_id'       => $service->id,
                'appointment_time' => $start,
                'check_in_time'    => $checkIn,
                'end_time'         => $end,
                'status'           => 'completed',
                'reason'           => fake()->sentence(),
                'cancel_reason'    => null,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }

        $this->command->info('✅ Đã tạo lịch sử khám bệnh thành công.');
    }
}
