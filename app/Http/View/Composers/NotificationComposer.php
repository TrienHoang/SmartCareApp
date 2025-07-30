<?php

namespace App\Http\View\Composers;

use App\Models\Admin_notification;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class NotificationComposer
{
    public function compose(View $view)
    {
        $unreadCount = 0;
        $notifications = collect();

        if (Auth::check()) {
            $user = Auth::user();
            $userId = $user->id;
            $userRoleId = $user->role_id ?? null;

            // Helper parse JSON or CSV
            $parseRecipientIds = function ($recipientIds) {
                if (empty($recipientIds)) return [];

                $decoded = json_decode($recipientIds, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return $decoded;
                }

                $cleaned = preg_replace('/[^0-9,]/', '', $recipientIds);
                return array_map('trim', array_filter(explode(',', $cleaned)));
            };

            // Kiểm tra quyền hiển thị
            $canUserSee = function ($notification) use ($userId, $userRoleId, $parseRecipientIds) {
                switch ($notification->recipient_type) {
                    case 'all':
                        return true;
                    case 'specific_users':
                        $ids = $parseRecipientIds($notification->recipient_ids);
                        return in_array($userId, $ids) || in_array((string)$userId, $ids);
                    case 'roles':
                        if (is_null($userRoleId)) return false;
                        $ids = $parseRecipientIds($notification->recipient_ids);
                        return in_array($userRoleId, $ids) || in_array((string)$userRoleId, $ids);
                    default:
                        return false;
                }
            };

            // Lấy tất cả thông báo chưa bị xóa với user hiện tại
            $allNotifications = Admin_notification::where('status', 'sent')
                ->whereDoesntHave('userStatuses', function ($q) use ($userId) {
                    $q->where('user_id', $userId)->where('is_deleted', true);
                })
                ->with(['userStatuses' => function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                }])
                ->orderByDesc('sent_at')
                ->get();

            // Lọc theo quyền người dùng
            $notifications = $allNotifications->filter(function ($notification) use ($canUserSee) {
                return $canUserSee($notification);
            });

            // Đếm chưa đọc
            $unreadCount = $notifications->filter(function ($notification) {
                $status = $notification->userStatuses->first();
                return !$status || !$status->is_read;
            })->count();
        }

        $view->with([
            'notifications' => $notifications,
            'unreadNotificationsCount' => $unreadCount,
            'currentUnreadCount' => $unreadCount,
        ]);
    }
}
