<?php

namespace App\Helpers;

use Carbon\Carbon;

class TimeHelper
{
    /**
     * Kiểm tra xem một thời điểm có nằm trong giờ hành chính hay không.
     * Giờ hành chính: Thứ 2 - Thứ 6, từ 08:00:00 đến trước 17:00:00
     */
    public static function isWithinBusinessHours(?Carbon $datetime = null): bool
    {
        $now = $datetime ?? Carbon::now();

        // Kiểm tra ngày trong tuần (Thứ 2 đến Thứ 6)
        if ($now->isWeekend()) {
            return false;
        }

        // Giới hạn giờ hành chính
        $start = $now->copy()->setTime(8, 0, 0);   // 08:00:00
        $end   = $now->copy()->setTime(17, 0, 0);  // 17:00:00

        return $now->between($start, $end->subSecond()); // trước 17:00:00
    }

    /**
     * Kiểm tra nếu deadline là quá khứ
     */
    public static function isInThePast(Carbon $datetime): bool
    {
        return $datetime->lt(Carbon::now());
    }
}
