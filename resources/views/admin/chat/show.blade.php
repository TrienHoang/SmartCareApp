@extends('admin.dashboard')

@section('title', 'Chi tiết Chat Session')

<head>
    <meta name="chat-session-id" content="{{ $session->session_id }}">
</head>

@section('content')
    <div class="container-fluid">
        <!-- 🔔 Notification Toast Container -->
        <div id="notification-container" class="position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
        </div>

        <!-- 🔊 Audio for notification sound -->
        <audio id="notification-sound" preload="auto">
            <source src="/sounds/notification.mp3" type="audio/mpeg">
            <source src="/sounds/notification.wav" type="audio/wav">
        </audio>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-comments mr-2"></i>Chat với
                            {{ $session->user ? $session->user->full_name : ($session->visitor_name ?: 'Khách ẩn danh') }}
                        </h3>

                        <!-- Connection Status Indicator -->
                        <div class="d-flex align-items-center">
                            <div id="connection-status" class="mr-3">
                                <span id="status-indicator" class="badge badge-secondary">
                                    <i class="fas fa-circle mr-1"></i>Đang kết nối...
                                </span>
                            </div>

                            <!-- Notification Toggle -->
                            <button id="notification-toggle" class="btn btn-sm btn-outline-secondary"
                                title="Bật/Tắt thông báo">
                                <i class="fas fa-bell"></i>
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <!-- Typing Indicator -->
                        <div id="typing-indicator" class="px-3 py-2 bg-light border-bottom" style="display: none;">
                            <small class="text-muted">
                                <i class="fas fa-circle-notch fa-spin mr-1"></i>
                                <span id="typing-user"></span> đang nhập...
                            </small>
                        </div>

                        <div class="chat-box p-3"
                            style="height: 450px; overflow-y: auto; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                            @foreach ($messages as $message)
                                @php
                                    $isAdminOrBot = in_array($message->sender_type, ['admin', 'receptionist', 'bot']);
                                @endphp

                                <div class="message mb-3 d-flex {{ $isAdminOrBot ? 'justify-content-end' : 'justify-content-start' }}"
                                    data-message-id="{{ $message->id }}">
                                    <div class="message-content" style="max-width: 75%;">
                                        <div class="message-bubble p-3 rounded-lg shadow-sm {{ $isAdminOrBot ? 'bg-primary text-white' : 'bg-white border' }}"
                                            style="border-radius: {{ $isAdminOrBot ? '20px 20px 5px 20px' : '20px 20px 20px 5px' }};">
                                            {!! nl2br(e($message->message)) !!}
                                        </div>
                                        <small
                                            class="text-muted d-block mt-1 {{ $isAdminOrBot ? 'text-right' : 'text-left' }}">
                                            @if ($message->sender_type === 'admin')
                                                <i class="fas fa-user-shield mr-1"></i>Admin
                                            @elseif ($message->sender_type === 'bot')
                                                <i class="fas fa-robot mr-1"></i>Bot
                                            @elseif ($message->sender_type === 'receptionist')
                                                <i class="fas fa-concierge-bell mr-1"></i>Lễ tân
                                            @else
                                                <i class="fas fa-user mr-1"></i>Khách hàng
                                            @endif
                                            • {{ $message->created_at->format('H:i d/m') }}
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Reply Form -->
                        <div class="border-top bg-white p-3">
                            <form id="message-form" action="{{ route('admin.chat.send', $session) }}" method="POST">
                                @csrf
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn btn-outline-secondary" id="emoji-btn"
                                            title="Emoji">
                                            <i class="fas fa-smile"></i>
                                        </button>
                                    </div>
                                    <textarea name="message" id="message-input" class="form-control" placeholder="Nhập phản hồi..." rows="1" required
                                        style="resize: none; min-height: 38px; max-height: 120px;"></textarea>
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary" id="send-btn">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Quick Replies -->
                                <div class="mt-2">
                                    <small class="text-muted">Phản hồi nhanh:</small>
                                    <div class="quick-replies mt-1">
                                        <button type="button" class="btn btn-sm btn-outline-primary mr-1 mb-1 quick-reply"
                                            data-message="Xin chào! Tôi có thể giúp gì cho bạn?">
                                            Chào hỏi
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-primary mr-1 mb-1 quick-reply"
                                            data-message="Cảm ơn bạn đã liên hệ. Chúng tôi sẽ hỗ trợ bạn ngay.">
                                            Cảm ơn
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-primary mr-1 mb-1 quick-reply"
                                            data-message="Vui lòng chờ một chút, tôi sẽ kiểm tra thông tin cho bạn.">
                                            Chờ kiểm tra
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Customer Info Card -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-user-circle mr-2"></i>Thông tin khách hàng
                        </h4>
                    </div>
                    <div class="card-body">
                        @if ($session->user)
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-circle bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-3"
                                    style="width: 50px; height: 50px; font-size: 18px;">
                                    {{ substr($session->user->full_name, 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $session->user->full_name }}</h6>
                                    <small class="text-muted">Thành viên</small>
                                </div>
                            </div>

                            <p><strong><i class="fas fa-envelope mr-2"></i>Email:</strong>
                                <a href="mailto:{{ $session->user->email }}">{{ $session->user->email }}</a>
                            </p>
                            <p><strong><i class="fas fa-phone mr-2"></i>Điện thoại:</strong>
                                <a href="tel:{{ $session->user->phone }}">{{ $session->user->phone }}</a>
                            </p>
                        @else
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-circle bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center mr-3"
                                    style="width: 50px; height: 50px; font-size: 18px;">
                                    <i class="fas fa-user-secret"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $session->visitor_name ?: 'Khách ẩn danh' }}</h6>
                                    <small class="text-muted">Khách vãng lai</small>
                                </div>
                            </div>

                            <p><strong><i class="fas fa-envelope mr-2"></i>Email:</strong>
                                {{ $session->visitor_email ?: 'N/A' }}</p>
                            <p><strong><i class="fas fa-phone mr-2"></i>Điện thoại:</strong>
                                {{ $session->visitor_phone ?: 'N/A' }}</p>
                        @endif

                        <hr>
                        <p><strong><i class="fas fa-circle mr-2"></i>Trạng thái:</strong>
                            <span class="badge badge-{{ $session->status === 'active' ? 'success' : 'secondary' }}">
                                {{ $session->status === 'active' ? 'Đang hoạt động' : 'Không hoạt động' }}
                            </span>
                        </p>
                        <p><strong><i class="fas fa-clock mr-2"></i>Bắt đầu:</strong>
                            {{ $session->started_at->format('H:i d/m/Y') }}</p>
                        <p><strong><i class="fas fa-comment-dots mr-2"></i>Tổng tin nhắn:</strong> <span
                                id="message-count">{{ $messages->count() }}</span></p>
                    </div>
                </div>

                <!-- Chat Statistics Card -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-bar mr-2"></i>Thống kê Chat
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-3">
                                <div class="border-right">
                                    <h4 class="text-primary mb-0" id="admin-messages">
                                        {{ $messages->where('sender_type', 'admin')->count() }}</h4>
                                    <small class="text-muted">Admin</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="border-right">
                                    <h4 class="text-primary mb-0" id="receptionist-messages">
                                        {{ $messages->where('sender_type', 'receptionist')->count() }}</h4>
                                    <small class="text-muted">Nhân viên tiếp nhận</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="border-right">
                                    <h4 class="text-success mb-0" id="user-messages">
                                        {{ $messages->where('sender_type', 'user')->count() }}</h4>
                                    <small class="text-muted">Khách</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <h4 class="text-info mb-0" id="bot-messages">
                                    {{ $messages->where('sender_type', 'bot')->count() }}</h4>
                                <small class="text-muted">Bot</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                {{-- <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-bolt mr-2"></i>Thao tác
                        </h5>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-success btn-block mb-2" onclick="markAsResolved()">
                            <i class="fas fa-check mr-2"></i>Đánh dấu đã giải quyết
                        </button>
                        <button class="btn btn-warning btn-block mb-2" onclick="transferToAgent()">
                            <i class="fas fa-user-tie mr-2"></i>Chuyển cho nhân viên khác
                        </button>
                        <button class="btn btn-info btn-block" onclick="exportChat()">
                            <i class="fas fa-download mr-2"></i>Xuất lịch sử chat
                        </button>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>

    <!-- Emoji Picker Modal -->
    <div class="modal fade" id="emojiModal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chọn Emoji</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="emoji-grid"
                        style="display: grid; grid-template-columns: repeat(8, 1fr); gap: 5px; text-align: center;">
                        <span class="emoji-item" style="cursor: pointer; padding: 5px; font-size: 20px;">😀</span>
                        <span class="emoji-item" style="cursor: pointer; padding: 5px; font-size: 20px;">😊</span>
                        <span class="emoji-item" style="cursor: pointer; padding: 5px; font-size: 20px;">👍</span>
                        <span class="emoji-item" style="cursor: pointer; padding: 5px; font-size: 20px;">❤️</span>
                        <span class="emoji-item" style="cursor: pointer; padding: 5px; font-size: 20px;">😢</span>
                        <span class="emoji-item" style="cursor: pointer; padding: 5px; font-size: 20px;">😮</span>
                        <span class="emoji-item" style="cursor: pointer; padding: 5px; font-size: 20px;">🙏</span>
                        <span class="emoji-item" style="cursor: pointer; padding: 5px; font-size: 20px;">✨</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .message-bubble {
            transition: all 0.3s ease;
            position: relative;
        }

        .message-bubble:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        }

        .chat-box {
            scroll-behavior: smooth;
        }

        .notification-toast {
            min-width: 300px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            animation: slideInRight 0.5s ease-out;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        .notification-exit {
            animation: slideOutRight 0.3s ease-in;
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(0, 123, 255, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(0, 123, 255, 0);
            }
        }

        .typing-animation {
            animation: typing 1.4s ease-in-out infinite;
        }

        @keyframes typing {

            0%,
            60%,
            100% {
                transform: translateY(0);
            }

            30% {
                transform: translateY(-10px);
            }
        }

        .quick-reply:hover {
            transform: translateY(-2px);
            transition: all 0.2s ease;
        }

        .avatar-circle {
            transition: transform 0.2s ease;
        }

        .avatar-circle:hover {
            transform: scale(1.1);
        }

        #message-input {
            transition: all 0.3s ease;
        }

        #message-input:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
        }

        .emoji-item:hover {
            background: #f8f9fa;
            border-radius: 50%;
            transform: scale(1.2);
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 🎯 Configuration
            const CONFIG = {
                sessionId: document.head.querySelector('meta[name="chat-session-id"]').content,
                notificationsEnabled: localStorage.getItem('admin-notifications') !== 'false',
                soundEnabled: localStorage.getItem('admin-sound') !== 'false'
            };

            // 🎯 Elements
            const elements = {
                chatBox: document.querySelector('.chat-box'),
                messageInput: document.getElementById('message-input'),
                messageForm: document.getElementById('message-form'),
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
                console.log(`🎧 Admin listening to channel: chat-session-${CONFIG.sessionId}`);

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
                    console.error('❌ Echo not available for admin panel');
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
                            console.log('✅ Admin successfully subscribed to channel');
                            updateConnectionStatus('connected', 'Đã kết nối');
                        })
                        .error((error) => {
                            console.error('❌ Admin channel subscription error:', error);
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
                console.log('📩 Admin received message:', e);

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

                // Show notification only for non-admin messages
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
                    const lastThanksDate = localStorage.getItem('lastThanksDate');
                    const receptionistReplied = localStorage.getItem('receptionistReplied') === 'true';

                    if (receptionistReplied || lastThanksDate === today) return true;

                    localStorage.setItem('lastThanksDate', today);
                }
                return false;
            }

            function addMessageToChat(e) {
                if (!elements.chatBox) return;

                const isAdminOrBot = ['admin', 'receptionist', 'bot'].includes(e.sender_type);
                const messageDiv = document.createElement('div');

                messageDiv.className =
                    `message mb-3 d-flex ${isAdminOrBot ? 'justify-content-end' : 'justify-content-start'}`;
                messageDiv.setAttribute('data-message-id', e.id || Date.now());
                messageDiv.style.opacity = '0';
                messageDiv.style.transform = 'translateY(20px)';

                const senderInfo = getSenderInfo(e.sender_type);
                const bubbleClass = isAdminOrBot ? 'bg-primary text-white' : 'bg-white border';
                const borderRadius = isAdminOrBot ? '20px 20px 5px 20px' : '20px 20px 20px 5px';
                const textAlign = isAdminOrBot ? 'text-right' : 'text-left';

                messageDiv.innerHTML = `
                    <div class="message-content" style="max-width: 75%;">
                        <div class="message-bubble p-3 rounded-lg shadow-sm ${bubbleClass}" 
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
                    admin: {
                        icon: 'fas fa-user-shield',
                        name: 'Admin'
                    },
                    bot: {
                        icon: 'fas fa-robot',
                        name: 'Bot'
                    },
                    receptionist: {
                        icon: 'fas fa-concierge-bell',
                        name: 'Lễ tân'
                    },
                    user: {
                        icon: 'fas fa-user',
                        name: 'Khách hàng'
                    }
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

                    notification.onclick = function() {
                        window.focus();
                        notification.close();
                    };

                    setTimeout(() => notification.close(), 5000);
                }
            }

            function showToastNotification(e) {
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
                elements.chatBox.classList.add('pulse');
                setTimeout(() => elements.chatBox.classList.remove('pulse'), 2000);
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
                elements.statusIndicator.innerHTML =
                    `<i class="${statusIcons[status] || 'fas fa-circle'} mr-1"></i>${text}`;
            }

            function updateChatStatistics(e) {
                // Update message counts
                const messageCount = parseInt(elements.messageCount.textContent) + 1;
                elements.messageCount.textContent = messageCount;

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
                const messageCount = parseInt(elements.messageCount.textContent) + 1;
                elements.messageCount.textContent = messageCount;
            }

            function setupFormHandlers() {
                // Message form submission
                if (elements.messageForm) {
                    elements.messageForm.addEventListener('submit', function(e) {
                        e.preventDefault();

                        const message = elements.messageInput.value.trim();
                        if (!message) return;

                        // Disable send button temporarily
                        const sendBtn = document.getElementById('send-btn');
                        const originalText = sendBtn.innerHTML;
                        sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                        sendBtn.disabled = true;

                        // Submit form with better error handling
                        fetch(this.action, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')
                                        .value,
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: new URLSearchParams(new FormData(this))
                            })
                            .then(response => {
                                console.log('Response status:', response.status);
                                console.log('Response headers:', response.headers);

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
                                    return {
                                        success: true
                                    };
                                }
                            })
                            .then(data => {
                                console.log('Response data:', data);

                                // Clear input on success (whether JSON response or redirect)
                                elements.messageInput.value = '';
                                autoResizeTextarea();

                                // Show success toast
                                showToastMessage('Tin nhắn đã được gửi', 'success');

                                // If there's an error in JSON response
                                if (data.success === false) {
                                    console.error('Message send failed:', data.error || data.message);
                                    showErrorNotification(data.error || data.message ||
                                        'Không thể gửi tin nhắn. Vui lòng thử lại.');
                                }
                            })
                            .catch(error => {
                                console.error('Message send error:', error);

                                // More specific error messages
                                if (error.name === 'TypeError' && error.message.includes(
                                        'Failed to fetch')) {
                                    showErrorNotification(
                                        'Lỗi kết nối mạng. Vui lòng kiểm tra internet.');
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
                    elements.messageInput.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter' && !e.shiftKey) {
                            e.preventDefault();
                            elements.messageForm.dispatchEvent(new Event('submit'));
                        }
                    });
                }

                // Quick reply buttons
                document.querySelectorAll('.quick-reply').forEach(button => {
                    button.addEventListener('click', function() {
                        const message = this.getAttribute('data-message');
                        elements.messageInput.value = message;
                        elements.messageInput.focus();
                        autoResizeTextarea();
                    });
                });

                // Emoji picker
                const emojiBtn = document.getElementById('emoji-btn');
                if (emojiBtn) {
                    emojiBtn.addEventListener('click', function() {
                        $('#emojiModal').modal('show');
                    });
                }

                document.querySelectorAll('.emoji-item').forEach(emoji => {
                    emoji.addEventListener('click', function() {
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
                    elements.notificationToggle.addEventListener('click', function() {
                        CONFIG.notificationsEnabled = !CONFIG.notificationsEnabled;
                        localStorage.setItem('admin-notifications', CONFIG.notificationsEnabled);
                        updateNotificationToggle();

                        showToastMessage(
                            CONFIG.notificationsEnabled ? 'Đã bật thông báo' : 'Đã tắt thông báo',
                            CONFIG.notificationsEnabled ? 'success' : 'warning'
                        );
                    });
                }

                // Message hover effects
                document.addEventListener('mouseenter', function(e) {
                    if (e.target.closest('.message-bubble')) {
                        e.target.closest('.message-bubble').style.transform = 'translateY(-2px)';
                    }
                }, true);

                document.addEventListener('mouseleave', function(e) {
                    if (e.target.closest('.message-bubble')) {
                        e.target.closest('.message-bubble').style.transform = 'translateY(0)';
                    }
                }, true);

                // Chat box scroll to bottom button
                let scrollButton = null;
                if (elements.chatBox) {
                    elements.chatBox.addEventListener('scroll', function() {
                        const isAtBottom = this.scrollTop + this.clientHeight >= this.scrollHeight - 5;

                        if (!isAtBottom && !scrollButton) {
                            scrollButton = document.createElement('button');
                            scrollButton.className = 'btn btn-primary btn-sm position-absolute';
                            scrollButton.style.cssText =
                                'bottom: 20px; right: 20px; border-radius: 50%; width: 40px; height: 40px; z-index: 1000;';
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
                const alertClass = `alert-${type}`;
                const iconClass = {
                    success: 'fas fa-check-circle',
                    warning: 'fas fa-exclamation-triangle',
                    error: 'fas fa-times-circle',
                    info: 'fas fa-info-circle'
                } [type] || 'fas fa-info-circle';

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
    </script>
@endpush
