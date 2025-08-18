import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// ✅ Đúng thư viện, đúng cú pháp
import { createIcons, icons } from 'lucide';
createIcons({ icons });

const sessionId = localStorage.getItem('chat_session_id');
// console.log("🔌 Joining channel: chat-session-" + sessionId);
if (sessionId) {
    window.Echo.private(`chat-session-${sessionId}`)
        .listen('.chat-message-sent', (e) => {
            // console.log('📩 Tin nhắn mới:', e.message);
            // console.log('📩 Toàn bộ e:', e);
            // console.log('📩 e.message:', e.message);

//             const newMessageHtml = `
//     <div class="flex items-start gap-2 my-2">
//         <!-- Avatar -->
//         <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
//             <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
//                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A4 4 0 017 17h10a4 4 0 011.879.804M15 11a4 4 0 10-6 0m6 0v4m-6-4v4" />
//             </svg>
//         </div>
//         <!-- Nội dung -->
//         <div>
//             <div class="bg-green-100 p-3 rounded-2xl rounded-tl-none">
//                 <p class="text-gray-800 text-sm">${e.message}</p>
//             </div>
//             <p class="text-green-600 text-xs mt-1">Nhân viên hỗ trợ</p>
//         </div>
//     </div>
// `;

//             $('#chatbox-content').append(newMessageHtml);

//             // Scroll xuống cuối
//             $('#chatbox-content').scrollTop($('#chatbox-content')[0].scrollHeight);
        });
}