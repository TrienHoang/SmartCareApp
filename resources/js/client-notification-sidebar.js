import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? "mt1",
    forceTLS: true,
});

// Lấy user ID từ meta
const userId = document.head.querySelector('meta[name="user-id"]')?.content;

if (userId && window.Echo) {
    window.Echo.private(`user-notifications.${userId}`)
        .listen('.NewAdminNotification', (notification) => {
            console.log("📬 Realtime notification:", notification);

            const unreadBadge = document.querySelector('#unreadCount');
            if (!unreadBadge) return; // luôn có trong DOM, chỉ hidden thôi

            let currentCount = parseInt(unreadBadge.textContent || 0);
            currentCount += 1;

            unreadBadge.textContent = currentCount;
            unreadBadge.classList.remove('hidden');
        });
}
