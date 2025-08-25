import Echo from 'laravel-echo';
import Pusher from "pusher-js";

window.Pusher = Pusher;

export function initNotifications() {
    const userMeta = document.querySelector('meta[name="user-id"]');
    if (!userMeta) return;

    const userId = userMeta.getAttribute('content');

    // Khởi tạo Echo nếu chưa có
    if (!window.Echo) {
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: import.meta.env.VITE_PUSHER_APP_KEY,
            cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
            forceTLS: true,
        });
    }

    const container = document.getElementById('notification-list');
    if (!container) return;

    // 🔔 Lắng nghe kênh realtime
   window.Echo.private(`user-notifications.${userId}`)
    .listen('.NewAdminNotification', (e) => {
        console.log("📩 New notification:", e);

        // 1. Chèn item thông báo mới
        if (e.html) {
            container.insertAdjacentHTML('afterbegin', e.html);
        }

        // 2. Cập nhật badge realtime
        const badge = document.getElementById('notification-badge');
        if (badge) {
            let count = parseInt(badge.innerText) || 0;
            badge.innerText = count + 1;
            badge.classList.remove('hidden');
        }
    });

}
