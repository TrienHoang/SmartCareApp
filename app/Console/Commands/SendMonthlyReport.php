<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\Statistic;
use App\Mail\MonthlyReportMail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StatisticExport;
use Illuminate\Support\Carbon;

class SendMonthlyReport extends Command
{
    protected $signature = 'report:email {type=monthly}';
    protected $description = 'Gửi báo cáo thống kê tháng hoặc năm tới email quản lý';

    public function handle(): void
    {
        $type = $this->argument('type');

        $now = now();
        $date = match ($type) {
            'monthly' => $now->copy()->startOfMonth(),
            'yearly' => $now->copy()->startOfYear(),
            default => $now->copy()->startOfMonth(),
        };

        $stat = Statistic::where('type', $type)
            ->whereDate('date', $date)
            ->first();

        if (!$stat) {
            $this->error("Không tìm thấy dữ liệu thống kê cho {$type} ngày {$date->toDateString()}");
            return;
        }

        // Xuất file Excel tạm thời
        $fileName = "bao-cao-{$type}-" . $date->format('Y-m') . ".xlsx";
        $path = storage_path("app/reports/{$fileName}");
        Excel::store(new StatisticExport($stat), "reports/{$fileName}");

        // Gửi email đến quản lý
        Mail::to('quanly@example.com')->send(new MonthlyReportMail($stat, $path));

        $this->info("Đã gửi báo cáo {$type} tới email quản lý.");
    }
}
