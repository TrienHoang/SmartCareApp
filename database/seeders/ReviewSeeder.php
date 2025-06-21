<?php 
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Review;
use App\Models\Doctor;
use App\Models\Role;
use App\Models\Appointment;
class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = Doctor::all();
      $patientRoleId = Role::where('name', 'patient')->value('id');
$patients = User::where('role_id', $patientRoleId)->get();
$appointments = Appointment::where('status', 'completed')->get();

        if ($appointments->isEmpty()) {
            $this->command->warn('⚠️ Không có cuộc hẹn hoàn thành để tạo đánh giá.');
            return;
        }

        if ($doctors->count() === 0 || $patients->count() === 0) {
            $this->command->warn('⚠️ Thiếu bác sĩ hoặc bệnh nhân để tạo đánh giá');
            return;
        }

       foreach ($appointments as $appointment) {
            Review::create([
                'appointment_id' => $appointment->id,
                'doctor_id' => $appointment->doctor_id,
                'patient_id' => $appointment->patient_id,
                'service_id'     => $appointment->service_id,
                'rating' => rand(3, 5),
                'comment' => fake()->sentence(),
            ]);
        }
    }
}

