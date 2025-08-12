@extends('reception.dashboard')

@section('title', 'Chi tiết Chat Session')

<head>
    <meta name="chat-session-id" content="{{ $session->session_id }}">
</head>

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Chat với
                            {{ $session->user ? $session->user->full_name : ($session->visitor_name ?: 'Khách ẩn danh') }}
                        </h3>
                    </div>

                    <div class="card-body">
                        <div class="chat-box"
                            style="height: 400px; overflow-y: auto; border: 1px solid #ddd; padding: 15px; margin-bottom: 15px;">
                            @foreach ($messages as $message)
                                @php
                                    $isReceptionOrBot = in_array($message->sender_type, ['receptionist', 'bot']);
                                @endphp

                                <div
                                    class="message mb-3 d-flex {{ $isReceptionOrBot ? 'justify-content-end' : 'justify-content-start' }}">
                                    <div class="message-content" style="max-width: 70%;">
                                        <div
                                            class="message-bubble p-3 rounded {{ $isReceptionOrBot ? 'bg-primary text-white' : 'bg-light' }}">
                                            {{ $message->message }}
                                        </div>
                                        <small class="text-muted d-block mt-1">
                                            @if ($message->sender_type === 'admin')
                                                Admin
                                            @elseif ($message->sender_type === 'bot')
                                                Bot
                                            @elseif ($message->sender_type === 'receptionist')
                                                Lễ tân
                                            @else
                                                Khách hàng
                                            @endif
                                            • {{ $message->created_at->format('H:i d/m') }}
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Reply Form -->
                        <form action="{{ route('receptionist.chat.send', $session->id) }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <textarea name="message" class="form-control" placeholder="Nhập phản hồi..." rows="2" required></textarea>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane"></i> Gửi
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Thông tin khách hàng</h4>
                    </div>
                    <div class="card-body">
                        @if ($session->user)
                            <p><strong>Tên:</strong> {{ $session->user->full_name }}</p>
                            <p><strong>Email:</strong> {{ $session->user->email }}</p>
                            <p><strong>Điện thoại:</strong> {{ $session->user->phone }}</p>
                        @else
                            <p><strong>Tên:</strong> {{ $session->visitor_name ?: 'N/A' }}</p>
                            <p><strong>Email:</strong> {{ $session->visitor_email ?: 'N/A' }}</p>
                            <p><strong>Điện thoại:</strong> {{ $session->visitor_phone ?: 'N/A' }}</p>
                        @endif

                        <hr>
                        <p><strong>Trạng thái:</strong>
                            <span class="badge badge-{{ $session->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($session->status) }}
                            </span>
                        </p>
                        <p><strong>Bắt đầu:</strong> {{ $session->started_at->format('H:i d/m/Y') }}</p>
                        <p><strong>Tổng tin nhắn:</strong> {{ $messages->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto scroll to bottom
            const chatBox = document.querySelector('.chat-box');
            if (chatBox) {
                chatBox.scrollTop = chatBox.scrollHeight;
            }

            let sessionId = document.head.querySelector('meta[name="chat-session-id"]').content;
            console.log(`🎧 Reception listening to channel: chat-session-${sessionId}`);

            if (window.Echo && window.Echo.connector) {
                console.log('✅ Echo available for reception, Pusher state:', window.Echo.connector.pusher.connection
                    .state);

                const setupListener = () => {
                    window.Echo.private(`chat-session-${sessionId}`)
                        .listen('.chat-message-sent', (e) => {
                            console.log('📩 Reception received message:', e);

                            // 🚫 Chặn tin nhắn cảm ơn lặp lại
                            if (e.sender_type === 'bot' && e.message.includes("Cảm ơn bạn đã liên hệ")) {
                                const today = new Date().toISOString().split('T')[0];
                                const lastThanksDate = localStorage.getItem('lastThanksDateReception');
                                const receptionistReplied = localStorage.getItem('receptionistReplied') ===
                                    'true';

                                if (receptionistReplied) {
                                    console.log('⏩ Bỏ qua tin cảm ơn vì lễ tân đã trả lời');
                                    return;
                                }

                                if (lastThanksDate === today) {
                                    console.log('⏩ Bỏ qua tin cảm ơn vì đã hiển thị hôm nay');
                                    return;
                                }

                                localStorage.setItem('lastThanksDateReception', today);
                            }

                            // ✅ Đánh dấu nếu lễ tân đã trả lời
                            if (e.sender_type === 'receptionist') {
                                localStorage.setItem('receptionistReplied', 'true');
                            }

                            // ---- Render tin nhắn ----
                            let chatBoxEl = document.querySelector('.chat-box');
                            if (chatBoxEl) {
                                let messageDiv = document.createElement('div');
                                let isReceptionOrBot = ['receptionist', 'bot'].includes(e.sender_type);

                                messageDiv.className =
                                    `mb-3 flex ${isReceptionOrBot ? 'justify-end' : 'justify-start'}`;
                                messageDiv.innerHTML = `
            <div class="${isReceptionOrBot ? 'bg-blue-500 text-white' : 'bg-light'} px-4 py-2 rounded-lg max-w-xs">
                <p class="text-sm">${escapeHtml(e.message)}</p>
                <p class="text-xs opacity-75 mt-1">
                    ${e.sender_type === 'receptionist' ? 'Lễ tân' : e.sender_type === 'bot' ? 'Bot' : 'Khách hàng'} • vừa xong
                </p>
            </div>
        `;
                                chatBoxEl.appendChild(messageDiv);
                                chatBoxEl.scrollTop = chatBoxEl.scrollHeight;
                                showNewMessageNotification();
                            }
                        })
                        .subscribed(() => {
                            console.log('✅ Reception successfully subscribed to channel');
                        })
                        .error((error) => {
                            console.error('❌ Reception channel subscription error:', error);
                        });
                };

                if (window.Echo.connector.pusher.connection.state === 'connected') {
                    setupListener();
                } else {
                    window.Echo.connector.pusher.connection.bind('connected', setupListener);
                }
            } else {
                console.error('❌ Echo not available for reception panel');
            }

            // ✅ Helper functions
            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            function showNewMessageNotification() {
                const title = document.title;
                document.title = '🔔 Tin nhắn mới - ' + title;

                setTimeout(() => {
                    document.title = title;
                }, 3000);

                if ('Notification' in window && Notification.permission === 'granted') {
                    new Notification('Tin nhắn mới từ khách hàng', {
                        body: 'Có tin nhắn mới trong chat',
                        icon: '/favicon.ico'
                    });
                }
            }

            if ('Notification' in window && Notification.permission === 'default') {
                Notification.requestPermission();
            }
        });
    </script>
@endpush --}}
