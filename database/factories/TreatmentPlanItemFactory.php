<?php

namespace Database\Factories;

use App\Models\TreatmentPlanItem;
use App\Models\TreatmentPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

class TreatmentPlanItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TreatmentPlanItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
       return [
    'title' => $this->faker->sentence(3), // ✅ đúng cột trong database
    'description' => $this->faker->paragraph(),
    'expected_start_date' => $this->faker->dateTimeBetween('+1 weeks', '+1 month'),
    'expected_end_date' => $this->faker->dateTimeBetween('+1 month', '+2 months'),
    'actual_end_date' => $this->faker->optional()->dateTimeBetween('+1 month', '+3 months'),
    'frequency' => $this->faker->randomElement(['daily', 'weekly', 'once']),
    'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed']),
    'notes' => $this->faker->optional()->text(100),
    'treatment_plan_id' => TreatmentPlan::factory(), // thường override trong seeder
];

    }
}