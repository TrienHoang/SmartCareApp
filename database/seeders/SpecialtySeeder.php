<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Specialty;

class SpecialtySeeder extends Seeder
{
    public function run(): void
    {
        $specialties = [
            ['name' => 'Tim mạch', 'description' => 'Chẩn đoán và điều trị bệnh tim mạch'],
            ['name' => 'Nội tiết', 'description' => 'Bệnh lý tuyến giáp, tiểu đường, hormone'],
            ['name' => 'Da liễu', 'description' => 'Các bệnh ngoài da và thẩm mỹ'],
            ['name' => 'Ngoại thần kinh', 'description' => 'Phẫu thuật hệ thần kinh'],
            ['name' => 'Phục hồi chức năng', 'description' => 'Vật lý trị liệu, phục hồi sau chấn thương'],
            ['name' => 'Hô hấp', 'description' => 'Hen suyễn, viêm phổi, COPD'],
            ['name' => 'Sản phụ khoa', 'description' => 'Chăm sóc sức khỏe phụ nữ và thai kỳ'],
        ];

        foreach ($specialties as $specialty) {
            Specialty::updateOrCreate(
                ['name' => $specialty['name']], // Tìm theo tên
                ['description' => $specialty['description']] // Cập nhật mô tả nếu cần
            );
        }
    }
}
