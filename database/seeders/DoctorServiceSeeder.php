<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\Service;

class DoctorServiceSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = Doctor::all();
        $services = Service::all();

        // Nếu chưa có dữ liệu thì dừng lại
        if ($doctors->isEmpty() || $services->isEmpty()) {
            $this->command->warn('❌ Không có bác sĩ hoặc dịch vụ để gắn.');
            return;
        }

        foreach ($doctors as $doctor) {
            $matchingServices = $services->where('department_id', $doctor->department_id);

            if ($matchingServices->isEmpty()) {
                continue;
            }

            $randomServiceIds = $matchingServices->random(min(3, $matchingServices->count()))->pluck('id')->toArray();
            $doctor->services()->sync($randomServiceIds);
        }


        $this->command->info('✅ Đã gắn dịch vụ cho tất cả bác sĩ.');
    }
}
