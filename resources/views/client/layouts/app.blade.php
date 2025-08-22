<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->id() }}">
    <title>@yield('title', 'SmartCare - Hệ thống Y tế')</title>
    <link rel="icon" href="{{ asset('path/to/your-icon.png') }}" type="image/png">
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
    <!-- Phải có trong <head> -->

    @auth
        <meta name="user-id" content="{{ auth()->id() }}">
    @endauth
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

        .article-content {
            /* Reset tất cả về mặc định của browser */
            all: revert;

            /* Cho phép text tự động xuống dòng */
            white-space: normal;

            /* Fix mấy chỗ bị bootstrap override */
            font-size: 1rem;
            line-height: 1.6;
            color: #222;
        }

        /* Nếu muốn format lại heading */
        .article-content h1,
        .article-content h2,
        .article-content h3 {
            margin: 1em 0 0.5em;
            font-weight: bold;
        }

        /* Paragraph */
        .article-content p {
            margin: 0 0 1em;
        }

        /* List */
        .article-content ul,
        .article-content ol {
            padding-left: 2em;
            margin: 0 0 1em;
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

    @auth
        <a href="{{ route('client.notifications.index') }}">
            <div id="notification-button"
                class="fixed bottom-[120px] right-6 z-50 bg-gradient-to-r from-blue-600 to-blue-700 
           hover:from-blue-700 hover:to-blue-800 text-white 
           w-16 h-16 rounded-full shadow-2xl flex items-center justify-center 
           cursor-pointer transition-all duration-300 transform hover:scale-105">

                {{-- Icon chuông --}}
                <i class="fas fa-bell text-2xl"></i>

                {{-- Badge đỏ hiển thị số lượng --}}
                <span id="notification-badge"
                    class="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center
               {{ $unreadNotificationsCount > 0 ? '' : 'hidden' }}">
                    {{ $unreadNotificationsCount }}
                </span>
            </div>
        </a>
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

                        <!-- Nút Emoji -->
                        <button id="emoji-button"
                            class="bg-gray-100 text-gray-600 p-3 rounded-xl hover:bg-gray-200 transition flex items-center justify-center">
                            <i class="fas fa-smile"></i>
                        </button>

                        <!-- Nút gửi -->
                        <button id="chatbox-send"
                            class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-3 rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endauth
    <!-- Emoji Picker Modal (Tailwind) -->
    <div id="emojiModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl shadow-lg p-4 w-64">
            <div class="flex justify-between items-center mb-3">
                <h5 class="font-semibold">Chọn Emoji</h5>
                <button id="emoji-close" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>
            <div class="grid grid-cols-8 gap-2 text-xl">
                <span class="emoji-item cursor-pointer">😀</span>
                <span class="emoji-item cursor-pointer">😊</span>
                <span class="emoji-item cursor-pointer">👍</span>
                <span class="emoji-item cursor-pointer">❤️</span>
                <span class="emoji-item cursor-pointer">😢</span>
                <span class="emoji-item cursor-pointer">😮</span>
                <span class="emoji-item cursor-pointer">🙏</span>
                <span class="emoji-item cursor-pointer">✨</span>
            </div>
        </div>
    </div>


    {{-- Footer --}}
    @include('client.partials.footer')

    <!-- Thêm script này cuối cùng -->
    <script src="{{ asset('js/chatbox.js') }}"></script>

    <!-- Đặt sau lucide -->

    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.3/dist/js/splide.min.js"></script>
    <script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>

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

            const emojiBtn = document.getElementById("emoji-button");
            const emojiModal = document.getElementById("emojiModal");
            const emojiClose = document.getElementById("emoji-close");
            const chatInput = document.getElementById("chatbox-input");

            if (emojiBtn && emojiModal && chatInput) {
                // mở modal
                emojiBtn.addEventListener("click", function() {
                    emojiModal.classList.remove("hidden");
                });

                // đóng modal
                emojiClose.addEventListener("click", function() {
                    emojiModal.classList.add("hidden");
                });

                // bấm emoji -> chèn vào input + ẩn modal
                document.querySelectorAll(".emoji-item").forEach(el => {
                    el.addEventListener("click", function() {
                        chatInput.value += el.textContent;
                        emojiModal.classList.add("hidden");
                        chatInput.focus();
                    });
                });

                // đóng khi click outside
                emojiModal.addEventListener("click", function(e) {
                    if (e.target === emojiModal) {
                        emojiModal.classList.add("hidden");
                    }
                });
            }

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
                arrows: false, // Ẩn nút next/prev
                pagination: false, // Ẩn dots
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
                arrows: false, // Ẩn nút next/prev
                pagination: false, // Ẩn dots
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
    <script>
        window.isLoggedIn = @json(Auth::check());
    </script>
    @stack('scripts')

</body>

</html>
