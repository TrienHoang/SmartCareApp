<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SmartCare - Hệ thống Y tế')</title>

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- SplideJS CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.3/dist/css/splide.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    <!-- App Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .active-link {
            font-weight: 600;
            color: #2563eb;
        }

        .splide__arrow {
            opacity: 0.3;
            transition: opacity 0.3s ease;
        }

        .splide__arrow:hover {
            opacity: 1;
        }

        #chatbox-content::-webkit-scrollbar {
            width: 6px;
        }

        #chatbox-content::-webkit-scrollbar-thumb {
            background-color: #ccc;
            border-radius: 8px;
        }

        #chatbox-content {
            scroll-behavior: smooth;
        }
    </style>

    @stack('styles')
</head>

<body class="flex flex-col min-h-screen bg-gray-50">

    {{-- Header --}}
    @include('client.partials.header')

    {{-- Nội dung chính --}}
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Chatbox UI -->
    <div id="chatbox-button"
        class="fixed bottom-6 right-6 z-50 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white w-16 h-16 rounded-full shadow-2xl flex items-center justify-center cursor-pointer transition-all duration-300 transform hover:scale-105">
        <i class="fas fa-comments text-2xl"></i>
        <div id="unread-count"
            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold hidden">
            0
        </div>
    </div>

    <!-- Enhanced Chatbox Modal -->
    <div id="chatbox-modal"
        class="fixed bottom-24 right-6 w-96 max-w-[95vw] bg-white rounded-2xl shadow-2xl border-0 hidden z-50 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-md text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-sm">Trợ lý Y tế SmartCare</h4>
                        <p class="text-xs text-blue-100">Online • Sẵn sàng tư vấn</p>
                    </div>
                </div>
                <button id="chatbox-close" class="hover:bg-white/10 p-2 rounded-full transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <!-- Quick Actions -->
        <div id="quick-actions" class="px-4 py-3 bg-gray-50 border-b">
            <div class="flex gap-2 flex-wrap">
                <button
                    class="quick-action-btn bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs hover:bg-blue-200 transition"
                    data-message="Hướng dẫn đặt lịch hẹn khám">
                    📅 Hướng dẫn đặt lịch
                </button>
                {{-- <button
                    class="quick-action-btn bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs hover:bg-green-200 transition"
                    data-message="Bảng giá dịch vụ">
                    💰 Bảng giá
                </button> --}}
                <button
                    class="quick-action-btn bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs hover:bg-purple-200 transition"
                    data-message="Thông tin bác sĩ">
                    👨‍⚕️ Bác sĩ
                </button>
            </div>
        </div>

        <!-- Chat Content -->
        <div class="flex flex-col h-96">
            <div class="flex-1 p-4 space-y-4 overflow-y-auto" id="chatbox-content">
                <div class="flex items-start gap-3">
                    <div
                        class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-robot text-white text-xs"></i>
                    </div>
                    <div class="bg-gray-100 p-3 rounded-2xl rounded-tl-sm max-w-[80%]">
                        <p class="text-sm text-gray-800">
                            Xin chào! 👋 Tôi là trợ lý ảo của SmartCare.<br>
                            Tôi có thể hỗ trợ bạn:<br>
                            • Hướng dẫn Đặt lịch khám<br>
                            • Tư vấn dịch vụ<br>
                            • Thông tin bác sĩ<br>
                            Bạn cần hỗ trợ gì ạ?
                        </p>
                    </div>
                </div>
            </div>

            <!-- Typing Indicator -->
            <div id="typing-indicator" class="px-4 py-2 hidden">
                <div class="flex items-center gap-2">
                    <div
                        class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-robot text-white text-xs"></i>
                    </div>
                    <div class="bg-gray-100 p-2 rounded-xl">
                        <div class="flex gap-1">
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-pulse"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-pulse" style="animation-delay: 0.2s">
                            </div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-pulse" style="animation-delay: 0.4s">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="p-4 bg-gray-50 border-t">
                <div class="flex items-end gap-3">
                    <div class="flex-1">
                        <textarea id="chatbox-input" placeholder="Nhập tin nhắn của bạn..." rows="1"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl resize-none outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                            style="max-height: 120px;"></textarea>
                    </div>
                    <button id="chatbox-send"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-3 rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>


    {{-- Footer --}}
    @include('client.partials.footer')

    <!-- Thêm script này cuối cùng -->
    <script>
        class SmartCareChat {
            constructor() {
                this.sessionId = localStorage.getItem('chat_session_id');
                this.isTyping = false;
                this.hasShownServices = localStorage.getItem('hasShownServices') === 'true';
                this.echoChannel = null; // ✅ Lưu reference channel
                this.init();
            }

            init() {
                this.bindEvents();
                this.autoResizeTextarea();
                this.loadSavedServices();

                // ✅ Debug Echo
                this.debugEcho();

                if (this.sessionId) {
                    this.loadMessages();
                    // ✅ Delay để đảm bảo Echo sẵn sàng
                    setTimeout(() => {
                        this.listenForMessages();
                    }, 500);
                } else {
                    this.startSession();
                }
            }

            debugEcho() {
                console.log('🔍 Echo available:', typeof window.Echo);
                console.log('🔍 Pusher available:', typeof window.Pusher);

                if (window.Echo && window.Echo.connector) {
                    console.log('🔍 Pusher state:', window.Echo.connector.pusher.connection.state);

                    // Debug connection events
                    window.Echo.connector.pusher.connection.bind('connected', () => {
                        console.log('✅ Pusher connected successfully');
                    });

                    window.Echo.connector.pusher.connection.bind('error', (err) => {
                        console.error('❌ Pusher connection error:', err);
                    });
                }
            }

            bindEvents() {
                document.getElementById('chatbox-button')?.addEventListener('click', () => {
                    this.toggleChat();
                });

                document.getElementById('chatbox-close')?.addEventListener('click', () => {
                    this.closeChat();
                });

                document.getElementById('chatbox-send')?.addEventListener('click', () => {
                    this.sendMessage();
                });

                document.querySelectorAll('.quick-action-btn').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        const message = e.target.dataset.message;
                        document.getElementById('chatbox-input').value = message;
                        this.sendMessage();
                    });
                });

                document.getElementById('chatbox-input')?.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        this.sendMessage();
                    }
                });
            }

            autoResizeTextarea() {
                const textarea = document.getElementById('chatbox-input');
                if (!textarea) return;
                textarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = Math.min(this.scrollHeight, 120) + 'px';
                });
            }

            toggleChat() {
                const modal = document.getElementById('chatbox-modal');
                modal.classList.toggle('hidden');
                if (!modal.classList.contains('hidden')) {
                    modal.classList.add('animate__animated', 'animate__slideInUp');
                    document.getElementById('chatbox-input')?.focus();
                }
            }

            closeChat() {
                const modal = document.getElementById('chatbox-modal');
                modal.classList.add('hidden');
                modal.classList.remove('animate__slideInUp');
            }

            async startSession(resetServices = false) {
                try {
                    const response = await fetch('/chat/start', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            session_id: this.sessionId
                        })
                    });

                    const data = await response.json();
                    if (data.success) {
                        this.sessionId = data.session.session_id;
                        localStorage.setItem('chat_session_id', this.sessionId);

                        if (resetServices) {
                            localStorage.removeItem('hasShownServices');
                            localStorage.removeItem('lastSuggestedServices');
                            this.hasShownServices = false;
                        }

                        this.displayMessages(data.messages);

                        // ✅ Setup listening sau khi có sessionId
                        setTimeout(() => {
                            this.listenForMessages();
                        }, 500);
                    }
                } catch (error) {
                    console.error('Error starting chat session:', error);
                }
            }

            listenForMessages() {
                if (!this.sessionId) {
                    console.warn('⚠️ Không có sessionId để listen');
                    return;
                }

                // ✅ Kiểm tra Echo có sẵn không
                if (!window.Echo) {
                    console.error('❌ Window.Echo chưa được khởi tạo');
                    return;
                }

                console.log(`🎧 Đang setup listener cho channel: chat-session-${this.sessionId}`);

                try {
                    // ✅ Hủy channel cũ nếu có
                    if (this.echoChannel) {
                        console.log('🔄 Hủy channel cũ');
                        window.Echo.leaveChannel(`private-chat-session-${this.sessionId}`);
                    }

                    // ✅ Tạo channel mới
                    this.echoChannel = window.Echo.private(`chat-session-${this.sessionId}`);

                    this.echoChannel
                        .listen('.chat-message-sent', (e) => {
                            console.log('📩 RECEIVED MESSAGE:', e.message);
                            console.log('📩 Full event:', e);

                            // ✅ Method 1: Direct DOM manipulation (guaranteed to work)
                            const chatContent = document.getElementById('chatbox-content');
                            if (chatContent) {
                                chatContent.innerHTML += `
                            <div style="background: #dcfce7; padding: 15px; margin: 10px 0; border-radius: 12px; border-left: 4px solid #22c55e;">
                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                    <div style="width: 32px; height: 32px; background: #22c55e; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 14px;">
                                        👨‍💼
                                    </div>
                                    <strong style="color: #166534;">Nhân viên hỗ trợ</strong>
                                </div>
                                <p style="margin: 0; color: #166534; font-size: 14px; line-height: 1.5;">${this.escapeHtml(e.message)}</p>
                                <small style="color: #22c55e; font-size: 11px;">vừa xong</small>
                            </div>
                        `;

                                // Scroll to bottom
                                chatContent.scrollTop = chatContent.scrollHeight;

                                console.log('✅ Admin message added to UI successfully');
                            } else {
                                console.error('❌ chatContent not found');
                            }

                            // ✅ Method 2: Also try the original method (for debugging)
                            try {
                                this.addMessageRealtime(e.message, 'admin');
                            } catch (error) {
                                console.error('❌ addMessageRealtime failed:', error);
                            }
                        })
                        .subscribed(() => {
                            console.log('✅ Successfully subscribed to channel:', `chat-session-${this.sessionId}`);
                        })
                        .error((error) => {
                            console.error('❌ Channel subscription error:', error);
                        });

                } catch (error) {
                    console.error('❌ Error setting up channel listener:', error);
                }
            }

            async sendMessage() {
                const input = document.getElementById('chatbox-input');
                const message = input.value.trim();

                if (!message || this.isTyping) return;

                input.value = '';
                input.style.height = 'auto';
                this.addMessage(message, 'user');
                this.showTyping();

                try {
                    const response = await fetch('/chat/send', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            session_id: this.sessionId,
                            message: message
                        })
                    });

                    const data = await response.json();
                    this.hideTyping();

                    if (data.success) {
                        this.displayMessages(data.messages);
                    } else {
                        this.addMessage('Có lỗi xảy ra, vui lòng thử lại sau.', 'bot');
                    }
                } catch (error) {
                    this.hideTyping();
                    this.addMessage('Kết nối mạng bị lỗi, vui lòng thử lại.', 'bot');
                    console.error('Error sending message:', error);
                }
            }

            showTyping() {
                this.isTyping = true;
                document.getElementById('typing-indicator')?.classList.remove('hidden');
                this.scrollToBottom();
            }

            hideTyping() {
                this.isTyping = false;
                document.getElementById('typing-indicator')?.classList.add('hidden');
            }

            // ✅ Method riêng để thêm message realtime (không cần metadata)
            // addMessageRealtime(message, sender) {
            //     console.log('🎯 Adding realtime message:', message, sender);

            //     const chatContent = document.getElementById('chatbox-content');
            //     if (!chatContent) {
            //         console.error('❌ Không tìm thấy chatbox-content');
            //         return;
            //     }

            //     // ✅ Test với HTML đơn giản trước
            //     const messageDiv = document.createElement('div');
            //     messageDiv.style.cssText =
            //         'margin: 10px 0; padding: 10px; background: #e8f5e8; border-radius: 8px; border: 2px solid #4ade80;';

            //     if (sender === 'admin') {
            //         messageDiv.innerHTML = `
        //     <div style="display: flex; align-items: center; gap: 8px;">
        //         <div style="width: 30px; height: 30px; background: #22c55e; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px;">
        //             👨‍💼
        //         </div>
        //         <div>
        //             <div style="background: #dcfce7; padding: 8px 12px; border-radius: 12px;">
        //                 <p style="margin: 0; font-size: 14px; color: #166534;">${this.escapeHtml(message || '')}</p>
        //                 <p style="margin: 4px 0 0 0; font-size: 11px; color: #22c55e;">Nhân viên hỗ trợ • vừa xong</p>
        //             </div>
        //         </div>
        //     </div>
        // `;
            //     } else {
            //         messageDiv.innerHTML = `
        //     <div style="display: flex; justify-content: flex-end;">
        //         <div style="background: #3b82f6; color: white; padding: 8px 12px; border-radius: 12px; max-width: 80%;">
        //             <p style="margin: 0; font-size: 14px;">${this.escapeHtml(message)}</p>
        //         </div>
        //     </div>
        // `;
            //     }

            //     console.log('🔍 About to append message div:', messageDiv);
            //     console.log('🔍 Current chatContent children:', chatContent.children.length);

            //     chatContent.appendChild(messageDiv);

            //     console.log('🔍 After append children:', chatContent.children.length);
            //     console.log('✅ Message added to UI successfully');

            //     // ✅ Force scroll
            //     setTimeout(() => {
            //         chatContent.scrollTop = chatContent.scrollHeight;
            //         console.log('📜 Scrolled to bottom');
            //     }, 100);
            // }

            addMessage(message, sender, metadata = null, allMessages = []) {
                const chatContent = document.getElementById('chatbox-content');
                const messageDiv = document.createElement('div');

                // Kiểm tra nếu là tin nhắn cảm ơn và đã gửi hôm nay thì bỏ qua
                if (sender === 'bot' && message.includes("Cảm ơn bạn đã liên hệ")) {
                    const lastThanks = localStorage.getItem('lastThanksDate');
                    const today = new Date().toISOString().split('T')[0]; // yyyy-mm-dd

                    if (lastThanks === today) {
                        console.log('⏩ Đã gửi tin nhắn cảm ơn hôm nay, bỏ qua.');
                        return;
                    } else {
                        localStorage.setItem('lastThanksDate', today);
                    }
                }

                if (sender === 'user') {
                    messageDiv.innerHTML = `
                <div class="flex justify-end mb-4">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-3 rounded-2xl rounded-br-sm max-w-[80%]">
                        <p class="text-sm">${this.escapeHtml(message)}</p>
                    </div>
                </div>
            `;
                } else if (sender === 'admin') {
                    // ✅ Style cho admin message
                    messageDiv.innerHTML = `
                <div class="flex items-start gap-3 mb-4">
                    <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user-tie text-white text-xs"></i>
                    </div>
                    <div class="max-w-[80%]">
                        <div class="bg-green-100 p-3 rounded-2xl rounded-tl-sm">
                            <p class="text-sm text-gray-800">${this.formatMessage(message || '')}</p>
                            <p class="text-xs text-green-600 mt-1">Nhân viên hỗ trợ</p>
                        </div>
                    </div>
                </div>
            `;
                } else {
                    // Bot message
                    let suggestedServices = '';

                    if (metadata?.suggested_services && !this.hasShownServices) {
                        const lastTwo = allMessages.slice(-2);
                        const adminReplied = lastTwo.some(m => m.sender_type === 'admin');

                        if (!adminReplied) {
                            suggestedServices = this.renderSuggestedServices(metadata.suggested_services);
                            localStorage.setItem('lastSuggestedServices', JSON.stringify(metadata.suggested_services));
                            this.hasShownServices = true;
                            localStorage.setItem('hasShownServices', 'true');
                        }
                    }

                    messageDiv.innerHTML = `
                <div class="flex items-start gap-3 mb-4">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-robot text-white text-xs"></i>
                    </div>
                    <div class="max-w-[80%]">
                        <div class="bg-gray-100 p-3 rounded-2xl rounded-tl-sm">
                            <p class="text-sm text-gray-800">${this.formatMessage(message || '')}</p>
                        </div>
                        ${suggestedServices}
                    </div>
                </div>
            `;
                }

                chatContent.appendChild(messageDiv);
                this.scrollToBottom();
            }

            loadSavedServices() {
                if (localStorage.getItem('adminHasReplied') === 'true') return;

                const saved = localStorage.getItem('lastSuggestedServices');
                if (saved) {
                    const services = JSON.parse(saved);
                    const chatContent = document.getElementById('chatbox-content');
                    const html = this.renderSuggestedServices(services);
                    chatContent.innerHTML += html;
                }
            }

            renderSuggestedServices(services) {
                if (!services || services.length === 0) return '';

                let html = '<div class="mt-3 space-y-2">';
                services.forEach(service => {
                    let id, name, price, image;
                    if (typeof service === 'number') {
                        id = service;
                        name = `Dịch vụ #${service}`;
                        price = '';
                        image = null;
                    } else {
                        id = service.id;
                        name = service.name;
                        price = service.price;
                        image = service.image;
                    }

                    html += `
                <div class="bg-white border border-gray-200 rounded-lg p-3 hover:shadow-md transition cursor-pointer" onclick="window.location.href='/chi-tiet-dich-vu/${id}'">
                    <div class="flex items-center gap-3">
                        ${image ? `<img src="${image}" alt="${name}" class="w-12 h-12 object-cover rounded-lg">` : ''}
                        <div class="flex-1">
                            <h4 class="font-medium text-sm text-gray-800">${name}</h4>
                            <p class="text-blue-600 font-semibold text-sm">${price ? this.formatPrice(price) : ''}</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                    </div>
                </div>
            `;
                });
                html += '</div>';
                return html;
            }

            displayMessages(messages) {
                console.log("Messages từ server:", messages);

                const adminHasReplied = messages.some(m => m.sender_type === 'admin');

                if (adminHasReplied) {
                    localStorage.setItem('adminHasReplied', 'true');
                    localStorage.removeItem('lastSuggestedServices');
                    localStorage.removeItem('hasShownServices');
                    this.hasShownServices = true;
                } else {
                    localStorage.removeItem('adminHasReplied');
                }

                const chatContent = document.getElementById('chatbox-content');
                const welcomeMsg = chatContent.firstElementChild;
                chatContent.innerHTML = '';
                if (welcomeMsg) chatContent.appendChild(welcomeMsg);

                messages.forEach(msg => {
                    if (
                        msg.sender_type === 'bot' &&
                        !adminHasReplied &&
                        !this.hasShownServices &&
                        (msg.metadata?.services || msg.metadata?.suggested_services)
                    ) {
                        const services = msg.metadata.services || msg.metadata.suggested_services;
                        this.hasShownServices = true;
                        localStorage.setItem('hasShownServices', 'true');
                        localStorage.setItem('lastSuggestedServices', JSON.stringify(services));

                        const html = this.renderSuggestedServices(services);
                        chatContent.innerHTML += html;
                    } else {
                        this.addMessage(msg.message, msg.sender_type, msg.metadata, messages);
                    }
                });
            }

            async loadMessages() {
                try {
                    const response = await fetch(`/chat/messages?session_id=${this.sessionId}`);
                    const data = await response.json();
                    if (data.success && data.messages.length > 0) {
                        this.displayMessages(data.messages);
                    }
                } catch (error) {
                    console.error('Error loading messages:', error);
                }
            }

            formatMessage(message) {
                return message.replace(/\n/g, '<br>');
            }

            formatPrice(price) {
                if (!price) return 'Liên hệ';
                return new Intl.NumberFormat('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                }).format(price);
            }

            escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            scrollToBottom() {
                const chatContent = document.getElementById('chatbox-content');
                setTimeout(() => {
                    chatContent.scrollTop = chatContent.scrollHeight;
                }, 100);
            }
        }

        // ✅ Debug Echo trước khi khởi tạo
        document.addEventListener("DOMContentLoaded", function() {
            console.log('🚀 DOM loaded, khởi tạo SmartCareChat...');

            // Debug Echo connection
            if (window.Echo && window.Echo.connector) {
                console.log('✅ Echo available, Pusher state:', window.Echo.connector.pusher.connection.state);

                // Wait for connection if not ready
                if (window.Echo.connector.pusher.connection.state !== 'connected') {
                    console.log('⏳ Waiting for Pusher connection...');
                    window.Echo.connector.pusher.connection.bind('connected', () => {
                        console.log('✅ Pusher connected, initializing chat...');
                        new SmartCareChat();
                    });
                } else {
                    new SmartCareChat();
                }
            } else {
                console.warn('⚠️ Echo not available, initializing anyway...');
                new SmartCareChat();
            }
        });
    </script>

    <!-- Đặt sau lucide -->

    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.3/dist/js/splide.min.js"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- AlpineJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cấu hình Toastr
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "5000"
            };

            // Hiển thị thông báo session
            @if (session('success'))
                toastr.success("{{ session('success') }}");
            @endif
            @if (session('error'))
                toastr.error("{!! session('error') !!}");
            @endif
            @if (session('warning'))
                toastr.warning("{{ session('warning') }}", "Cảnh báo");
            @endif
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error("{!! $error !!}");
                @endforeach
            @endif

            // Các mã khác như Splide, counter animation...
            new Splide('#testimonial-slider', {
                type: 'loop',
                perPage: 3,
                autoplay: true,
                interval: 3000,
                pauseOnHover: true,
                pauseOnFocus: true,
                gap: '1rem',
                breakpoints: {
                    1024: {
                        perPage: 2
                    },
                    640: {
                        perPage: 1
                    }
                }
            }).mount();

            new Splide('#doctor-slider', {
                type: 'loop',
                perPage: 4,
                autoplay: true,
                interval: 3000,
                pauseOnHover: true,
                pauseOnFocus: true,
                gap: '1rem',
                breakpoints: {
                    1024: {
                        perPage: 2
                    },
                    640: {
                        perPage: 1
                    }
                }
            }).mount();

            // Animation Count Number
            const counters = document.querySelectorAll(".counter");

            function animateCounter(counter) {
                const target = +counter.getAttribute("data-number");
                const step = +counter.getAttribute("data-step") || 1;
                const duration = 1500;
                const incrementTime = Math.max(duration / (target / step), 10);
                let current = 0;

                counter.textContent = "0";
                const run = () => {
                    current += step;
                    if (current >= target) {
                        counter.textContent = target;
                    } else {
                        counter.textContent = current;
                        setTimeout(run, incrementTime);
                    }
                };
                run();
            }

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        animateCounter(entry.target);
                    }
                });
            }, {
                threshold: 0.6
            });

            counters.forEach(counter => {
                observer.observe(counter);
            });
        });
    </script>
    @stack('scripts')

</body>

</html>
