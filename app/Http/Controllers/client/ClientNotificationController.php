<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Admin_notification; // Đảm bảo import đúng model của bạn
use App\Models\NotificationUserStatus; // Đảm bảo import đúng model của bạn
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Thêm import DB facade để debug query
// The Log facade was removed as per your request

class ClientNotificationController extends Controller
{
    /**
     * Hiển thị danh sách các thông báo cho người dùng hiện tại.
     * Thông báo được lấy từ Admin_notification và được lọc theo trạng thái của người dùng.
     */

    public function index()
    {
        $userId = Auth::id();

        if (!$userId) {
            return view('client.notifications.index', [
                'notifications' => collect(),
                'unreadNotificationsCount' => 0
            ]);
        }

        $userRoleId = Auth::user()->role_id ?? null;

        // Parse recipient_ids helper
        $parseRecipientIds = function ($recipientIds) {
            if (empty($recipientIds)) return [];

            $decoded = json_decode($recipientIds, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }

            $cleaned = preg_replace('/[^0-9,]/', '', $recipientIds);
            return array_map('trim', array_filter(explode(',', $cleaned)));
        };

        // Check if user can view a notification
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

        // Lấy tất cả notifications chưa bị user hiện tại "xóa"
        $allNotifications = Admin_notification::where('status', 'sent')
            ->whereDoesntHave('userStatuses', function ($q) use ($userId) {
                $q->where('user_id', $userId)->where('is_deleted', true);
            })
            ->with(['userStatuses' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])
            ->orderByDesc('sent_at')
            ->get();

        // Lọc thông báo mà user hiện tại được xem
        $notifications = $allNotifications->filter(function ($notification) use ($canUserSee) {
            return $canUserSee($notification);
        });

        // Đếm số thông báo chưa đọc
        $unreadNotificationsCount = $notifications->filter(function ($notification) {
            $status = $notification->userStatuses->first();
            return !$status || !$status->is_read;
        })->count();

        return view('client.notifications.index', compact('notifications', 'unreadNotificationsCount'));
    }



    /**
     * Đánh dấu một thông báo cụ thể là đã xóa cho người dùng hiện tại.
     * Điều này không thực sự xóa bản ghi Admin_notification, mà thay vào đó
     * tạo/cập nhật trạng thái người dùng cụ thể cho biết nó đã bị xóa.
     */
    public function destroy($id)
    {
        $userId = Auth::id();
        if (!$userId) {
            return redirect()->back()->with('error', 'Bạn cần đăng nhập để thực hiện hành động này.');
        }

        // Mark the notification as deleted for the specific user
        // If a record exists, update it. If not, create it.
        NotificationUserStatus::updateOrCreate(
            ['notification_id' => $id, 'user_id' => $userId],
            ['is_deleted' => true, 'is_read' => true] // When deleted, also consider it as read
        );

        return redirect()->back()->with('success', 'Đã xóa thông báo.');
    }

    /**
     * Đánh dấu một thông báo cụ thể là đã đọc cho người dùng hiện tại.
     * Điều này tạo/cập nhật trạng thái người dùng cụ thể cho biết nó đã được đọc.
     */
    public function markAsRead(Request $request, Admin_notification $notification)
    {
        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Bạn cần đăng nhập để thực hiện hành động này.'], 401);
        }

        $userRoleId = Auth::user()->role_id ?? null;

        // Kiểm tra xem thông báo còn tồn tại và chưa bị xóa bởi user
        $canAccess = Admin_notification::where('id', $notification->id)
            ->where('status', 'sent')
            ->whereDoesntHave('userStatuses', function ($q) use ($userId) {
                $q->where('user_id', $userId)->where('is_deleted', true);
            })
            ->where(function ($q) use ($userId, $userRoleId) {
                $q->where('recipient_type', 'all')
                    ->orWhere(function ($q2) use ($userId) {
                        $q2->where('recipient_type', 'specific_users')
                            ->where(function ($q3) use ($userId) {
                                $q3->whereJsonContains('recipient_ids', (int)$userId)
                                    ->orWhereJsonContains('recipient_ids', (string)$userId);
                            });
                    });

                if (!is_null($userRoleId)) {
                    $q->orWhere(function ($q4) use ($userRoleId) {
                        $q4->where('recipient_type', 'roles')
                            ->where(function ($q5) use ($userRoleId) {
                                $q5->whereJsonContains('recipient_ids', (int)$userRoleId)
                                    ->orWhereJsonContains('recipient_ids', (string)$userRoleId);
                            });
                    });
                }
            })
            ->exists();

        if (!$canAccess) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy thông báo hoặc bạn không có quyền truy cập.'], 404);
        }

        NotificationUserStatus::updateOrCreate(
            ['notification_id' => $notification->id, 'user_id' => $userId],
            ['is_read' => true]
        );

        return response()->json(['success' => true, 'message' => 'Thông báo đã được đánh dấu là đã đọc.']);
    }


    /**
     * Hiển thị chi tiết của một thông báo cụ thể và đánh dấu nó là đã đọc.
     * Phương thức này thường được sử dụng cho các yêu cầu AJAX để lấy nội dung thông báo.
     */
    public function show(Admin_notification $notification)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Bạn cần đăng nhập để xem thông báo.'], 401);
        }

        $userId = $user->id;
        $userRoleId = $user->role_id;

        if ($notification->status !== 'sent') {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy thông báo hoặc bạn không có quyền truy cập.'], 404);
        }

        // Xử lý recipient_ids
        $recipientIds = json_decode($notification->recipient_ids, true);
        if (!is_array($recipientIds)) {
            $recipientIds = array_map('trim', explode(',', preg_replace('/[^0-9,]/', '', $notification->recipient_ids ?? '')));
        }

        // Kiểm tra quyền truy cập
        $canAccess = match ($notification->recipient_type) {
            'all' => true,
            'specific_users' => in_array($userId, $recipientIds) || in_array((string)$userId, $recipientIds),
            'roles' => $userRoleId && (in_array($userRoleId, $recipientIds) || in_array((string)$userRoleId, $recipientIds)),
            default => false,
        };

        if (!$canAccess) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy thông báo hoặc bạn không có quyền truy cập.'], 404);
        }

        // Kiểm tra nếu user đã xóa
        $userStatus = NotificationUserStatus::where('notification_id', $notification->id)
            ->where('user_id', $userId)
            ->first();

        if ($userStatus && $userStatus->is_deleted) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy thông báo hoặc bạn không có quyền truy cập.'], 404);
        }

        // Đánh dấu là đã đọc
        NotificationUserStatus::updateOrCreate(
            ['notification_id' => $notification->id, 'user_id' => $userId],
            ['is_read' => true, 'read_at' => now()]
        );

        return response()->json([
            'id' => $notification->id,
            'title' => $notification->title,
            'content' => $notification->content,
            'sent_at' => optional($notification->sent_at)->toIso8601String(),
        ]);
    }



    /**
     * Đánh dấu tất cả các thông báo hiển thị và chưa bị xóa là đã đọc cho người dùng hiện tại.
     * Phương thức này sử dụng thao tác upsert hiệu quả cho các cập nhật hàng loạt.
     */
    public function markAllAsRead()
    {
        try {
            $user = Auth::user();
            $userId = $user->id;
            $userRoleId = $user->role_id ?? null;

            // Helper parse JSON hoặc chuỗi CSV
            $parseRecipientIds = function ($recipientIds) {
                if (empty($recipientIds)) return [];

                $decoded = json_decode($recipientIds, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return $decoded;
                }

                $cleaned = preg_replace('/[^0-9,]/', '', $recipientIds);
                return array_map('trim', array_filter(explode(',', $cleaned)));
            };

            // Kiểm tra người dùng có quyền xem thông báo
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

            // Lấy tất cả thông báo hợp lệ và chưa bị xóa
            $allNotifications = Admin_notification::where('status', 'sent')
                ->whereDoesntHave('userStatuses', function ($q) use ($userId) {
                    $q->where('user_id', $userId)->where('is_deleted', true);
                })
                ->with(['userStatuses' => function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                }])
                ->orderByDesc('sent_at')
                ->get();

            // Lọc ra các thông báo user có quyền xem và chưa đọc
            $unreadNotifications = $allNotifications->filter(function ($notification) use ($canUserSee, $userId) {
                if (!$canUserSee($notification)) return false;

                $status = $notification->userStatuses->first();
                return !$status || !$status->is_read;
            });

            if ($unreadNotifications->isEmpty()) {
                return redirect()->back()->with('info', 'Không có thông báo nào để đánh dấu.');
            }

            $now = now();
            $dataToInsert = [];

            foreach ($unreadNotifications as $notification) {
                $dataToInsert[] = [
                    'notification_id' => $notification->id,
                    'user_id' => $userId,
                    'is_read' => true,
                    'is_deleted' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // upsert đảm bảo không ghi đè bản ghi đã đọc
            NotificationUserStatus::upsert(
                $dataToInsert,
                ['notification_id', 'user_id'],
                ['is_read', 'updated_at']
            );

            return redirect()->back()->with('success', 'Đã đánh dấu tất cả thông báo là đã đọc.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra. Vui lòng thử lại.');
        }
    }



    /**
     * Đánh dấu tất cả các thông báo hiển thị là đã xóa cho người dùng hiện tại.
     * Phương thức này sử dụng thao tác upsert hiệu quả cho các cập nhật hàng loạt.
     */
    public function deleteAll(Request $request)
    {
        $user = auth()->user();
        $userId = $user->id;
        $userRoleId = $user->role_id ?? null;

        // Helper: parse recipient_ids
        $parseRecipientIds = function ($recipientIds) {
            if (empty($recipientIds)) return [];

            $decoded = json_decode($recipientIds, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }

            $cleaned = preg_replace('/[^0-9,]/', '', $recipientIds);
            return array_map('trim', array_filter(explode(',', $cleaned)));
        };

        // Kiểm tra người dùng có quyền xem thông báo này
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

        // Lấy toàn bộ thông báo gửi rồi, chưa bị user này xóa
        $allNotifications = Admin_notification::where('status', 'sent')
            ->whereDoesntHave('userStatuses', function ($q) use ($userId) {
                $q->where('user_id', $userId)->where('is_deleted', true);
            })
            ->with(['userStatuses' => function ($q) use ($userId) {
                $q->where('user_id', $userId);
            }])
            ->get();

        // Duyệt qua từng thông báo xem user có thể xem không
        $count = 0;
        foreach ($allNotifications as $notification) {
            if ($canUserSee($notification)) {
                NotificationUserStatus::updateOrCreate(
                    [
                        'notification_id' => $notification->id,
                        'user_id' => $userId,
                    ],
                    [
                        'is_read' => true,
                        'read_at' => now(),
                        'is_deleted' => true,
                    ]
                );
                $count++;
            }
        }

        return $request->wantsJson()
            ? response()->json(['status' => 'success', 'deleted' => $count])
            : back()->with('success', "Đã xóa $count thông báo.");
    }
}
