<?php

namespace Database\Seeders;

use App\Models\MedicalRecord;
use App\Models\Appointment;
use Illuminate\Database\Seeder;

class MedicalRecordSeeder extends Seeder
{
    public function run(): void
    {
        $appointmentIds = Appointment::where('status', 'completed')->pluck('id');

        if ($appointmentIds->isEmpty()) {
            $this->command->warn('⚠️ Không có lịch hẹn hoàn tất để tạo hồ sơ bệnh án.');
            return;
        }

        foreach ($appointmentIds as $id) {
            // Kiểm tra tránh tạo trùng
            if (MedicalRecord::where('appointment_id', $id)->exists()) {
                continue;
            }

            MedicalRecord::create([
                'appointment_id' => $id,
                'symptoms' => fake()->sentence(),
                'diagnosis' => fake()->sentence(),
                'treatment' => fake()->sentence(),
                'notes' => fake()->optional()->paragraph(),
                'created_at' => now(),
            ]);
        }

        $this->command->info('✅ Đã tạo hồ sơ bệnh án cho các lịch hẹn hoàn tất.');
    }
}
