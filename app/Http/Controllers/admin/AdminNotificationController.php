<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin_notification; //
use App\Models\User; // Import Model User để lấy danh sách người nhận
use App\Notifications\AdminPanelNotification; // Import Laravel Notification class
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Dùng để ghi log lỗi
// use Spatie\Permission\Models\Role; // Nếu bạn dùng Spatie Laravel Permission

class AdminNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     * Hiển thị danh sách các thông báo.
     */
    public function index(Request $request)
    {
        $query = Admin_notification::orderBy('created_at', 'desc');

        // Lọc và tìm kiếm
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhere('content', 'like', '%' . $search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        $notifications = $query->paginate(10); // Phân trang 10 thông báo mỗi trang

        // Định nghĩa các loại và trạng thái để hiển thị trong bộ lọc
        $notificationTypes = ['system', 'appointment_related', 'promotion', 'reminder', 'other'];
        $notificationStatuses = ['draft', 'scheduled', 'sending', 'sent', 'failed'];

        return view('admin.notifications.index', compact('notifications', 'notificationTypes', 'notificationStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     * Hiển thị form tạo thông báo mới.
     */
    public function create()
    {
        $notificationTypes = ['system', 'appointment_related', 'promotion', 'reminder', 'other'];
        // Tùy chọn: Truyền danh sách users và roles nếu không dùng AJAX cho Select2
        // $users = User::all();
        // $roles = Role::all(); // Nếu dùng Spatie
        // $roles = ['admin', 'doctor', 'patient']; // Nếu tự quản lý role

        return view('admin.notifications.create', compact('notificationTypes')); // Có thể thêm 'users', 'roles'
    }

    /**
     * Store a newly created resource in storage.
     * Lưu thông báo mới vào database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string|in:system,appointment_related,promotion,reminder,other',
            'recipient_type' => 'required|string|in:all,specific_users,roles', // Giới hạn các loại người nhận
            'recipient_ids' => 'nullable|array', // Mảng các ID người dùng hoặc tên vai trò
            'scheduled_at' => 'nullable|date',
            'send_now_checkbox' => 'nullable|boolean', // Checkbox gửi ngay
        ]);

        try {
            DB::beginTransaction(); // Bắt đầu transaction

            $status = 'draft';
            $sentAt = null;

            if ($request->filled('scheduled_at')) {
                $status = 'scheduled';
            } elseif ($request->boolean('send_now_checkbox')) { // Nếu chọn gửi ngay
                $status = 'sending'; // Đặt trạng thái là 'sending' trước khi đẩy vào queue
            }

            $adminNotification = Admin_notification::create([
                'title' => $request->title,
                'content' => $request->content,
                'type' => $request->type,
                'sender_id' => auth()->id(), // Giả sử admin là User đang đăng nhập
                'recipient_type' => $request->recipient_type,
                // Lưu recipient_ids vào recipient_data dưới dạng mảng
                'recipient_data' => $request->recipient_ids,
                'scheduled_at' => $request->scheduled_at,
                'status' => $status,
            ]);

            // Nếu thông báo cần được gửi ngay lập tức
            if ($status === 'sending') {
                $this->dispatchNotificationToUsers($adminNotification);
                // Sau khi dispatch, trạng thái sẽ được update thành 'sent' hoặc 'failed' bởi Queue
                // Trong trường hợp này, chúng ta không set sent_at ở đây mà đợi Queue xử lý
            }

            DB::commit(); // Hoàn tất transaction

            return redirect()->route('admin.notifications.index')->with('success', 'Thông báo đã được tạo thành công!');

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback nếu có lỗi
            Log::error('Error creating notification: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withInput()->with('error', 'Có lỗi xảy ra khi tạo thông báo: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     * Hiển thị chi tiết thông báo.
     */
    public function show(Admin_notification $notification)
    {
        return view('admin.notifications.show', compact('notification'));
    }

    /**
     * Show the form for editing the specified resource.
     * Hiển thị form chỉnh sửa thông báo.
     */
    public function edit(Admin_notification $notification)
    {
        // Chỉ cho phép chỉnh sửa nếu trạng thái là 'draft' hoặc 'scheduled'
        if ($notification->status == 'sent' || $notification->status == 'sending') {
            return redirect()->route('admin.notifications.index')->with('error', 'Không thể chỉnh sửa thông báo đã được gửi hoặc đang gửi.');
        }

        $notificationTypes = ['system', 'appointment_related', 'promotion', 'reminder', 'other'];
        // Tùy chọn: Truyền danh sách users và roles nếu không dùng AJAX cho Select2
        // $users = User::all();
        // $roles = Role::all(); // Nếu dùng Spatie

        return view('admin.notifications.edit', compact('notification', 'notificationTypes')); // Có thể thêm 'users', 'roles'
    }

    /**
     * Update the specified resource in storage.
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
            'scheduled_at' => 'nullable|date',
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
                'recipient_data' => $request->recipient_ids, // Lưu recipient_ids vào recipient_data
                'scheduled_at' => $request->scheduled_at,
                'status' => $status,
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
     * Remove the specified resource from storage.
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
     * Gửi thông báo ngay lập tức đến người dùng.
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
     * Phương thức chung để dispatch (đẩy) thông báo đến hàng đợi.
     * Logic lấy người nhận sẽ nằm trong một Command/Job sau.
     *
     * @param Admin_notification $adminNotification
     */
    protected function dispatchNotificationToUsers(Admin_notification $adminNotification)
    {
        // Ở đây chúng ta chỉ đánh dấu thông báo là 'sending' và sẽ xử lý việc lấy người nhận
        // và gửi Notification Laravel trong một Artisan Command (cho scheduled)
        // hoặc ngay lập tức trong Job nếu là sendNow.
        // Để đơn giản, tôi sẽ đưa logic này trực tiếp vào đây cho sendNow,
        // nhưng lý tưởng nên dùng Job riêng để tách biệt logic.

        $usersToNotify = collect();

        if ($adminNotification->recipient_type === 'all') {
            $usersToNotify = User::all();
        } elseif ($adminNotification->recipient_type === 'specific_users' && is_array($adminNotification->recipient_data)) {
            $usersToNotify = User::whereIn('id', $adminNotification->recipient_data)->get();
        } elseif ($adminNotification->recipient_type === 'roles' && is_array($adminNotification->recipient_data)) {
            // Giả sử bạn đang dùng Spatie Laravel Permission
            // $usersToNotify = User::role($adminNotification->recipient_data)->get();

            // Hoặc nếu bạn có cột 'role' trong bảng users:
            $usersToNotify = User::whereIn('role', $adminNotification->recipient_data)->get();
        }
        // Thêm logic cho 'by_condition' nếu bạn triển khai nó

        if ($usersToNotify->isEmpty()) {
            Log::warning("Không tìm thấy người dùng nào để gửi thông báo ID: {$adminNotification->id} (Recipient Type: {$adminNotification->recipient_type})");
            // Cập nhật trạng thái failed nếu không có người nhận
            $adminNotification->update(['status' => 'failed', 'sent_at' => now()]);
            return;
        }

        foreach ($usersToNotify as $user) {
            // Gửi Laravel Notification
            $user->notify(new AdminPanelNotification([
                'id' => $adminNotification->id,
                'title' => $adminNotification->title,
                'content' => $adminNotification->content,
                'type' => $adminNotification->type,
            ]));
        }

        // Sau khi đã dispatch tất cả, cập nhật trạng thái của adminNotification
        // Lưu ý: Việc này có thể được xử lý tốt hơn trong một Job sau khi hoàn thành
        // Tuy nhiên, để đơn giản cho ví dụ này, chúng ta sẽ cập nhật ở đây.
        // Đối với scheduled notifications, việc cập nhật status sẽ nằm trong Artisan Command.
        if ($adminNotification->status === 'sending') { // Chỉ cập nhật nếu nó đang được gửi ngay
             $adminNotification->update(['status' => 'sent', 'sent_at' => now()]);
        }
    }
}
