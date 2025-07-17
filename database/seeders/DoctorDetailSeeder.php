<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Achievement;
use App\Models\Specialty;

class DoctorDetailSeeder extends Seeder
{
    public function run(): void
    {
        // Đảm bảo có specialties để gán
        if (Specialty::count() === 0) {
            $this->command->warn('Không có chuyên môn nào trong bảng specialties. Hãy chạy SpecialtySeeder trước.');
            return;
        }

        Doctor::all()->each(function ($doctor) {
            // Tạo dữ liệu liên quan
            Education::factory(2)->create(['doctor_id' => $doctor->id]);
            Experience::factory(3)->create(['doctor_id' => $doctor->id]);
            Achievement::factory(2)->create(['doctor_id' => $doctor->id]);

            // Gán chuyên môn (1–3)
            $specialtyIds = Specialty::inRandomOrder()->take(rand(1, 3))->pluck('id');
            $doctor->specialties()->sync($specialtyIds);
        });

        $this->command->info('DoctorDetailSeeder đã chạy thành công!');
    }
}
