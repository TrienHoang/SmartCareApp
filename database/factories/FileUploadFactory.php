<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;

class FileUploadFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? 1,
            'appointment_id' => Appointment::inRandomOrder()->first()?->id ?? 1,
            'file_name' => fake()->word() . '.pdf',
            'file_path' => 'uploads/' . fake()->uuid() . '.pdf',
            'size' => fake()->randomFloat(2, 1, 100),
            'file_category' => fake()->randomElement(['Kết quả xét nghiệm', 'Hình ảnh y tế', 'Đơn thuốc', 'Báo cáo khám', 'Giấy tờ', 'Khác']),
            'note' => fake()->sentence(),
            'uploaded_at' => now()
        ];
    }
}