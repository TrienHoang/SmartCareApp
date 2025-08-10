// resources/js/admin-chat.js
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

// Khởi tạo Echo
window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? "mt1",
    forceTLS: true,
});

// Lấy ID admin từ meta tag trong layout
const adminId = document.head.querySelector('meta[name="admin-id"]').content;

// Lắng nghe kênh chat realtime
window.Echo.private(`chat.admin.${adminId}`)
    .listen("MessageSent", (e) => {
        appendMessage(e.message);
    });

// Hàm append tin nhắn vào khung chat
function appendMessage(message) {
    const container = document.getElementById("chat-messages");
    if (!container) return;

    const msgHTML = `
        <div class="message ${message.sender_type === 'admin' ? 'outgoing' : 'incoming'}">
            <strong>${message.sender_name}:</strong> ${message.content}
        </div>
    `;
    container.insertAdjacentHTML("beforeend", msgHTML);
    container.scrollTop = container.scrollHeight;
}

// Xử lý gửi tin nhắn
document.getElementById("chat-form")?.addEventListener("submit", function (e) {
    e.preventDefault();
    const input = document.getElementById("chat-input");
    const content = input.value.trim();
    if (!content) return;

    axios.post("/admin/chat/send", { content })
        .then(() => {
            input.value = "";
        });
});
