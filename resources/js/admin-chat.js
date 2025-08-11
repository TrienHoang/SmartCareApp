// resources/js/admin-chat.js
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? "mt1",
    forceTLS: true,
});

// Lấy sessionId (VD: từ URL hoặc biến server truyền vào)
const sessionId = document.head.querySelector('meta[name="chat-session-id"]').content;

console.log("🔌 Admin joining channel: chat-session-" + sessionId);

window.Echo.private(`chat-session-${sessionId}`)
    .listen('.chat-message-sent', (e) => {
        appendMessage(e.message, 'client');
    });

function appendMessage(message, sender) {
    const container = document.getElementById("chat-messages");
    if (!container) return;

    const msgHTML = `
        <div class="message ${sender === 'admin' ? 'outgoing' : 'incoming'}">
            <strong>${sender}:</strong> ${message}
        </div>
    `;
    container.insertAdjacentHTML("beforeend", msgHTML);
    container.scrollTop = container.scrollHeight;
}

// Gửi tin nhắn từ admin
document.getElementById("chat-form")?.addEventListener("submit", function (e) {
    e.preventDefault();
    const input = document.getElementById("chat-input");
    const content = input.value.trim();
    if (!content) return;

    axios.post(`/admin/chat/${sessionId}/send`, {
        message: content
    }).then(() => {
        appendMessage(content, 'admin');
        input.value = "";
    });
});
