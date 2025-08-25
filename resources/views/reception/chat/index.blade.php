@extends('reception.dashboard')

@section('title', 'Quản lý Chat')

@push('styles')
    <style>
        .chat-session-item {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }

        .chat-session-item:hover {
            background: #f8f9fa;
        }

        .chat-session-item.has-unread {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
        }

        .unread-badge {
            position: absolute;
            top: 10px;
            right: 15px;
            background: #dc3545;
            color: white;
            border-radius: 10px;
            padding: 2px 8px;
            font-size: 11px;
            font-weight: bold;
            min-width: 18px;
            text-align: center;
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        .status-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }

        .status-active {
            background: #28a745;
        }

        .status-waiting {
            background: #ffc107;
        }

        .status-closed {
            background: #6c757d;
        }

        .total-unread-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #dc3545;
            color: white;
            border-radius: 20px;
            padding: 8px 15px;
            font-size: 12px;
            font-weight: bold;
            z-index: 1000;
            animation: slideIn 0.3s ease-out;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .search-filter-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .session-stats {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }

        .stat-card {
            background: white;
            padding: 10px 15px;
            border-radius: 6px;
            border: 1px solid #dee2e6;
            text-align: center;
            flex: 1;
        }

        .stat-number {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
        }

        .stat-label {
            font-size: 11px;
            color: #6c757d;
            text-transform: uppercase;
        }

        .online-indicator {
            width: 8px;
            height: 8px;
            background: #28a745;
            border-radius: 50%;
            display: inline-block;
            margin-left: 5px;
            animation: blink 2s infinite;
        }

        @keyframes blink {

            0%,
            50% {
                opacity: 1;
            }

            51%,
            100% {
                opacity: 0.3;
            }
        }

        .new-message-highlight {
            background: linear-gradient(90deg, #fff3cd, #ffffff) !important;
            animation: highlightFade 3s ease-out;
        }

        @keyframes highlightFade {
            0% {
                background: #fff3cd;
            }

            100% {
                background: #ffffff;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h3>Quản lý Chat
                        <span class="badge badge-primary" id="total-sessions-count">{{ $sessions->count() }}</span>
                    </h3>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary" id="refresh-btn">
                            <i class="fas fa-sync-alt" id="refresh-icon"></i> Làm mới
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="search-filter-section">
            <div class="session-stats">
                <div class="stat-card">
                    <div class="stat-number" id="stat-total">{{ $sessions->count() }}</div>
                    <div class="stat-label">Tổng cộng</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="stat-active">{{ $sessions->where('status', 'active')->count() }}</div>
                    <div class="stat-label">Hoạt động</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="stat-waiting">{{ $sessions->where('status', 'waiting')->count() }}</div>
                    <div class="stat-label">Đang chờ</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number text-danger" id="stat-unread">0</div>
                    <div class="stat-label">Chưa đọc</div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <input type="text" class="form-control" id="search-input"
                        placeholder="🔍 Tìm kiếm theo tên hoặc email...">
                </div>
                <div class="col-md-3">
                    <select class="form-control" id="status-filter">
                        <option value="all">📋 Tất cả trạng thái</option>
                        <option value="active">🟢 Hoạt động</option>
                        <option value="waiting">🟡 Đang chờ</option>
                        <option value="closed">⚫ Đã đóng</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-control" id="sort-filter">
                        <option value="newest">⏰ Mới nhất</option>
                        <option value="oldest">📅 Cũ nhất</option>
                        <option value="unread">🔔 Chưa đọc trước</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Total Unread Notification -->
        <div id="total-unread-notification" class="total-unread-notification" style="display: none;">
            <i class="fas fa-envelope"></i>
            <span id="total-unread-count">0</span> tin nhắn mới
        </div>

        <!-- Main Content -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="80">STT</th>
                                        <th>Khách hàng</th>
                                        <th>Tin nhắn cuối</th>
                                        <th width="120">Trạng thái</th>
                                        <th width="150">Thời gian</th>
                                        <th width="120">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody id="sessions-tbody">
                                    @foreach ($sessions as $key => $session)
                                        <tr class="chat-session-row" data-session-id="{{ $session->session_id }}"
                                            data-status="{{ $session->status }}"
                                            data-user-name="{{ $session->user ? $session->user->full_name : ($session->visitor_name ?: 'Khách ẩn danh') }}"
                                            data-user-email="{{ $session->user ? $session->user->email : $session->visitor_email }}"
                                            data-last-message-time="{{ $session->latestMessage ? $session->latestMessage->created_at->timestamp : $session->started_at->timestamp }}">

                                            <td class="position-relative">
                                                {{ $loop->iteration + ($sessions->currentPage() - 1) * $sessions->perPage() }}
                                                <div class="unread-badge session-unread-badge"
                                                    data-session="{{ $session->session_id }}"
                                                    style="{{ $session->unread_count > 0 ? '' : 'display:none' }}">
                                                    {{ $session->unread_count }}
                                                </div>
                                            </td>

                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="status-indicator status-{{ $session->status }}"></div>
                                                    <div>
                                                        @if ($session->user)
                                                            <strong
                                                                class="customer-name">{{ $session->user->full_name }}</strong>
                                                            @if ($session->user->is_online ?? false)
                                                                <span class="online-indicator" title="Đang online"></span>
                                                            @endif
                                                            <br>
                                                            <small
                                                                class="text-muted customer-email">{{ $session->user->email }}</small>
                                                        @else
                                                            <strong
                                                                class="customer-name">{{ $session->visitor_name ?: 'Khách ẩn danh' }}</strong><br>
                                                            <small
                                                                class="text-muted customer-email">{{ $session->visitor_email }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                @if ($session->latestMessage)
                                                    <div class="latest-message-content">
                                                        <div class="text-truncate" style="max-width: 250px;">
                                                            @if ($session->latestMessage->sender_type === 'admin')
                                                                <span class="text-primary"><i class="fas fa-reply"></i>
                                                                    Admin:
                                                                </span>
                                                            @elseif($session->latestMessage->sender_type === 'bot')
                                                                <span class="text-info"><i class="fas fa-robot"></i> Bot:
                                                                </span>
                                                            @elseif($session->latestMessage->sender_type === 'receptionist')
                                                                <span class="text-success"><i class="fas fa-user-tie"></i>
                                                                    Bạn: </span>
                                                            @endif
                                                            <span
                                                                class="latest-message-text">{{ Str::limit($session->latestMessage->message, 50) }}</span>
                                                        </div>
                                                        <small class="text-muted latest-message-time">
                                                            {{ $session->latestMessage->created_at->diffForHumans() }}
                                                        </small>
                                                    </div>
                                                @else
                                                    <div class="latest-message-content">
                                                        <span class="text-muted">Chưa có tin nhắn</span><br>
                                                        <small class="text-muted latest-message-time">
                                                            {{ $session->started_at->diffForHumans() }}
                                                        </small>
                                                    </div>
                                                @endif
                                            </td>

                                            <td>
                                                <span
                                                    class="badge session-status-badge badge-{{ $session->status === 'active' ? 'success' : ($session->status === 'waiting' ? 'warning' : 'secondary') }}">
                                                    @if ($session->status === 'active')
                                                        🟢 Hoạt động
                                                    @elseif($session->status === 'waiting')
                                                        🟡 Đang chờ
                                                    @else
                                                        ⚫ {{ ucfirst($session->status) }}
                                                    @endif
                                                </span>
                                            </td>

                                            <td>
                                                <small
                                                    class="session-start-time">{{ $session->started_at->format('H:i d/m/Y') }}</small>
                                            </td>

                                            <td>
                                                <a href="{{ route('receptionist.chat.show', $session->id) }}"
                                                    class="btn btn-sm btn-primary" title="Xem chi tiết cuộc hội thoại">
                                                    <i class="fas fa-eye"></i> Xem
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="p-3">
                            {{ $sessions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State (when filtered) -->
        <div id="empty-state" style="display: none;" class="text-center py-5">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Không tìm thấy cuộc hội thoại nào</h5>
            <p class="text-muted">Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm</p>
        </div>
    </div>

    <!-- Hidden audio element for notifications -->
    <audio id="notification-sound" preload="auto" style="display: none;">
        <source src="{{ asset('sounds/notification.mp3') }}" type="audio/mpeg">
        <source src="{{ asset('sounds/notification.wav') }}" type="audio/wav">
        <!-- Fallback notification sound using data URL -->
        <source
            src="data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+PyvmwhBkOY4a82CAQU"
            type="audio/wav">
    </audio>
@endsection

@push('scripts')
    <script>
        class ReceptionistChatNotificationManager {
            constructor() {
                this.unreadCounts = {}; // Track unread count per session
                this.totalUnread = 0;
                this.currentFilter = 'all';
                this.currentSort = 'newest';
                this.searchQuery = '';

                this.initializeElements();
                this.initializeUnreadCounts();
                this.bindEvents();
                this.setupRealtime();
                this.requestNotificationPermission();
                this.updateStats();

                console.log('✅ Receptionist Chat Notification Manager initialized');
            }

            initializeElements() {
                this.$sessionsTable = document.getElementById('sessions-tbody');
                this.$searchInput = document.getElementById('search-input');
                this.$statusFilter = document.getElementById('status-filter');
                this.$sortFilter = document.getElementById('sort-filter');
                this.$refreshBtn = document.getElementById('refresh-btn');
                this.$totalUnreadNotification = document.getElementById('total-unread-notification');
                this.$notificationSound = document.getElementById('notification-sound');
                this.$emptyState = document.getElementById('empty-state');
            }

            initializeUnreadCounts() {
                document.querySelectorAll('.session-unread-badge[data-session]').forEach(badge => {
                    const sessionId = badge.dataset.session;
                    const count = parseInt(badge.textContent) || 0;
                    if (count > 0) {
                        this.unreadCounts[sessionId] = count;
                    }
                });
                this.updateTotalUnreadNotification();
            }

            bindEvents() {
                // Search functionality
                this.$searchInput.addEventListener('input', (e) => {
                    this.searchQuery = e.target.value.toLowerCase();
                    this.filterSessions();
                });

                // Status filter
                this.$statusFilter.addEventListener('change', (e) => {
                    this.currentFilter = e.target.value;
                    this.filterSessions();
                });

                // Sort filter
                this.$sortFilter.addEventListener('change', (e) => {
                    this.currentSort = e.target.value;
                    this.sortSessions();
                });

                // Refresh button
                this.$refreshBtn.addEventListener('click', () => {
                    this.refreshSessions();
                });

                // Click notification to hide
                this.$totalUnreadNotification.addEventListener('click', () => {
                    this.$totalUnreadNotification.style.display = 'none';
                });
            }

            setupRealtime() {
                if (typeof window.Echo === 'undefined') {
                    console.warn('⚠️ Laravel Echo not available. Realtime features disabled.');
                    return;
                }

                console.log('🔄 Setting up realtime listeners for receptionist...');

                // Listen to all chat sessions for new messages
                window.Echo.channel('chat-global')
                    .listen('.chat-message-sent', (e) => {
                        console.log('📨 New message received:', e);
                        this.handleNewMessage(e);
                    })
                    .listen('.session-status-updated', (e) => {
                        console.log('📊 Session status updated:', e);
                        this.handleSessionStatusUpdate(e);
                    })
                    .subscribed(() => {
                        console.log('✅ Successfully subscribed to receptionist chat notifications');
                    })
                    .error((error) => {
                        console.error('❌ Failed to subscribe to receptionist chat notifications:', error);
                    });
            }

            handleNewMessage(data) {
                const sessionId = data.session_id;
                const message = data.message;

                // Skip if message is from receptionist (our own messages)
                if (data.sender_type === 'receptionist') {
                    return;
                }

                // Update unread count
                this.unreadCounts[sessionId] = (this.unreadCounts[sessionId] || 0) + 1;
                this.updateUnreadBadge(sessionId);
                this.updateTotalUnreadNotification();

                // Update latest message in the row
                this.updateLatestMessage(sessionId, message, data.sender_type);

                // Move session to top
                this.moveSessionToTop(sessionId);

                // Show visual feedback
                this.highlightSession(sessionId);

                // Play notification sound
                this.playNotificationSound();

                // Show desktop notification
                this.showDesktopNotification(sessionId, message);

                // Update stats
                this.updateStats();
            }

            handleSessionStatusUpdate(data) {
                const sessionId = data.session_id;
                const newStatus = data.status;

                const sessionRow = document.querySelector(`[data-session-id="${sessionId}"]`);
                if (sessionRow) {
                    // Update status badge
                    const statusBadge = sessionRow.querySelector('.session-status-badge');
                    if (statusBadge) {
                        statusBadge.className =
                            `badge session-status-badge badge-${newStatus === 'active' ? 'success' : (newStatus === 'waiting' ? 'warning' : 'secondary')}`;

                        let statusText = newStatus;
                        if (newStatus === 'active') statusText = '🟢 Hoạt động';
                        else if (newStatus === 'waiting') statusText = '🟡 Đang chờ';
                        else statusText = `⚫ ${newStatus}`;

                        statusBadge.textContent = statusText;
                    }

                    // Update status indicator
                    const statusIndicator = sessionRow.querySelector('.status-indicator');
                    if (statusIndicator) {
                        statusIndicator.className = `status-indicator status-${newStatus}`;
                    }

                    // Update data attribute
                    sessionRow.dataset.status = newStatus;
                }

                this.updateStats();
                this.filterSessions(); // Re-apply filters
            }

            updateUnreadBadge(sessionId) {
                const badge = document.querySelector(`.session-unread-badge[data-session="${sessionId}"]`);
                const sessionRow = document.querySelector(`[data-session-id="${sessionId}"]`);
                const count = this.unreadCounts[sessionId] || 0;

                if (badge && count > 0) {
                    badge.textContent = count > 99 ? '99+' : count;
                    badge.style.display = 'block';

                    if (sessionRow) {
                        sessionRow.classList.add('has-unread');
                    }
                } else if (badge) {
                    badge.style.display = 'none';
                    if (sessionRow) {
                        sessionRow.classList.remove('has-unread');
                    }
                }
            }

            updateTotalUnreadNotification() {
                const total = Object.values(this.unreadCounts).reduce((sum, count) => sum + count, 0);
                this.totalUnread = total;

                if (total > 0) {
                    document.getElementById('total-unread-count').textContent = total;
                    this.$totalUnreadNotification.style.display = 'block';

                    clearTimeout(this._notificationTimeout);
                    this._notificationTimeout = setTimeout(() => {
                        this.$totalUnreadNotification.style.display = 'none';
                    }, 3000);

                    // Update page title
                    document.title = `(${total}) Quản lý Chat - Lễ tân`;
                } else {
                    this.$totalUnreadNotification.style.display = 'none';
                    document.title = 'Quản lý Chat - Lễ tân';
                }

                // Update stats
                document.getElementById('stat-unread').textContent = total;
            }

            updateLatestMessage(sessionId, message, senderType) {
                const sessionRow = document.querySelector(`[data-session-id="${sessionId}"]`);
                if (!sessionRow) return;

                const latestMessageContent = sessionRow.querySelector('.latest-message-content');
                if (!latestMessageContent) return;

                let senderPrefix = '';
                if (senderType === 'bot') senderPrefix =
                    '<span class="text-info"><i class="fas fa-robot"></i> Bot: </span>';
                else if (senderType === 'admin') senderPrefix =
                    '<span class="text-primary"><i class="fas fa-reply"></i> Admin: </span>';
                else if (senderType === 'customer') senderPrefix = '';

                latestMessageContent.innerHTML = `
            <div class="text-truncate" style="max-width: 250px;">
                ${senderPrefix}
                <span class="latest-message-text">${this.escapeHtml(message.substring(0, 50))}</span>
            </div>
            <small class="text-muted latest-message-time">Vừa xong</small>
        `;

                // Update data attribute for sorting
                sessionRow.dataset.lastMessageTime = Math.floor(Date.now() / 1000);
            }

            moveSessionToTop(sessionId) {
                const sessionRow = document.querySelector(`[data-session-id="${sessionId}"]`);
                if (sessionRow) {
                    this.$sessionsTable.insertBefore(sessionRow, this.$sessionsTable.firstChild);
                    this.updateRowNumbers();
                }
            }

            highlightSession(sessionId) {
                const sessionRow = document.querySelector(`[data-session-id="${sessionId}"]`);
                if (sessionRow) {
                    sessionRow.classList.add('new-message-highlight');
                    setTimeout(() => {
                        sessionRow.classList.remove('new-message-highlight');
                    }, 3000);
                }
            }

            playNotificationSound() {
                if (this.$notificationSound && document.visibilityState === 'hidden') {
                    this.$notificationSound.play().catch(e => {
                        console.log('Could not play notification sound:', e);
                    });
                }
            }

            showDesktopNotification(sessionId, message) {
                if ('Notification' in window && Notification.permission === 'granted' && document.hidden) {
                    const sessionRow = document.querySelector(`[data-session-id="${sessionId}"]`);
                    const customerName = sessionRow ? sessionRow.dataset.userName : 'Khách hàng';

                    const notification = new Notification(`Tin nhắn mới từ ${customerName}`, {
                        body: message.substring(0, 100),
                        icon: '/favicon.ico',
                        badge: '/favicon.ico',
                        tag: `chat-${sessionId}`,
                        requireInteraction: false
                    });

                    setTimeout(() => notification.close(), 5000);

                    notification.onclick = () => {
                        window.focus();
                        // Navigate to chat detail page
                        const chatLink = sessionRow?.querySelector('a[href*="/chat/"]');
                        if (chatLink) {
                            window.location.href = chatLink.href;
                        }
                        notification.close();
                    };
                }
            }

            filterSessions() {
                const rows = document.querySelectorAll('.chat-session-row');
                let visibleCount = 0;

                rows.forEach(row => {
                    const status = row.dataset.status;
                    const userName = row.dataset.userName.toLowerCase();
                    const userEmail = row.dataset.userEmail.toLowerCase();

                    let matchesFilter = this.currentFilter === 'all' || status === this.currentFilter;
                    let matchesSearch = this.searchQuery === '' ||
                        userName.includes(this.searchQuery) ||
                        userEmail.includes(this.searchQuery);

                    if (matchesFilter && matchesSearch) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Show/hide empty state
                if (visibleCount === 0) {
                    this.$emptyState.style.display = 'block';
                    document.querySelector('.table-responsive').style.display = 'none';
                } else {
                    this.$emptyState.style.display = 'none';
                    document.querySelector('.table-responsive').style.display = 'block';
                }

                this.updateRowNumbers();
            }

            sortSessions() {
                const rows = Array.from(document.querySelectorAll('.chat-session-row'));

                rows.sort((a, b) => {
                    switch (this.currentSort) {
                        case 'newest':
                            return parseInt(b.dataset.lastMessageTime) - parseInt(a.dataset.lastMessageTime);
                        case 'oldest':
                            return parseInt(a.dataset.lastMessageTime) - parseInt(b.dataset.lastMessageTime);
                        case 'unread':
                            const aUnread = this.unreadCounts[a.dataset.sessionId] || 0;
                            const bUnread = this.unreadCounts[b.dataset.sessionId] || 0;
                            return bUnread - aUnread;
                        default:
                            return 0;
                    }
                });

                // Re-append sorted rows
                rows.forEach(row => this.$sessionsTable.appendChild(row));
                this.updateRowNumbers();
            }

            updateRowNumbers() {
                const visibleRows = document.querySelectorAll(
                    '.chat-session-row[style=""], .chat-session-row:not([style])');
                visibleRows.forEach((row, index) => {
                    const numberCell = row.querySelector('td:first-child');
                    if (numberCell) {
                        const badgeElement = numberCell.querySelector('.session-unread-badge');
                        numberCell.innerHTML = `${index + 1}`;
                        if (badgeElement) {
                            numberCell.appendChild(badgeElement);
                        }
                    }
                });
            }

            updateStats() {
                const rows = document.querySelectorAll('.chat-session-row');
                const activeCount = document.querySelectorAll('[data-status="active"]').length;
                const waitingCount = document.querySelectorAll('[data-status="waiting"]').length;
                const totalCount = rows.length;

                document.getElementById('stat-total').textContent = totalCount;
                document.getElementById('stat-active').textContent = activeCount;
                document.getElementById('stat-waiting').textContent = waitingCount;
                document.getElementById('stat-unread').textContent = this.totalUnread;
                document.getElementById('total-sessions-count').textContent = totalCount;
            }

            refreshSessions() {
                const refreshIcon = document.getElementById('refresh-icon');
                refreshIcon.classList.add('fa-spin');

                // Simulate refresh (in real app, this would be an AJAX call)
                setTimeout(() => {
                    refreshIcon.classList.remove('fa-spin');
                    // location.reload(); // Uncomment for real refresh
                }, 1000);
            }

            requestNotificationPermission() {
                if ('Notification' in window && Notification.permission === 'default') {
                    Notification.requestPermission().then(permission => {
                        if (permission === 'granted') {
                            console.log('✅ Desktop notifications enabled');
                        } else {
                            console.log('⚠️ Desktop notifications denied');
                        }
                    });
                }
            }

            // Utility methods
            escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            // Clear unread count when session is viewed
            clearUnreadCount(sessionId) {
                this.unreadCounts[sessionId] = 0;
                this.updateUnreadBadge(sessionId);
                this.updateTotalUnreadNotification();
            }

            // Mark session as read when clicked
            markSessionAsRead(sessionId) {
                this.clearUnreadCount(sessionId);
            }
        }

        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize the chat notification manager
            window.receptionistChatNotificationManager = new ReceptionistChatNotificationManager();

            // Handle session clicks to mark as read
            document.addEventListener('click', function(e) {
                const sessionLink = e.target.closest('a[href*="/chat/"]');
                if (sessionLink) {
                    const sessionRow = sessionLink.closest('.chat-session-row');
                    if (sessionRow) {
                        const sessionId = sessionRow.dataset.sessionId;
                        window.receptionistChatNotificationManager.markSessionAsRead(sessionId);
                    }
                }
            });

            // Handle page visibility changes
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    // When page becomes visible, you might want to refresh data or clear notifications
                    console.log('Page became visible - consider refreshing data');
                }
            });

            // Auto-refresh every 30 seconds when page is visible
            setInterval(function() {
                if (!document.hidden) {
                    // Auto refresh logic here
                    // window.receptionistChatNotificationManager.refreshSessions();
                }
            }, 30000);

            // Handle beforeunload to cleanup
            window.addEventListener('beforeunload', function() {
                console.log('Cleaning up receptionist chat notification listeners...');
            });

            console.log('✅ Receptionist Chat Index with Realtime Notifications ready!');
        });

        // Global functions for external access
        window.ReceptionistChatNotifications = {
            clearUnread: (sessionId) => {
                if (window.receptionistChatNotificationManager) {
                    window.receptionistChatNotificationManager.clearUnreadCount(sessionId);
                }
            },

            refreshSessions: () => {
                if (window.receptionistChatNotificationManager) {
                    window.receptionistChatNotificationManager.refreshSessions();
                }
            },

            getTotalUnread: () => {
                return window.receptionistChatNotificationManager ? window.receptionistChatNotificationManager
                    .totalUnread : 0;
            }
        };
    </script>
@endpush
