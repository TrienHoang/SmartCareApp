import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? "mt1",
    forceTLS: true,
});

const sessionId = document.head.querySelector('meta[name="chat-session-id"]').content;
console.log("🔌 Reception joining channel: chat-session-" + sessionId);

const chatBoxEl = document.querySelector(".chat-box");
const form = document.getElementById("chat-form");
const input = document.getElementById("chat-input");

// ---- Hàm cuộn xuống cuối ----
function scrollToBottom() {
    if (!chatBoxEl) return;
    chatBoxEl.scrollTop = chatBoxEl.scrollHeight;
}

// ✅ Cuộn khi load trang
document.addEventListener("DOMContentLoaded", scrollToBottom);

// ---- Nhận tin nhắn realtime ----
window.Echo.private(`chat-session-${sessionId}`)
    .listen('.chat-message-sent', (e) => {
        console.log("📩 Reception received:", e);

        // 🚫 Chặn tin nhắn cảm ơn bị lặp
        if (e.sender_type === 'bot' && e.message.includes("Cảm ơn bạn đã liên hệ")) {
            const today = new Date().toISOString().split('T')[0];
            const lastThanksDate = localStorage.getItem('lastThanksDateReception');
            const receptionistReplied = localStorage.getItem('receptionistReplied') === 'true';

            if (receptionistReplied || lastThanksDate === today) return;
            localStorage.setItem('lastThanksDateReception', today);
        }

        // ✅ Đánh dấu lễ tân đã trả lời
        if (e.sender_type === 'receptionist') {
            localStorage.setItem('receptionistReplied', 'true');
        }

        appendMessage(e.message, e.sender_type);
        showNewMessageNotification();

        // ✅ Cuộn xuống cuối ngay sau khi render
        requestAnimationFrame(scrollToBottom);
    });

// ---- Gửi tin nhắn từ lễ tân ----
if (form && input && chatBoxEl) {
    form.addEventListener("submit", function (e) {
        e.preventDefault(); // 🚫 Chặn reload trang

        const content = input.value.trim();
        if (!content) return;

        axios.post(`/reception/chat/${sessionId}/send`, {
            message: content
        }).then(() => {
            appendMessage(content, 'receptionist');
            localStorage.setItem('receptionistReplied', 'true');
            input.value = "";

            // ✅ Cuộn xuống cuối ngay sau khi thêm tin nhắn
            requestAnimationFrame(scrollToBottom);
        });
    });
}

// ---- Hàm append tin nhắn ----
function appendMessage(message, sender) {
    if (!chatBoxEl) return;

    const isReceptionOrBot = ['receptionist', 'bot'].includes(sender);

    const messageDiv = document.createElement('div');
    messageDiv.className = `mb-3 d-flex ${isReceptionOrBot ? 'justify-content-end' : 'justify-content-start'}`;
    messageDiv.innerHTML = `
        <div class="${isReceptionOrBot ? 'bg-primary text-white' : 'bg-light'} p-3 rounded" style="max-width:70%;">
            ${escapeHtml(message)}
            <small class="text-muted d-block mt-1">
                ${sender === 'receptionist' ? 'Lễ tân' : sender === 'bot' ? 'Bot' : 'Khách hàng'} • vừa xong
            </small>
        </div>
    `;
    chatBoxEl.appendChild(messageDiv);
}

// ---- Hàm escape HTML ----
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// ---- Thông báo tin mới ----
function showNewMessageNotification() {
    const title = document.title;
    document.title = '🔔 Tin nhắn mới - ' + title;

    setTimeout(() => { document.title = title; }, 3000);

    if ('Notification' in window && Notification.permission === 'granted') {
        new Notification('Tin nhắn mới từ khách hàng', {
            body: 'Có tin nhắn mới trong chat',
            icon: '/favicon.ico'
        });
    }
}

// ✅ Yêu cầu quyền Notification khi load
if ('Notification' in window && Notification.permission === 'default') {
    Notification.requestPermission();
}
