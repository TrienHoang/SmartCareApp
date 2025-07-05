<?php

namespace Database\Factories;

use App\Models\TreatmentPlanHistory;
use App\Models\TreatmentPlan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TreatmentPlanHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TreatmentPlanHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Giả sử bạn có sẵn một số TreatmentPlan và User
        $treatmentPlan = TreatmentPlan::inRandomOrder()->first();
        $changedBy = User::inRandomOrder()->first();

        // Tạo dữ liệu cũ và mới giả định
        $oldData = [
            'status' => $this->faker->randomElement(['pending', 'paused']),
            'notes' => $this->faker->boolean(50) ? $this->faker->sentence() : null,
            'total_estimated_cost' => $this->faker->numberBetween(500000, 5000000),
        ];
        $newData = [
            'status' => $this->faker->randomElement(['completed', 'pending', 'paused']),
            'notes' => $this->faker->sentence(),
            'total_estimated_cost' => $this->faker->numberBetween(1000000, 10000000),
        ];

        return [
            'treatment_plan_id' => $treatmentPlan ? $treatmentPlan->id : TreatmentPlan::factory(),
            'changed_by_id' => $changedBy ? $changedBy->id : User::factory()->admin(), // Hoặc doctor, tùy vào ai có quyền chỉnh sửa
            'change_description' => $this->faker->randomElement([
                'Admin cập nhật trạng thái kế hoạch',
                'Bác sĩ cập nhật ghi chú',
                'Thay đổi chi phí dự kiến',
                'Kế hoạch được chỉnh sửa',
            ]),
            'old_data' => json_encode($oldData),
            'new_data' => json_encode($newData),
            'changed_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }
}