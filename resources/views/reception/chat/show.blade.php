@extends('reception.dashboard')

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
                                    $isReceptionOrBot = in_array($message->sender_type, [
                                        'admin',
                                        'receptionist',
                                        'bot',
                                    ]);
                                @endphp

                                <div class="message mb-3 d-flex {{ $isReceptionOrBot ? 'justify-content-end' : 'justify-content-start' }}"
                                    data-message-id="{{ $message->id }}">
                                    <div class="message-content" style="max-width: 75%;">
                                        <div class="message-bubble p-3 rounded-lg shadow-sm {{ $isReceptionOrBot ? 'bg-primary text-white' : 'bg-white border' }}"
                                            style="border-radius: {{ $isReceptionOrBot ? '20px 20px 5px 20px' : '20px 20px 20px 5px' }};">
                                            {!! nl2br(e($message->message)) !!}
                                        </div>
                                        <small
                                            class="text-muted d-block mt-1 {{ $isReceptionOrBot ? 'text-right' : 'text-left' }}">
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
                            <form action="{{ route('receptionist.chat.send', $session->id) }}" method="POST">
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
