<?php

// database/seeders/MedicalRecordSeeder.php

namespace Database\Seeders;

use App\Models\MedicalRecord;
use App\Models\Appointment;
use Illuminate\Database\Seeder;

class MedicalRecordSeeder extends Seeder
{
    public function run(): void
    {
        $appointmentIds = Appointment::pluck('id');

        if ($appointmentIds->isEmpty()) {
            $this->command->warn('⚠️ Không có appointment để tạo hồ sơ bệnh án.');
            return;
        }

        foreach ($appointmentIds as $id) {
            MedicalRecord::create([
                'appointment_id' => $id,
                'symptoms' => fake()->sentence(),
                'diagnosis' => fake()->sentence(),
                'treatment' => fake()->sentence(),
                'notes' => fake()->sentence(),
                'created_at' => now(),
            ]);
        }
    }
}


