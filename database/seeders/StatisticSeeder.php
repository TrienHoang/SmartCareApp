<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Statistic;
use Carbon\Carbon;

class StatisticSeeder extends Seeder
{
    public function run()
    {
        $today = Carbon::today();

        // Daily statistics - 7 ngày gần nhất
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            Statistic::create([
                'type' => 'daily',
                'date' => $date,
                'total_doctors' => rand(10, 20),
                'total_patients' => rand(50, 100),
                'total_appointments' => rand(30, 80),
                'appointments_pending' => rand(5, 15),
                'appointments_completed' => rand(10, 30),
                'appointments_cancelled' => rand(5, 10),
                'total_revenue' => rand(1000000, 5000000),
            ]);
        }

        // Monthly statistics - 12 tháng gần nhất
        for ($i = 0; $i < 12; $i++) {
            $month = $today->copy()->subMonths($i)->startOfMonth();
            Statistic::create([
                'type' => 'monthly',
                'date' => $month,
                'total_doctors' => rand(10, 20),
                'total_patients' => rand(500, 1000),
                'total_appointments' => rand(300, 800),
                'appointments_pending' => rand(20, 50),
                'appointments_completed' => rand(200, 500),
                'appointments_cancelled' => rand(30, 60),
                'total_revenue' => rand(10000000, 30000000),
            ]);
        }

        // Yearly statistics - năm hiện tại
        Statistic::create([
            'type' => 'yearly',
            'date' => $today->copy()->startOfYear(),
            'total_doctors' => 20,
            'total_patients' => 2000,
            'total_appointments' => 8000,
            'appointments_pending' => 100,
            'appointments_completed' => 7000,
            'appointments_cancelled' => 900,
            'total_revenue' => 120000000,
        ]);
    }
}

