// resources/js/client-notification-list.js
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
  broadcaster: "pusher",
  key: import.meta.env.VITE_PUSHER_APP_KEY,
  cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? "mt1",
  forceTLS: true,
});

document.addEventListener('DOMContentLoaded', function() {
    // Lấy user ID từ meta tag
    const userId = document.head.querySelector('meta[name="user-id"]')?.content;
    const notificationList = document.getElementById('notification-list');
    
    if (!userId || !notificationList) {
        console.warn('❌ Không tìm thấy userId hoặc notification-list element');
        return;
    }

    console.log('🔥 Khởi tạo realtime cho user:', userId);

    // Listen cho thông báo mới
    window.Echo.private(`user-notifications.${userId}`)
        .listen('.NewAdminNotification', (e) => {
            console.log("📬 Nhận notification mới:", e);
            addNewNotificationToList(e);
        });

    // Function thêm notification mới vào danh sách
    function addNewNotificationToList(notification) {
        // Kiểm tra xem notification đã tồn tại chưa
        const existingNotif = document.querySelector(`#notification-${notification.id}`);
        if (existingNotif) {
            console.log('⚠️ Notification đã tồn tại, bỏ qua');
            return;
        }

        // Tạo element notification mới
        const notifEl = document.createElement('div');
        notifEl.id = `notification-${notification.id}`;
        notifEl.dataset.notificationId = notification.id;
        notifEl.className = "bg-white p-5 rounded-lg shadow-md mb-4 flex justify-between items-start border-l-4 border-blue-500 hover:shadow-lg hover:bg-gray-50 cursor-pointer transition-all duration-200 relative";
        
        // Format thời gian
        const sentAt = new Date(notification.sent_at || notification.time);
        const formattedTime = sentAt.toLocaleDateString('vi-VN', {
            year: 'numeric',
            month: '2-digit', 
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        });

        // Tạo nội dung HTML
        notifEl.innerHTML = `
            <div class="flex-grow pr-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-1 leading-tight">${notification.title}</h3>
                <p class="text-sm text-gray-600 mb-2">${limitText(notification.content, 180)}</p>
                <span class="text-xs text-gray-500 flex items-center">
                    <i class="fas fa-clock mr-2"></i>Gửi lúc: ${formattedTime}
                </span>
            </div>
            <span class="unread-indicator absolute top-2 right-2 w-3 h-3 bg-red-500 rounded-full animate-pulse"></span>
        `;

        // Thêm event click để mở modal
        notifEl.addEventListener('click', function() {
            // Gọi Alpine.js component method
            const alpineComponent = document.querySelector('[x-data*="notificationModal"]').__x;
            if (alpineComponent && alpineComponent.$data) {
                alpineComponent.$data.openModal(notification.id);
            }
        });

        // Kiểm tra nếu danh sách trống (có empty state)
        const emptyState = notificationList.querySelector('.bg-white.p-8.rounded-lg.shadow-xl');
        if (emptyState) {
            // Xóa empty state và thay thế bằng danh sách
            emptyState.remove();
        }

        // Thêm vào đầu danh sách với animation
        notifEl.style.opacity = '0';
        notifEl.style.transform = 'translateY(-20px)';
        notificationList.prepend(notifEl);
        
        // Animate in
        setTimeout(() => {
            notifEl.style.transition = 'all 0.3s ease';
            notifEl.style.opacity = '1';
            notifEl.style.transform = 'translateY(0)';
        }, 10);

        // Cập nhật Alpine store (nếu có)
        if (window.Alpine && Alpine.store('notification')) {
            Alpine.store('notification').unreadCount++;
            Alpine.store('notification').notificationCount++;
        }

        // Giới hạn số lượng hiển thị (tùy chọn)
        limitNotificationDisplay();
        
        console.log('✅ Đã thêm notification mới vào danh sách');
    }

    // Function giới hạn text
    function limitText(text, limit) {
        if (!text) return '';
        const stripped = text.replace(/<[^>]*>/g, ''); // Remove HTML tags
        return stripped.length > limit ? stripped.substring(0, limit) + '...' : stripped;
    }

    // Function giới hạn số lượng notification hiển thị
    function limitNotificationDisplay(maxCount = 20) {
        const allNotifications = notificationList.querySelectorAll('div[id^="notification-"]');
        if (allNotifications.length > maxCount) {
            // Xóa những notification cũ nhất
            for (let i = maxCount; i < allNotifications.length; i++) {
                allNotifications[i].remove();
            }
        }
    }

    // Listen cho event đánh dấu đã đọc từ component khác
    window.addEventListener('notificationRead', function(event) {
        const notificationId = event.detail.notificationId;
        updateNotificationReadStatus(notificationId);
    });

    // Function cập nhật trạng thái đã đọc
    function updateNotificationReadStatus(notificationId) {
        const notifEl = document.querySelector(`#notification-${notificationId}`);
        if (notifEl) {
            // Thay đổi style thành đã đọc
            notifEl.classList.remove('border-blue-500');
            notifEl.classList.add('border-gray-300');
            
            // Xóa chấm đỏ
            const unreadIndicator = notifEl.querySelector('.unread-indicator');
            if (unreadIndicator) {
                unreadIndicator.remove();
            }
        }
    }

    console.log('🚀 Client notification realtime đã khởi tạo');
});