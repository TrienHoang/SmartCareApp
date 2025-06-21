<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin_Notification;
use App\Models\User;
use App\Notifications\AdminPanelNotification;
use Illuminate\Support\Facades\Log;

class SendScheduledNotifications extends Command
{
    protected $signature = 'notifications:send-scheduled';
    protected $description = 'Gửi các thông báo đã lên lịch đến người dùng.';

    public function handle()
    {
        $currentTime = now();
        $scheduledNotifications = Admin_Notification::where('status', 'scheduled')
            ->where('scheduled_at', '<=', $currentTime)
            ->get();

        if ($scheduledNotifications->isEmpty()) {
            $this->info('Không có thông báo nào cần gửi.');
            return Command::SUCCESS;
        }

        foreach ($scheduledNotifications as $notification) {
            try {
                $notification->update(['status' => 'sending']);

                $usersToNotify = $this->getUsersToNotify($notification);

                if ($usersToNotify->isEmpty()) {
                    $notification->update(['status' => 'failed', 'sent_at' => now()]);
                    $this->warn("Thông báo ID {$notification->id} không có người nhận hợp lệ.");
                    continue;
                }

                foreach ($usersToNotify as $user) {
                    try {
                        $user->notify(new AdminPanelNotification([
                            'id' => $notification->id,
                            'title' => $notification->title,
                            'content' => $notification->content,
                            'type' => $notification->type,
                        ]));
                    } catch (\Exception $e) {
                        Log::error("Lỗi gửi thông báo tới user ID {$user->id}: " . $e->getMessage());
                    }
                }

                $notification->update(['status' => 'sent', 'sent_at' => now()]);
                $this->info("Thông báo ID {$notification->id} đã gửi đến {$usersToNotify->count()} người nhận.");
            } catch (\Exception $e) {
                Log::error("Lỗi xử lý thông báo ID {$notification->id}: " . $e->getMessage());
                $notification->update(['status' => 'failed']);
                $this->error("Lỗi gửi thông báo ID {$notification->id}.");
            }
        }

        $this->info('Hoàn tất gửi thông báo.');
        return Command::SUCCESS;
    }

    /**
     * Lấy danh sách người dùng cần gửi thông báo.
     *
     * @param Admin_Notification $notification
     * @return \Illuminate\Support\Collection
     */
    private function getUsersToNotify(Admin_Notification $notification)
    {
        $recipientData = json_decode($notification->recipient_ids, true) ?? [];

        if ($notification->recipient_type === 'all') {
            return User::all();
        }

        if ($notification->recipient_type === 'specific_users' && is_array($recipientData)) {
            return User::whereIn('id', $recipientData)->get();
        }

        if ($notification->recipient_type === 'roles' && is_array($recipientData)) {
            $usersToNotify = collect();
            foreach ($recipientData as $roleId) {
                $usersToNotify = $usersToNotify->merge(
                    User::where('role_id', intval($roleId))->get()
                );
            }
            return $usersToNotify->unique('id');
        }

        return collect();
    }
}