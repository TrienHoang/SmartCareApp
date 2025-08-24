import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? "mt1",
    forceTLS: true,
});

document.addEventListener('DOMContentLoaded', function () {
    // 🎯 Configuration
    const CONFIG = {
        sessionId: document.head.querySelector('meta[name="chat-session-id"]').content,
        notificationsEnabled: localStorage.getItem('reception-notifications') !== 'false',
        soundEnabled: localStorage.getItem('reception-sound') !== 'false'
    };

    // 🎯 Elements
    const elements = {
        chatBox: document.querySelector('.chat-box'),
        messageInput: document.querySelector('textarea[name="message"]'),
        messageForm: document.querySelector('form'),
        notificationContainer: document.getElementById('notification-container'),
        statusIndicator: document.getElementById('status-indicator'),
        notificationToggle: document.getElementById('notification-toggle'),
        typingIndicator: document.getElementById('typing-indicator'),
        messageCount: document.getElementById('message-count'),
        notificationSound: document.getElementById('notification-sound')
    };

    // 🎯 Initialize
    init();

    function init() {
        console.log(`🎧 Reception listening to channel: chat-session-${CONFIG.sessionId}`);

        // Auto scroll to bottom
        if (elements.chatBox) {
            elements.chatBox.scrollTop = elements.chatBox.scrollHeight;
        }

        // Setup Echo connection
        setupEchoConnection();

        // Setup form handlers
        setupFormHandlers();

        // Setup UI handlers
        setupUIHandlers();

        // Update notification toggle state
        updateNotificationToggle();

        // Request notification permission
        requestNotificationPermission();
    }

    function setupEchoConnection() {
        if (!window.Echo?.connector) {
            console.error('❌ Echo not available for reception panel');
            updateConnectionStatus('error', 'Lỗi kết nối');
            return;
        }

        console.log('✅ Echo available, Pusher state:', window.Echo.connector.pusher.connection.state);

        const setupListener = () => {
            window.Echo.channel('chat-global')
                .listen('.chat-message-sent', handleNewMessage)
                .listen('.user-typing', handleUserTyping)
                .listen('.user-stopped-typing', handleUserStoppedTyping)
                .subscribed(() => {
                    console.log('✅ Reception successfully subscribed to channel');
                    updateConnectionStatus('connected', 'Đã kết nối');
                })
                .error((error) => {
                    console.error('❌ Reception channel subscription error:', error);
                    updateConnectionStatus('error', 'Lỗi kết nối');
                });
        };

        // Setup connection event handlers
        window.Echo.connector.pusher.connection.bind('connected', () => {
            console.log('🔗 Pusher connected');
            updateConnectionStatus('connected', 'Đã kết nối');
            setupListener();
        });

        window.Echo.connector.pusher.connection.bind('disconnected', () => {
            console.log('🔌 Pusher disconnected');
            updateConnectionStatus('disconnected', 'Mất kết nối');
        });

        window.Echo.connector.pusher.connection.bind('reconnecting', () => {
            console.log('🔄 Pusher reconnecting');
            updateConnectionStatus('reconnecting', 'Đang kết nối lại...');
        });

        if (window.Echo.connector.pusher.connection.state === 'connected') {
            setupListener();
        } else {
            updateConnectionStatus('connecting', 'Đang kết nối...');
        }
    }

    function handleNewMessage(e) {
        console.log('📩 Reception received message:', e);

        // Nếu không phải session đang mở → chỉ hiện thông báo
        if (e.session_id && e.session_id !== CONFIG.sessionId) {
            if (!['admin', 'receptionist'].includes(e.sender_type)) {
                showNotification(e);
            }
            return; // không đổ vào chatbox
        }

        // Skip bot thank you messages if already handled
        if (shouldSkipBotMessage(e)) return;

        // Update receptionist replied status
        if (e.sender_type === 'receptionist') {
            localStorage.setItem('receptionistReplied', 'true');
        }

        // Add message to chat
        addMessageToChat(e);

        // Show notification only for non-reception messages
        if (!['admin', 'receptionist'].includes(e.sender_type)) {
            showNotification(e);
        }

        // Update statistics
        updateChatStatistics(e);
    }

    function handleUserTyping(e) {
        console.log('✏️ User is typing:', e);
        showTypingIndicator(e.user_name || 'Khách hàng');
    }

    function handleUserStoppedTyping(e) {
        console.log('✏️ User stopped typing');
        hideTypingIndicator();
    }

    function shouldSkipBotMessage(e) {
        if (e.sender_type === 'bot' && e.message.includes("Cảm ơn bạn đã liên hệ")) {
            const today = new Date().toISOString().split('T')[0];
            const lastThanksDate = localStorage.getItem('lastThanksDateReception');
            const receptionistReplied = localStorage.getItem('receptionistReplied') === 'true';

            if (receptionistReplied || lastThanksDate === today) return true;

            localStorage.setItem('lastThanksDateReception', today);
        }
        return false;
    }

    function addMessageToChat(e) {
        if (!elements.chatBox) return;

        const isReceptionOrBot = ['admin', 'receptionist', 'bot'].includes(e.sender_type);
        const messageDiv = document.createElement('div');

        messageDiv.className = `message mb-3 d-flex ${isReceptionOrBot ? 'justify-content-end' : 'justify-content-start'}`;
        messageDiv.setAttribute('data-message-id', e.id || Date.now());
        messageDiv.style.opacity = '0';
        messageDiv.style.transform = 'translateY(20px)';

        const senderInfo = getSenderInfo(e.sender_type);
        const bubbleClass = isReceptionOrBot ? 'bg-primary text-white' : 'bg-light';
        const borderRadius = isReceptionOrBot ? '20px 20px 5px 20px' : '20px 20px 20px 5px';
        const textAlign = isReceptionOrBot ? 'text-right' : 'text-left';

        messageDiv.innerHTML = `
            <div class="message-content" style="max-width: 70%;">
                <div class="message-bubble p-3 rounded ${bubbleClass}" 
                     style="border-radius: ${borderRadius};">
                    ${escapeHtml(e.message).replace(/\n/g, '<br>')}
                </div>
                <small class="text-muted d-block mt-1 ${textAlign}">
                    <i class="${senderInfo.icon} mr-1"></i>${senderInfo.name} • vừa xong
                </small>
            </div>
        `;

        elements.chatBox.appendChild(messageDiv);

        // Animate message appearance
        setTimeout(() => {
            messageDiv.style.transition = 'all 0.3s ease';
            messageDiv.style.opacity = '1';
            messageDiv.style.transform = 'translateY(0)';
        }, 10);

        // Auto scroll to bottom
        elements.chatBox.scrollTop = elements.chatBox.scrollHeight;

        // Update message count
        updateMessageCount();
    }

    function getSenderInfo(senderType) {
        const senderMap = {
            admin: { icon: 'fas fa-user-shield', name: 'Admin' },
            bot: { icon: 'fas fa-robot', name: 'Bot' },
            receptionist: { icon: 'fas fa-concierge-bell', name: 'Lễ tân' },
            user: { icon: 'fas fa-user', name: 'Khách hàng' }
        };
        return senderMap[senderType] || senderMap.user;
    }

    function showNotification(e) {
        if (!CONFIG.notificationsEnabled) return;

        // Browser notification
        showBrowserNotification(e);

        // Toast notification
        showToastNotification(e);

        // Sound notification
        playNotificationSound();

        // Title notification
        showTitleNotification();

        // Visual indicator
        addPulseEffect();
    }

    function showBrowserNotification(e) {
        if ('Notification' in window && Notification.permission === 'granted') {
            const senderName = getSenderInfo(e.sender_type).name;
            const notification = new Notification(`💬 Tin nhắn mới từ ${senderName}`, {
                body: e.message.substring(0, 100) + (e.message.length > 100 ? '...' : ''),
                icon: '/favicon.ico',
                badge: '/favicon.ico',
                tag: 'chat-message',
                requireInteraction: true
            });

            notification.onclick = function () {
                window.focus();
                notification.close();
            };

            setTimeout(() => notification.close(), 5000);
        }
    }

    function showToastNotification(e) {
        if (!elements.notificationContainer) return;

        const toast = document.createElement('div');
        toast.className = 'notification-toast alert alert-info alert-dismissible mb-2';
        toast.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-comment-dots mr-2"></i>
                <div class="flex-grow-1">
                    <strong>${getSenderInfo(e.sender_type).name}</strong>
                    <div class="small">${e.message.substring(0, 50)}${e.message.length > 50 ? '...' : ''}</div>
                </div>
                <button type="button" class="close ml-2" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        `;

        elements.notificationContainer.appendChild(toast);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.classList.add('notification-exit');
                setTimeout(() => toast.remove(), 300);
            }
        }, 5000);
    }

    function playNotificationSound() {
        if (CONFIG.soundEnabled && elements.notificationSound) {
            elements.notificationSound.currentTime = 0;
            elements.notificationSound.play().catch(e => console.log('Sound play failed:', e));
        }
    }

    function showTitleNotification() {
        const originalTitle = document.title;
        let blinking = true;

        const blinkInterval = setInterval(() => {
            document.title = blinking ? '🔔 TIN NHẮN MỚI!' : originalTitle;
            blinking = !blinking;
        }, 1000);

        // Stop blinking when user focuses window
        const stopBlinking = () => {
            clearInterval(blinkInterval);
            document.title = originalTitle;
            window.removeEventListener('focus', stopBlinking);
        };

        window.addEventListener('focus', stopBlinking);

        // Auto stop after 10 seconds
        setTimeout(stopBlinking, 10000);
    }

    function addPulseEffect() {
        if (elements.chatBox) {
            elements.chatBox.classList.add('pulse');
            setTimeout(() => elements.chatBox.classList.remove('pulse'), 2000);
        }
    }

    function showTypingIndicator(userName) {
        if (elements.typingIndicator) {
            document.getElementById('typing-user').textContent = userName;
            elements.typingIndicator.style.display = 'block';
            elements.typingIndicator.classList.add('typing-animation');
        }
    }

    function hideTypingIndicator() {
        if (elements.typingIndicator) {
            elements.typingIndicator.style.display = 'none';
            elements.typingIndicator.classList.remove('typing-animation');
        }
    }

    function updateConnectionStatus(status, text) {
        if (!elements.statusIndicator) return;

        const statusClasses = {
            connected: 'badge-success',
            connecting: 'badge-warning',
            reconnecting: 'badge-warning',
            disconnected: 'badge-danger',
            error: 'badge-danger'
        };

        const statusIcons = {
            connected: 'fas fa-circle',
            connecting: 'fas fa-circle-notch fa-spin',
            reconnecting: 'fas fa-sync fa-spin',
            disconnected: 'fas fa-exclamation-triangle',
            error: 'fas fa-times-circle'
        };

        elements.statusIndicator.className = `badge ${statusClasses[status] || 'badge-secondary'}`;
        elements.statusIndicator.innerHTML = `<i class="${statusIcons[status] || 'fas fa-circle'} mr-1"></i>${text}`;
    }

    function updateChatStatistics(e) {
        // Update message counts
        if (elements.messageCount) {
            const messageCount = parseInt(elements.messageCount.textContent) + 1;
            elements.messageCount.textContent = messageCount;
        }

        // Update sender-specific counts
        const senderCountElements = {
            admin: document.getElementById('admin-messages'),
            receptionist: document.getElementById('receptionist-messages'),
            user: document.getElementById('user-messages'),
            bot: document.getElementById('bot-messages')
        };

        const element = senderCountElements[e.sender_type];
        if (element) {
            const count = parseInt(element.textContent) + 1;
            element.textContent = count;

            // Add pulse effect to updated counter
            element.parentElement.classList.add('pulse');
            setTimeout(() => element.parentElement.classList.remove('pulse'), 1000);
        }
    }

    function updateMessageCount() {
        if (elements.messageCount) {
            const messageCount = parseInt(elements.messageCount.textContent) + 1;
            elements.messageCount.textContent = messageCount;
        }
    }

    function setupFormHandlers() {
        // Message form submission
        if (elements.messageForm) {
            elements.messageForm.addEventListener('submit', function (e) {
                e.preventDefault();

                const message = elements.messageInput.value.trim();
                if (!message) return;

                // Disable send button temporarily
                const sendBtn = this.querySelector('button[type="submit"]');
                const originalText = sendBtn.innerHTML;
                sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                sendBtn.disabled = true;

                // Submit form with better error handling
                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new URLSearchParams(new FormData(this))
                })
                    .then(response => {
                        console.log('Response status:', response.status);

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        // Check if response is JSON
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            return response.json();
                        } else {
                            // If not JSON, assume success (Laravel might return redirect)
                            console.log('Non-JSON response received, assuming success');
                            return { success: true };
                        }
                    })
                    .then(data => {
                        console.log('Response data:', data);

                        // Clear input on success
                        elements.messageInput.value = '';
                        autoResizeTextarea();

                        // Mark that receptionist has replied
                        localStorage.setItem('receptionistReplied', 'true');

                        // Show success toast
                        showToastMessage('Tin nhắn đã được gửi', 'success');

                        // If there's an error in JSON response
                        if (data.success === false) {
                            console.error('Message send failed:', data.error || data.message);
                            showErrorNotification(data.error || data.message || 'Không thể gửi tin nhắn. Vui lòng thử lại.');
                        }
                    })
                    .catch(error => {
                        console.error('Message send error:', error);

                        // More specific error messages
                        if (error.name === 'TypeError' && error.message.includes('Failed to fetch')) {
                            showErrorNotification('Lỗi kết nối mạng. Vui lòng kiểm tra internet.');
                        } else if (error.message.includes('HTTP error')) {
                            showErrorNotification('Lỗi server. Vui lòng thử lại sau.');
                        } else {
                            showErrorNotification('Có lỗi xảy ra. Vui lòng thử lại.');
                        }
                    })
                    .finally(() => {
                        sendBtn.innerHTML = originalText;
                        sendBtn.disabled = false;
                        elements.messageInput.focus();
                    });
            });
        }

        // Auto-resize textarea
        if (elements.messageInput) {
            elements.messageInput.addEventListener('input', autoResizeTextarea);
            elements.messageInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    elements.messageForm.dispatchEvent(new Event('submit'));
                }
            });
        }

        // Quick reply buttons
        document.querySelectorAll('.quick-reply').forEach(button => {
            button.addEventListener('click', function () {
                const message = this.getAttribute('data-message');
                elements.messageInput.value = message;
                elements.messageInput.focus();
                autoResizeTextarea();
            });
        });

        // Emoji picker
        const emojiBtn = document.getElementById('emoji-btn');
        if (emojiBtn) {
            emojiBtn.addEventListener('click', function () {
                $('#emojiModal').modal('show');
            });
        }

        document.querySelectorAll('.emoji-item').forEach(emoji => {
            emoji.addEventListener('click', function () {
                elements.messageInput.value += this.textContent;
                $('#emojiModal').modal('hide');
                elements.messageInput.focus();
                autoResizeTextarea();
            });
        });
    }

    function setupUIHandlers() {
        // Notification toggle
        if (elements.notificationToggle) {
            elements.notificationToggle.addEventListener('click', function () {
                CONFIG.notificationsEnabled = !CONFIG.notificationsEnabled;
                localStorage.setItem('reception-notifications', CONFIG.notificationsEnabled);
                updateNotificationToggle();

                showToastMessage(
                    CONFIG.notificationsEnabled ? 'Đã bật thông báo' : 'Đã tắt thông báo',
                    CONFIG.notificationsEnabled ? 'success' : 'warning'
                );
            });
        }

        // Message hover effects
        document.addEventListener('mouseenter', function (e) {
            if (e.target.closest('.message-bubble')) {
                e.target.closest('.message-bubble').style.transform = 'translateY(-2px)';
            }
        }, true);

        document.addEventListener('mouseleave', function (e) {
            if (e.target.closest('.message-bubble')) {
                e.target.closest('.message-bubble').style.transform = 'translateY(0)';
            }
        }, true);

        // Chat box scroll to bottom button
        let scrollButton = null;
        if (elements.chatBox) {
            elements.chatBox.addEventListener('scroll', function () {
                const isAtBottom = this.scrollTop + this.clientHeight >= this.scrollHeight - 5;

                if (!isAtBottom && !scrollButton) {
                    scrollButton = document.createElement('button');
                    scrollButton.className = 'btn btn-primary btn-sm position-absolute';
                    scrollButton.style.cssText = 'bottom: 20px; right: 20px; border-radius: 50%; width: 40px; height: 40px; z-index: 1000;';
                    scrollButton.innerHTML = '<i class="fas fa-arrow-down"></i>';
                    scrollButton.title = 'Cuộn xuống cuối';

                    scrollButton.addEventListener('click', () => {
                        elements.chatBox.scrollTop = elements.chatBox.scrollHeight;
                    });

                    this.parentElement.appendChild(scrollButton);
                } else if (isAtBottom && scrollButton) {
                    scrollButton.remove();
                    scrollButton = null;
                }
            });
        }
    }

    function autoResizeTextarea() {
        if (!elements.messageInput) return;

        elements.messageInput.style.height = 'auto';
        const newHeight = Math.min(elements.messageInput.scrollHeight, 120);
        elements.messageInput.style.height = newHeight + 'px';
    }

    function updateNotificationToggle() {
        if (!elements.notificationToggle) return;

        const icon = elements.notificationToggle.querySelector('i');
        if (CONFIG.notificationsEnabled) {
            elements.notificationToggle.className = 'btn btn-sm btn-success';
            icon.className = 'fas fa-bell';
            elements.notificationToggle.title = 'Tắt thông báo';
        } else {
            elements.notificationToggle.className = 'btn btn-sm btn-outline-secondary';
            icon.className = 'fas fa-bell-slash';
            elements.notificationToggle.title = 'Bật thông báo';
        }
    }

    function requestNotificationPermission() {
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission().then(permission => {
                if (permission === 'granted') {
                    showToastMessage('Đã bật thông báo trình duyệt', 'success');
                }
            });
        }
    }

    function showErrorNotification(message) {
        if (!elements.notificationContainer) return;

        const toast = document.createElement('div');
        toast.className = 'alert alert-danger alert-dismissible mb-2';
        toast.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span>${message}</span>
                <button type="button" class="close ml-auto" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        `;

        elements.notificationContainer.appendChild(toast);
        setTimeout(() => toast.remove(), 5000);
    }

    function showToastMessage(message, type = 'info') {
        if (!elements.notificationContainer) return;

        const alertClass = `alert-${type}`;
        const iconClass = {
            success: 'fas fa-check-circle',
            warning: 'fas fa-exclamation-triangle',
            error: 'fas fa-times-circle',
            info: 'fas fa-info-circle'
        }[type] || 'fas fa-info-circle';

        const toast = document.createElement('div');
        toast.className = `alert ${alertClass} alert-dismissible mb-2`;
        toast.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="${iconClass} mr-2"></i>
                <span>${message}</span>
                <button type="button" class="close ml-auto" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        `;

        elements.notificationContainer.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    // Utility functions
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});