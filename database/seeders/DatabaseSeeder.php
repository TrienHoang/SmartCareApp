<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
            DepartmentSeeder::class,
            RoomSeeder::class,
            DoctorSeeder::class,
            WorkingScheduleSeeder::class,
            DoctorLeaveSeeder::class,
            ServiceCategorySeeder::class,
            ServiceSeeder::class,
            AppointmentSeeder::class,
            AppointmentLogSeeder::class,
            PromotionSeeder::class,
            PaymentSeeder::class,
            PaymentHistorySeeder::class,
            MedicineSeeder::class,
            MedicalRecordSeeder::class,
            PrescriptionSeeder::class,
            PrescriptionItemSeeder::class,
            TreatmentPlanSeeder::class,
            TreatmentHistorySeeder::class,
            FileUploadSeeder::class,
            UploadHistorySeeder::class,
            BlogSeeder::class,
            NotificationSeeder::class,
            FaqSeeder::class,
            OrderSeeder::class,
            // NotificationSeeder::class,
            FaqSeeder::class,
        ]);
        $this->call(ReviewSeeder::class);
    }
}
