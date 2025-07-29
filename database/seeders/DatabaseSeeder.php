<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\FileUploadSeeder;
use Database\Seeders\StatisticSeeder;
use Database\Seeders\ReviewSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,


            TaskSeeder::class,

            UserSeeder::class,
            DepartmentSeeder::class,
            RoomSeeder::class,

            DoctorSeeder::class,
            DoctorDetailSeeder::class,
            DoctorServiceSeeder::class,
            WorkingScheduleSeeder::class,
            DoctorLeaveSeeder::class,

            DoctorDetailSeeder::class,
            DoctorServiceSeeder::class,

            SpecialtySeeder::class,


            ServiceCategorySeeder::class,
            ServiceSeeder::class,


            AppointmentSeeder::class,

            AppointmentLogSeeder::class,
            StatisticSeeder::class,

            PromotionSeeder::class,
            PaymentSeeder::class,
            PaymentHistorySeeder::class,

            MedicineSeeder::class,
            MedicalRecordSeeder::class,
            PrescriptionSeeder::class,
            PrescriptionItemSeeder::class,

             TreatmentPlanSeeder::class,


            FileUploadSeeder::class,
            UploadHistorySeeder::class,

            BlogSeeder::class,

            ContactSeeder::class,
      

             NotificationSeeder::class,
            FaqSeeder::class,
            OrderSeeder::class,
            NotificationSeeder::class,
            FaqSeeder::class,
            ReviewSeeder::class,
            DoctorServiceSeeder::class,
            DoctorDetailSeeder::class,

        SpecialtySeeder::class,
        DoctorSeeder::class,
        DoctorDetailSeeder::class,

        ReviewReplySeeder::class



        ]);
    }
}
