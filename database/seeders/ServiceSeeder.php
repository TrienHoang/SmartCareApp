<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceCategory;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo 5 danh mục dịch vụ
        $categories = ServiceCategory::factory(5)->create();

        // Với mỗi danh mục, tạo ngẫu nhiên 3–6 dịch vụ tương ứng
        foreach ($categories as $category) {
            Service::factory(rand(3, 6))->create([
                'service_cate_id' => $category->id,
            ]);
        }
    }
}
