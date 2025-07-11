<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin_notification; 
use App\Models\Role;
use App\Models\User; 
use App\Notifications\AdminPanelNotification; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 


class AdminNotificationController extends Controller
{
    /**
     * Hiển thị danh sách các thông báo.
     */
    public function index(Request $request)
    {
        // Khởi tạo query cơ bản
        $query = Admin_notification::query()->orderBy('created_at', 'desc');

        // Lọc theo từ khóa tìm kiếm
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Lọc theo loại
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        // Phân trang kết quả
        $notifications = $query->paginate(10);

        // Xử lý dữ liệu hiển thị recipients
        foreach ($notifications as $notification) {
            $recipientIds = json_decode($notification->recipient_ids, true) ?? [];

            if ($notification->recipient_type === 'specific_users' && !empty($recipientIds)) {
                $notification->display_recipients = User::whereIn('id', $recipientIds)->pluck('full_name')->toArray();
            } elseif ($notification->recipient_type === 'roles' && !empty($recipientIds)) {
                $notification->display_recipients = Role::whereIn('id', $recipientIds)->pluck('name')->toArray();
            } elseif ($notification->recipient_type === 'all') {
                $notification->display_recipients = ['Tất cả người dùng'];
            } else {
                $notification->display_recipients = ['Không xác định'];
            }
        }

        // Thống kê số lượng thông báo theo trạng thái
        $statusCounts = [
            'sent' => Admin_notification::where('status', 'sent')->count(),
            'scheduled' => Admin_notification::where('status', 'scheduled')->count(),
            'sending' => Admin_notification::where('status', 'sending')->count(),
            'failed' => Admin_notification::where('status', 'failed')->count(),
        ];

        // Định nghĩa các loại và trạng thái cho bộ lọc
        $notificationTypes = ['system', 'appointment_related', 'promotion', 'reminder', 'other'];
        $notificationStatuses = ['draft', 'scheduled', 'sending', 'sent', 'failed'];

        return view('admin.notifications.index', compact(
            'notifications',
            'notificationTypes',
            'notificationStatuses',
            'statusCounts'
        ));
    }

    /**
     * Hiển thị form tạo thông báo mới.
     */
    public function create()
    {
        $notificationTypes = ['system', 'appointment_related', 'promotion', 'reminder', 'other'];

        $roles = Role::all();


        return view('admin.notifications.create', compact('notificationTypes', 'roles')); // Có thể thêm 'users', 'roles'
    }

    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string|in:system,appointment_related,promotion,reminder,other',
            'recipient_type' => 'required|string|in:all,specific_users,roles',
            'recipient_ids' => 'nullable|array',
            'recipient_ids.*' => 'integer',
            'scheduled_at' => 'nullable|date|after_or_equal:now',
            'send_now_checkbox' => 'nullable|boolean',
        ], [
            'title.required' => 'Tiêu đề là bắt buộc.',
            'title.string' => 'Tiêu đề phải là chuỗi ký tự.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
        
            'content.required' => 'Nội dung là bắt buộc.',
            'content.string' => 'Nội dung phải là chuỗi ký tự.',
        
            'type.required' => 'Loại thông báo là bắt buộc.',
            'type.string' => 'Loại thông báo phải là chuỗi.',
            'type.in' => 'Loại thông báo không hợp lệ.',
        
            'recipient_type.required' => 'Loại người nhận là bắt buộc.',
            'recipient_type.string' => 'Loại người nhận phải là chuỗi.',
            'recipient_type.in' => 'Loại người nhận không hợp lệ.',
        
            'recipient_ids.array' => 'Danh sách người nhận phải là một mảng.',
            'recipient_ids.*.integer' => 'Mỗi ID người nhận phải là một số nguyên.',
        
            'scheduled_at.date' => 'Thời gian gửi phải là định dạng ngày hợp lệ.',
            'scheduled_at.after_or_equal' => 'Thời gian gửi phải lớn hơn hoặc bằng thời gian hiện tại.',
        
            'send_now_checkbox.boolean' => 'Giá trị gửi ngay phải là true hoặc false.',
        ]);

        try {
            DB::beginTransaction(); 
            $status = 'draft';
            $sentAt = null;

            if ($request->filled('scheduled_at')) {
                $status = 'scheduled';
            } elseif ($request->boolean('send_now_checkbox')) { // Nếu chọn gửi ngay
                $status = 'sending';
            }


            
            $adminNotification = Admin_notification::create([
                'title' => $request->title,
                'content' => $request->content,
                'type' => $request->type,
                'sender_id' => auth()->id(),
                'recipient_type' => $request->recipient_type,
                'recipient_ids' => json_encode($request->recipient_ids ?? []),
                'scheduled_at' => $request->scheduled_at,
                'status' => $status,
            ]);

            // Nếu thông báo cần được gửi ngay lập tức
            if ($status === 'sending') {
                $this->dispatchNotificationToUsers($adminNotification);
            }
            // Hoàn tất transaction
            DB::commit();


            return redirect()->route('admin.notifications.index')->with('success', 'Thông báo đã được tạo thành công!');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback nếu có lỗi
            Log::error('Error creating notification: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withInput()->with('error', 'Có lỗi xảy ra khi tạo thông báo: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị chi tiết thông báo.
     */
    public function show(Admin_notification $notification)
    {

        $recipientIds = json_decode($notification->recipient_ids, true) ?? [];

        if ($notification->recipient_type === 'specific_users' && !empty($recipientIds)) {
            $notification->display_recipients = User::whereIn('id', $recipientIds)->pluck('full_name')->toArray();
        } elseif ($notification->recipient_type === 'roles' && !empty($recipientIds)) {
            $notification->display_recipients = Role::whereIn('id', $recipientIds)->pluck('name')->toArray();
        } elseif ($notification->recipient_type === 'all') {
            $notification->display_recipients = ['Tất cả người dùng'];
        } else {
            $notification->display_recipients = ['Không xác định'];
        }

        return view('admin.notifications.show', compact('notification'));
    }

    /**
     * Hiển thị form chỉnh sửa thông báo.
     */
    public function edit(Admin_notification $notification)
    {
        // Chỉ cho phép chỉnh sửa nếu trạng thái là 'draft' hoặc 'scheduled'
        if ($notification->status == 'sent' || $notification->status == 'sending') {
            return redirect()->route('admin.notifications.index')->with('error', 'Không thể chỉnh sửa thông báo đã được gửi hoặc đang gửi.');
        }
        $notification->recipient_ids = json_decode($notification->recipient_ids, true) ?? [];

        $notificationTypes = ['system', 'appointment_related', 'promotion', 'reminder', 'other'];

        return view('admin.notifications.edit', compact('notification', 'notificationTypes'));
    }

    /**
     * Cập nhật thông báo đã có trong database.
     */
    public function update(Request $request, Admin_notification $notification)
    {

        // Chỉ cho phép cập nhật nếu trạng thái là 'draft' hoặc 'scheduled'
        if ($notification->status == 'sent' || $notification->status == 'sending') {
            return redirect()->route('admin.notifications.index')->with('error', 'Không thể cập nhật thông báo đã được gửi hoặc đang gửi.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string|in:system,appointment_related,promotion,reminder,other',
            'recipient_type' => 'required|string|in:all,specific_users,roles',
            'recipient_ids' => 'nullable|array',
            'recipient_ids.*' => 'integer',
            'scheduled_at' => 'nullable|date|after_or_equal:now',
        ], [
            'title.required' => 'Tiêu đề là bắt buộc.',
            'title.string' => 'Tiêu đề phải là chuỗi.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
        
            'content.required' => 'Nội dung là bắt buộc.',
            'content.string' => 'Nội dung phải là chuỗi.',
        
            'type.required' => 'Loại thông báo là bắt buộc.',
            'type.string' => 'Loại thông báo phải là chuỗi.',
            'type.in' => 'Loại thông báo không hợp lệ.',
        
            'recipient_type.required' => 'Loại người nhận là bắt buộc.',
            'recipient_type.string' => 'Loại người nhận phải là chuỗi.',
            'recipient_type.in' => 'Loại người nhận không hợp lệ.',
        
            'recipient_ids.array' => 'Danh sách người nhận phải là một mảng.',
            'recipient_ids.*.integer' => 'ID người nhận phải là số nguyên.',
        
            'scheduled_at.date' => 'Thời gian gửi phải là một ngày hợp lệ.',
            'scheduled_at.after_or_equal' => 'Thời gian gửi phải từ hiện tại trở đi.',
        ]);

        try {
            DB::beginTransaction();

            $status = 'draft';

            if ($request->filled('scheduled_at')) {
                $status = 'scheduled';
            }

            $notification->update([
                'title' => $request->title,
                'content' => $request->content,
                'type' => $request->type,
                'recipient_type' => $request->recipient_type,
                'recipient_ids' => json_encode($request->recipient_ids ?? []),
                'scheduled_at' => $request->scheduled_at,
                'status' => $status,
                'sent_at' => null
            ]);

            DB::commit();

            return redirect()->route('admin.notifications.index')->with('success', 'Thông báo đã được cập nhật thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating notification: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withInput()->with('error', 'Có lỗi xảy ra khi cập nhật thông báo: ' . $e->getMessage());
        }
    }

    /**
     * Xóa thông báo khỏi database.
     */
    public function destroy(Admin_notification $notification)
    {
        try {
            // Không cho phép xóa nếu thông báo đã gửi
            if ($notification->status == 'sent' || $notification->status == 'sending') {
                return back()->with('error', 'Không thể xóa thông báo đã được gửi hoặc đang gửi.');
            }
            $notification->delete(); // Sử dụng Soft Deletes nếu bạn muốn
            return redirect()->route('admin.notifications.index')->with('success', 'Thông báo đã được xóa thành công!');
        } catch (\Exception $e) {
            Log::error('Error deleting notification: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->with('error', 'Có lỗi xảy ra khi xóa thông báo: ' . $e->getMessage());
        }
    }

    /**
     */
    public function sendNow(Admin_notification $notification)
    {
        if ($notification->status === 'sent' || $notification->status === 'sending') {
            return back()->with('error', 'Thông báo này đã được gửi hoặc đang trong quá trình gửi.');
        }

        try {
            DB::beginTransaction();
            $notification->update(['status' => 'sending']); // Đặt trạng thái 'sending' trước khi gửi
            $this->dispatchNotificationToUsers($notification); // Gọi phương thức gửi
            DB::commit();
            return back()->with('success', 'Thông báo đang được gửi đi. Vui lòng kiểm tra lại sau ít phút.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error sending notification immediately: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            // Nếu có lỗi, cập nhật lại trạng thái thành failed
            $notification->update(['status' => 'failed']);
            return back()->with('error', 'Có lỗi xảy ra khi gửi thông báo: ' . $e->getMessage());
        }
    }

    /**
     * Logic lấy người nhận sẽ nằm trong một Command/Job sau.
     * @param Admin_notification $adminNotification
     */
    protected function dispatchNotificationToUsers(Admin_notification $adminNotification)
    {
        $usersToNotify = collect();

        $recipientIds = json_decode($adminNotification->recipient_ids, true) ?? [];

        if ($adminNotification->recipient_type === 'all') {
            $usersToNotify = User::all();
        } elseif ($adminNotification->recipient_type === 'specific_users' && !empty($recipientIds)) {
            $usersToNotify = User::whereIn('id', $recipientIds)->get();
        } elseif ($adminNotification->recipient_type === 'roles' && !empty($recipientIds)) {
            //dùng cột 'role' trong users:
            $usersToNotify = User::whereIn('role_id', $recipientIds)->get();
        }

        if ($usersToNotify->isEmpty()) {
            Log::warning("Không tìm thấy người dùng nào để gửi thông báo ID: {$adminNotification->id} (Recipient Type: {$adminNotification->recipient_type})");
            $adminNotification->update(['status' => 'failed', 'sent_at' => now()]);
            return;
        }

        foreach ($usersToNotify as $user) {
            $user->notify(new AdminPanelNotification([
                'id' => $adminNotification->id,
                'title' => $adminNotification->title,
                'content' => $adminNotification->content,
                'type' => $adminNotification->type,
            ]));
        }

        if ($adminNotification->status === 'sending') {
            $adminNotification->update(['status' => 'sent', 'sent_at' => now()]);
        }
    }




    





}
