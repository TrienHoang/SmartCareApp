<?php 

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Review;
use App\Models\Doctor;
use App\Models\Role;
use App\Models\Appointment;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy danh sách các cuộc hẹn đã hoàn thành
        $appointments = Appointment::where('status', 'completed')->get();

        if ($appointments->isEmpty()) {
            $this->command->warn('⚠️ Không có cuộc hẹn hoàn thành để tạo đánh giá.');
            return;
        }

        // Tạo đánh giá cho từng cuộc hẹn đã hoàn thành
        foreach ($appointments as $appointment) {
            Review::create([
                'appointment_id' => $appointment->id,
                'doctor_id'      => $appointment->doctor_id,
                'patient_id'     => $appointment->patient_id,
                'service_id'     => $appointment->service_id,
                'rating'         => rand(3, 5),
                'comment'        => fake()->sentence(),
                'is_visible'     => true,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }

        $this->command->info('✅ Đã tạo đánh giá cho các cuộc hẹn hoàn thành.');
    }
}
