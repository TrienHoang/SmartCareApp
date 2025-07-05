<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckInactiveUsers extends Command
{
    protected $signature = 'users:check-inactive';
    protected $description = 'Check and set inactive users to offline if not logged in for 90 days';

    public function handle()
    {
        $ninetyDaysAgo = Carbon::now()->subDays(90);
        $users = User::where('status', 'online')
            ->where('last_login', '<', $ninetyDaysAgo)
            ->get();

        foreach ($users as $user) {
            if ($user->id !== auth()->id()) { // Không cập nhật tài khoản đang đăng nhập
                $user->status = 'offline';
                $user->save();
                $this->info("User ID {$user->id} set to offline due to inactivity.");
            }
        }

        $this->info('Inactive user check completed.');
    }
}
