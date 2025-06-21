<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement([
                'Khám bệnh',
                'Xét nghiệm',
                'Chẩn đoán hình ảnh',
                'Nha khoa',
                'Tai mũi họng',
                'Dinh dưỡng',
                'Vật lý trị liệu'
            ]),
            'description' => $this->faker->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
