// resources/js/client-notification-realtime.js
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? "mt1",
    forceTLS: true,
});

// Lấy user ID từ meta tag
const userId = document.head.querySelector('meta[name="user-id"]')?.content;

// DOM elements
const notificationBadge = document.getElementById('notification-badge');
const bellIcon = document.querySelector('.fa-bell');
const notificationList = document.getElementById('notification-list');

console.log("🔔 Client Notification - User ID:", userId);

function updateSidebarUnreadCount(notification) {
    const unreadBadge = document.getElementById('unreadCount');
    if (!unreadBadge) return;

    // Lấy count hiện tại
    let currentCount = parseInt(unreadBadge.textContent) || 0;
    currentCount += 1;

    // Cập nhật badge
    unreadBadge.textContent = currentCount;
    unreadBadge.classList.remove('hidden');

    // Animation pulse
    unreadBadge.classList.add('animate-pulse');
    setTimeout(() => {
        unreadBadge.classList.remove('animate-pulse');
    }, 2000);
}

// --- Thêm gọi vào Echo listener ---
if (userId) {
    window.Echo.private(`user-notifications.${userId}`)
        .listen('.NewAdminNotification', (e) => {
            console.log("📬 New notification received:", e);

            // Dropdown bell icon
            updateNotificationCountRealtime(e);
            // addNotificationToDropdown(e);
            animateBellIcon();
            showToastNotification(e);
            prependNotificationToList(e); // danh sách @foreach

            // Sidebar badge realtime
            updateSidebarUnreadCount(e);
        });
}

// Cập nhật count realtime (tăng lên 1)
function updateNotificationCountRealtime(notification) {
    if (!notificationBadge) return;

    // Lấy count hiện tại
    let currentCount = parseInt(notificationBadge.textContent) || 0;

    // Tăng lên 1
    currentCount += 1;

    // Cập nhật badge
    notificationBadge.textContent = currentCount;
    notificationBadge.classList.remove('hidden');

    // Animation pulse
    notificationBadge.classList.add('animate-pulse');
    setTimeout(() => {
        notificationBadge.classList.remove('animate-pulse');
    }, 2000);

    console.log("📊 Count updated:", currentCount);
}

// Thêm notification mới vào dropdown
// function addNotificationToDropdown(notification) {
//     if (!notificationList) return;

//     // Xóa "Không có thông báo nào" nếu có
//     const emptyMessage = notificationList.querySelector('.text-gray-500');
//     if (emptyMessage && emptyMessage.textContent.includes('Không có thông báo')) {
//         emptyMessage.remove();
//     }

//     // Tạo notification item mới
//     const newNotificationHTML = `
//         <div class="p-3 hover:bg-gray-100 text-sm bg-blue-50 border-l-4 border-blue-400">
//             <div class="font-medium text-gray-800">${notification.title}</div>
//             <div class="text-gray-600 text-xs mt-1 line-clamp-2">${notification.content}</div>
//             <div class="text-blue-600 text-xs mt-1 font-medium">
//                 Vừa xong • Mới db
//             </div>
//         </div>
//     `;

//     // Thêm vào đầu list
//     notificationList.insertAdjacentHTML('afterbegin', newNotificationHTML);

//     // Giới hạn chỉ hiển thị 5 notifications mới nhất
//     const allItems = notificationList.querySelectorAll('.p-3');
//     if (allItems.length > 5) {
//         allItems[allItems.length - 1].remove();
//     }
// }

// Animation icon chuông
function animateBellIcon() {
    if (!bellIcon) return;

    // Thêm animation swing
    bellIcon.classList.add('animate-bounce');
    bellIcon.style.color = '#3B82F6'; // Blue color

    // Thêm hiệu ứng glow
    bellIcon.style.filter = 'drop-shadow(0 0 6px #3B82F6)';

    // Reset sau 2 giây
    setTimeout(() => {
        bellIcon.classList.remove('animate-bounce');
        bellIcon.style.color = '#ffffffff'; // Gray-700
        bellIcon.style.filter = '';
    }, 2000);
}

// Toast notification
function showToastNotification(notification) {
    // Browser notification
    if ('Notification' in window && Notification.permission === 'granted') {
        new Notification(notification.title || 'Thông báo mới', {
            body: notification.content,
            icon: '/favicon.ico',
            tag: 'notification-' + notification.id
        });
    }

    // In-page toast
    showInPageToast(notification);
}

// Toast notification đơn giản và gọn gàng
function showInPageToast(notification) {
    // Tạo toast container nếu chưa có
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.style.cssText = `
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 12px;
            pointer-events: none;
        `;
        document.body.appendChild(toastContainer);
    }

    const toast = document.createElement('div');
    toast.style.cssText = `
        width: 300px;
        background: #1e40af;
        color: white;
        border-radius: 8px;
        padding: 16px;
        box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
        transform: translateX(100%);
        opacity: 0;
        transition: all 0.3s ease;
        pointer-events: auto;
        position: relative;
    `;

    // Truncate content
    const truncatedContent = notification.content.length > 70
        ? notification.content.substring(0, 70) + '...'
        : notification.content;

    toast.innerHTML = `
    <button onclick="closeToast(this)" style="
        position: absolute;
        top: 8px;
        right: 8px;
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 20px;
        height: 20px;
        border-radius: 4px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        line-height: 1;
    ">×</button>
    
    <div style="display: flex; align-items: flex-start; gap: 10px; padding-right: 24px;">
        <div style="
            width: 24px;
            height: 24px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 2px;
        ">
            <i class="fas fa-bell" style="font-size: 12px; color: white;"></i>
        </div>
        
        <div style="flex: 1; min-width: 0;">
            <h4 style="
                font-size: 14px;
                font-weight: 600;
                margin: 0 0 4px 0;
                line-height: 1.3;
                color: white;
            ">${notification.title || 'Thông báo'}</h4>
            
            <p style="
                font-size: 13px;
                margin: 0;
                line-height: 1.4;
                color: rgba(255, 255, 255, 0.9);
                word-wrap: break-word;
            ">${truncatedContent}</p>
            
            <span style="
                display: block;
                margin-top: 8px;
                font-size: 11px;
                font-weight: 500;
                color: #93c5fd;
            ">⏱ ${formatTimeAgo(notification.time || new Date().toISOString())}</span>
        </div>
    </div>
`;


    toastContainer.appendChild(toast);

    // Simple slide in
    requestAnimationFrame(() => {
        toast.style.transform = 'translateX(0)';
        toast.style.opacity = '1';
    });

    // Auto remove sau 4 giây
    setTimeout(() => {
        removeToast(toast);
    }, 4000);
}

// Helper function để đóng toast
window.closeToast = function (button) {
    const toast = button.closest('div[style*="background: #1e40af"]');
    removeToast(toast);
}

// Remove toast đơn giản
function removeToast(toast) {
    if (!toast || !toast.parentNode) return;

    toast.style.transform = 'translateX(100%)';
    toast.style.opacity = '0';

    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 300);
}


// Format time ago
function formatTimeAgo(timeString) {
    const now = new Date();
    const time = new Date(timeString);
    const diffInSeconds = Math.floor((now - time) / 1000);

    if (diffInSeconds < 60) return 'Vừa xong';
    if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} phút trước`;
    if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} giờ trước`;
    return `${Math.floor(diffInSeconds / 86400)} ngày trước`;
}

// Request notification permission khi trang load
if ('Notification' in window && Notification.permission === 'default') {
    Notification.requestPermission();
}

// CSS Animation cho swing effect
const minimalistStyle = document.createElement('style');
minimalistStyle.textContent = `
    @keyframes swing {
        0%, 100% { transform: rotate(0deg); }
        20% { transform: rotate(15deg); }
        40% { transform: rotate(-10deg); }
        60% { transform: rotate(5deg); }
        80% { transform: rotate(-5deg); }
    }
    
    .animate-swing {
        animation: swing 0.8s ease-in-out;
    }
    
    /* Mobile responsive */
    @media (max-width: 640px) {
        #toast-container {
            top: 16px !important;
            right: 16px !important;
            left: 16px !important;
        }
        
        #toast-container > div {
            width: 100% !important;
        }
    }
`;

// Remove tất cả old styles
const oldStyles = document.querySelectorAll('style');
oldStyles.forEach(style => {
    if (style.textContent.includes('toast') && style !== minimalistStyle) {
        style.remove();
    }
});

document.head.appendChild(minimalistStyle);

// Log để debug
console.log("🔔 Client notification realtime loaded");
console.log("🔔 Notification badge element:", notificationBadge);
console.log("🔔 Bell icon element:", bellIcon);