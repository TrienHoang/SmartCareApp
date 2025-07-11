<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;

app()->booted(function () {
    $schedule = app(Schedule::class);

    // Snapshot theo chu kỳ
    $schedule->command('snapshot:statistics daily')->dailyAt('23:59');
    $schedule->command('snapshot:statistics monthly')->monthlyOn(1, '01:00');
    $schedule->command('snapshot:statistics yearly')->yearlyOn(1, 1, '01:30');

    // Gửi thông báo hệ thống định kỳ
    $schedule->command('notifications:send-scheduled')->everyMinute();

    // Gửi thông báo lịch hẹn trước 1 ngày
    $schedule->command('appointments:notify-upcoming')->dailyAt('08:00');

    // Nếu có thêm: Gửi trước 30 phút
    // $schedule->command('appointments:notify-soon')->everyFiveMinutes();
});

// Câu lệnh truyền cảm hứng
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
