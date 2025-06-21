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
            // Chọn random bác sĩ và dịch vụ
            $doctor = $doctors->random();
            $service = $services->random();

            Appointment::create([
              'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'service_id' => $service->id,
                'appointment_time' => Carbon::now()->addDays(rand(0, 30)),
                'status' => 'completed', // trạng thái để review được phép tạo
              
            ]);
        }

        $this->command->info('✅ Đã tạo lịch hẹn thành công giữa bệnh nhân và bác sĩ.');
    }
}
