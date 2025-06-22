<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;

app()->booted(function () {
    $schedule = app(Schedule::class);

    // Chạy snapshot mỗi ngày lúc 23:59
    $schedule->command('snapshot:statistics daily')->dailyAt('23:59');

    // Chạy snapshot mỗi tháng (ngày 1 lúc 01:00)
    $schedule->command('snapshot:statistics monthly')->monthlyOn(1, '01:00');

    // Chạy snapshot mỗi năm (1/1 lúc 01:30)
    $schedule->command('snapshot:statistics yearly')->yearlyOn(1, 1, '01:30');

    // ✅ Di chuyển lệnh này vào đây
    $schedule->command('notifications:send-scheduled')->everyMinute();
});

// Lệnh CLI
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
