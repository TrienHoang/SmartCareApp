<?php

namespace Database\Factories;

use App\Models\TreatmentPlan;
use App\Models\User;
use App\Models\Role; // Import model Role của bạn
use Illuminate\Database\Eloquent\Factories\Factory;

class TreatmentPlanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TreatmentPlan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Lấy ID của các vai trò
        $doctorRoleId = Role::where('name', 'doctor')->first()->id;
        $patientRoleId = Role::where('name', 'patient')->first()->id;

        // Lấy danh sách các User có vai trò 'doctor' dựa vào role_id
        $doctors = User::where('role_id', $doctorRoleId)->get();
        // Lấy danh sách các User có vai trò 'patient' dựa vào role_id
        $patients = User::where('role_id', $patientRoleId)->get();

        // Đảm bảo rằng có ít nhất một bác sĩ trong DB
        if ($doctors->isEmpty()) {
            $doctor = User::factory()->create([
                'name' => 'Dr. ' . $this->faker->lastName(),
                'email' => 'doctor_gen_' . $this->faker->unique()->randomNumber(5) . '@example.com',
                'password' => bcrypt('password'),
                'role_id' => $doctorRoleId, // Gán role_id
            ]);
            // Cập nhật lại danh sách bác sĩ
            $doctors = User::where('role_id', $doctorRoleId)->get();
        }

        // Đảm bảo rằng có ít nhất một bệnh nhân trong DB
        if ($patients->isEmpty()) {
            $patient = User::factory()->create([
                'name' => 'Patient_gen ' . $this->faker->lastName(),
                'email' => 'patient_gen_' . $this->faker->unique()->randomNumber(5) . '@example.com',
                'password' => bcrypt('password'),
                'role_id' => $patientRoleId, // Gán role_id
            ]);
            // Cập nhật lại danh sách bệnh nhân
            $patients = User::where('role_id', $patientRoleId)->get();
        }

        return [
           'plan_title' => $this->faker->sentence(3),
        // 'description' => $this->faker->paragraph(2), // Xóa hoặc comment dòng này
        'diagnosis' => $this->faker->paragraph(1), // Thêm dòng này
        'goal' => $this->faker->paragraph(1), // Thêm dòng này
        'total_estimated_cost' => $this->faker->randomFloat(2, 100, 5000), // Ví dụ thêm giá trị
        'notes' => $this->faker->text(200), // Ví dụ thêm giá trị
        'doctor_id' => $doctors->random()->id,
        'patient_id' => $patients->random()->id,
        'start_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
        'end_date' => $this->faker->dateTimeBetween('now', '+1 year'),
        'status' => $this->faker->randomElement(['draft', 'active', 'completed', 'paused', 'cancelled']),
    ];
    }
}