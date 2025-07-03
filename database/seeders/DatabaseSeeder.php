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
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([

<<<<<<< HEAD
            TaskSeeder::class, // đã bao gồm tạo Task + Comment + Log
=======
            UserSeeder::class,
            DepartmentSeeder::class,
            RoomSeeder::class,

            DoctorSeeder::class,
            WorkingScheduleSeeder::class,
            DoctorLeaveSeeder::class,

            ServiceCategorySeeder::class,
            ServiceSeeder::class,

            AppointmentSeeder::class,           // 👈 phải chạy trước log/statistics
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
            TreatmentHistorySeeder::class,

            FileUploadSeeder::class,
            UploadHistorySeeder::class,

            BlogSeeder::class,
            // NotificationSeeder::class,
            FaqSeeder::class,
            OrderSeeder::class,
            // NotificationSeeder::class,
            //   FaqSeeder::class,
            ReviewSeeder::class,
>>>>>>> 8b50d06627551d61ef7f0f455357c188c304bd94
        ]);

    }
}
