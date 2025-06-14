<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin_Notification; // Import Model của bạn
use App\Models\User; // Import Model User
use App\Notifications\AdminPanelNotification; // Import Laravel Notification Class
use Illuminate\Support\Facades\Log;
// use Spatie\Permission\Models\Role; // Nếu bạn dùng Spatie Laravel Permission

class SendScheduledNotifications extends Command
{
    /**
     * The name and signature of the console command.
     * Tên và chữ ký của lệnh console.
     * @var string
     */
    protected $signature = 'notifications:send-scheduled';

    /**
     * The console command description.
     * Mô tả của lệnh console.
     * @var string
     */
    protected $description = 'Gửi các thông báo đã lên lịch đến người dùng.';

    /**
     * Execute the console command.
     * Thực thi lệnh console.
     */
    public function handle()
    {
        $this->info('Bắt đầu tìm kiếm và gửi các thông báo đã lên lịch...');

        // Lấy tất cả các thông báo có trạng thái 'scheduled'
        // và thời gian lên lịch đã đến hoặc trong quá khứ
        $scheduledNotifications = Admin_Notification::where('status', 'scheduled')
                                                    ->where('scheduled_at', '<=', now())
                                                    ->get();

        if ($scheduledNotifications->isEmpty()) {
            $this->info('Không có thông báo nào cần gửi theo lịch trình vào lúc này.');
            return Command::SUCCESS;
        }

        $this->info(count($scheduledNotifications) . ' thông báo đã lên lịch được tìm thấy.');

        foreach ($scheduledNotifications as $notification) {
            try {
                // Đặt trạng thái của thông báo thành 'sending' để tránh gửi lại
                $notification->update(['status' => 'sending']);
                $this->info("Đang xử lý thông báo: " . $notification->title);

                $usersToNotify = collect();

                // Logic để lấy danh sách người nhận dựa trên recipient_type và recipient_data
                if ($notification->recipient_type === 'all') {
                    $usersToNotify = User::all();
                } elseif ($notification->recipient_type === 'specific_users' && is_array($notification->recipient_data)) {
                    $usersToNotify = User::whereIn('id', $notification->recipient_data)->get();
                } elseif ($notification->recipient_type === 'roles' && is_array($notification->recipient_data)) {
                    // Giả sử bạn đang dùng Spatie Laravel Permission
                    // $usersToNotify = User::role($notification->recipient_data)->get();

                    // Hoặc nếu bạn có cột 'role' trong bảng users:
                    $usersToNotify = User::whereIn('role', $notification->recipient_data)->get();
                }
                // TODO: Thêm logic cho 'by_condition' nếu bạn triển khai nó

                if ($usersToNotify->isEmpty()) {
                    $this->warn("Thông báo ID {$notification->id} - Tiêu đề: {$notification->title} không có người nhận hợp lệ.");
                    $notification->update(['status' => 'failed', 'sent_at' => now()]); // Cập nhật trạng thái failed
                    continue; // Chuyển sang thông báo tiếp theo
                }

                foreach ($usersToNotify as $user) {
                    // Gửi Laravel Notification (sẽ được đẩy vào queue)
                    $user->notify(new AdminPanelNotification([
                        'id' => $notification->id,
                        'title' => $notification->title,
                        'content' => $notification->content,
                        'type' => $notification->type,
                    ]));
                }

                // Cập nhật trạng thái của AdminNotification sau khi đã dispatch tất cả người nhận
                $notification->update(['status' => 'sent', 'sent_at' => now()]);
                $this->info("Thông báo ID {$notification->id} - Tiêu đề: \"{$notification->title}\" đã được gửi thành công đến " . $usersToNotify->count() . " người nhận.");

            } catch (\Exception $e) {
                // Ghi log lỗi và cập nhật trạng thái thông báo
                Log::error("Lỗi khi gửi thông báo lên lịch ID {$notification->id}: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
                $notification->update(['status' => 'failed']);
                $this->error("Lỗi khi gửi thông báo ID {$notification->id}: " . $e->getMessage());
            }
        }

        $this->info('Hoàn tất việc gửi các thông báo đã lên lịch.');
        return Command::SUCCESS;
    }
}