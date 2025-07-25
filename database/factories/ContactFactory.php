<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    public function definition(): array
    {
        $titles = ['Đặt lịch hẹn', 'Tư vấn y khoa', 'Khiếu nại', 'Góp ý', 'Khác'];

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'title' => fake()->randomElement($titles), // ✅ thêm dòng này
            'message' => fake()->paragraph(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
