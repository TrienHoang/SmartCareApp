@extends('admin.dashboard')

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
                                <div
                                    class="message mb-3 d-flex {{ $message->sender_type === 'user' ? 'justify-content-end' : 'justify-content-start' }}">
                                    <div class="message-content" style="max-width: 70%;">
                                        <div
                                            class="message-bubble p-3 rounded {{ $message->sender_type === 'user' ? 'bg-primary text-white' : 'bg-light' }}">
                                            {{ $message->message }}
                                        </div>
                                        <small class="text-muted d-block mt-1">
                                            {{ $message->sender_type === 'admin' ? 'Admin' : ($message->sender_type === 'bot' ? 'Bot' : 'Khách hàng') }}
                                            •
                                            {{ $message->created_at->format('H:i d/m') }}
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Reply Form -->
                        <form action="{{ route('admin.chat.send', $session) }}" method="POST">
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto scroll to bottom
            const chatBox = document.querySelector('.chat-box');
            if (chatBox) {
                chatBox.scrollTop = chatBox.scrollHeight;
            }

            let sessionId = document.head.querySelector('meta[name="chat-session-id"]').content;
            console.log(`🎧 Admin listening to channel: chat-session-${sessionId}`);

            // ✅ Debug Echo connection
            if (window.Echo && window.Echo.connector) {
                console.log('✅ Echo available for admin, Pusher state:', window.Echo.connector.pusher.connection
                    .state);

                const setupListener = () => {
                    window.Echo.private(`chat-session-${sessionId}`)
                        .listen('.chat-message-sent', (e) => {
                            console.log('📩 Admin received message from user:', e);
                            console.log('📩 Message content:', e.message);

                            // ✅ Tạo message element với styling đẹp
                            let chatBoxEl = document.querySelector('.chat-box');
                            if (chatBoxEl) {
                                let messageDiv = document.createElement('div');
                                messageDiv.className = 'mb-3 flex justify-end';
                                messageDiv.innerHTML = `
                                <div class="bg-blue-500 text-white px-4 py-2 rounded-lg max-w-xs">
                                    <p class="text-sm">${escapeHtml(e.message)}</p>
                                    <p class="text-xs opacity-75 mt-1">Khách hàng • vừa xong</p>
                                </div>
                            `;

                                chatBoxEl.appendChild(messageDiv);
                                chatBoxEl.scrollTop = chatBoxEl.scrollHeight;

                                // ✅ Thêm notification sound hoặc visual indicator
                                showNewMessageNotification();
                            }
                        })
                        .subscribed(() => {
                            console.log('✅ Admin successfully subscribed to channel');
                        })
                        .error((error) => {
                            console.error('❌ Admin channel subscription error:', error);
                        });
                };

                // Setup listener when connection is ready
                if (window.Echo.connector.pusher.connection.state === 'connected') {
                    setupListener();
                } else {
                    window.Echo.connector.pusher.connection.bind('connected', setupListener);
                }
            } else {
                console.error('❌ Echo not available for admin panel');
            }

            // ✅ Helper functions
            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            function showNewMessageNotification() {
                // ✅ Visual notification
                const title = document.title;
                document.title = '🔔 Tin nhắn mới - ' + title;

                setTimeout(() => {
                    document.title = title;
                }, 3000);

                // ✅ Browser notification (nếu được phép)
                if ('Notification' in window && Notification.permission === 'granted') {
                    new Notification('Tin nhắn mới từ khách hàng', {
                        body: 'Có tin nhắn mới trong chat',
                        icon: '/favicon.ico'
                    });
                }
            }

            // ✅ Request notification permission
            if ('Notification' in window && Notification.permission === 'default') {
                Notification.requestPermission();
            }
        });
    </script>
@endpush
