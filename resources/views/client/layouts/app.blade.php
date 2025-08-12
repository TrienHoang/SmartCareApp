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
    <script src="{{ asset('js/chatbox.js') }}"></script>

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
