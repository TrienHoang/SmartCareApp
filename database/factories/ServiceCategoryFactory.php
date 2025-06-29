<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Khám bệnh',
                'Xét nghiệm',
                'Chẩn đoán hình ảnh',
                'Nha khoa',
                'Tai mũi họng',
                'Dinh dưỡng',
                'Vật lý trị liệu',
                'Chăm sóc sức khỏe',
                'Y học cổ truyền',
                'Tư vấn sức khỏe'
            ]),
            'description' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}