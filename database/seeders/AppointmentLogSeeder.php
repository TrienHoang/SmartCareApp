<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\AppointmentLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AppointmentLogSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy danh sách ID lịch hẹn
        $appointmentIds = Appointment::pluck('id');

        if ($appointmentIds->isEmpty()) {
            $this->command->warn('⚠️ Không có lịch hẹn nào để tạo log.');
            return;
        }

        foreach ($appointmentIds as $appointmentId) {
            AppointmentLog::create([
                'appointment_id' => $appointmentId,
                'changed_by' => User::inRandomOrder()->value('id'),
                'status_before' => 'pending',
                'status_after' => 'confirmed',
                'change_time' => now()->subDays(rand(1, 5)),
                'note' => fake()->sentence(),
            ]);

            AppointmentLog::create([
                'appointment_id' => $appointmentId,
                'changed_by' => User::inRandomOrder()->value('id'),
                'status_before' => 'confirmed',
                'status_after' => 'completed',
                'change_time' => now()->subDays(rand(0, 1)),
                'note' => fake()->sentence(),
            ]);
        }
    }
}
