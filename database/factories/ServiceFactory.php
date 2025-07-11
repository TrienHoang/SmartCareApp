<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ServiceCategory;

class ServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'service_cate_id' => ServiceCategory::inRandomOrder()->first()?->id ?? 1,
            'department_id' => Department::inRandomOrder()->first()?->id ?? 1,
            'name' => $this->faker->randomElement([
                'Khám tổng quát',
                'Xét nghiệm máu',
                'Siêu âm ổ bụng',
                'Chụp X-quang',
                'Điện tâm đồ',
                'Khám tai mũi họng',
                'Tư vấn dinh dưỡng',
                'Khám răng'
            ]),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 100000, 1000000), // giá từ 100k - 1 triệu
            'duration' => $this->faker->numberBetween(10, 30), // phút
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ];
    }
}
