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
        textarea.addEventListener('input', function () {
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

document.addEventListener("DOMContentLoaded", function () {
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