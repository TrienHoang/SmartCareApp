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

    <div id="chatbox-button"
        class="fixed bottom-6 right-6 z-50 bg-blue-600 hover:bg-blue-700 text-white w-14 h-14 rounded-full shadow-lg flex items-center justify-center cursor-pointer transition">
        <i class="fas fa-comment-dots text-2xl"></i>
    </div>

    <!-- Chatbox UI -->
    <div id="chatbox-modal"
        class="fixed bottom-24 right-6 w-80 max-w-[90vw] bg-white rounded-xl shadow-xl border hidden z-50 overflow-hidden">
        <!-- Header -->
        <div class="bg-blue-500 text-white px-4 py-3 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i class="fas fa-user-md text-lg"></i>
                <span class="font-semibold text-sm">Trợ lý Y tế</span>
            </div>
            <button id="chatbox-close" class="hover:text-gray-200 text-lg">&times;</button>
        </div>

        <!-- Chat content -->
        <div class="p-4 space-y-3 text-sm max-h-96 overflow-y-auto" id="chatbox-content">
            <div class="bg-gray-100 p-3 rounded-lg text-gray-700">
                Xin chào 👋<br>Bạn cần đặt lịch khám hay tư vấn gì ạ?
            </div>
        </div>

        <!-- Input -->
        <div class="px-4 pb-4">
            <div
                class="flex items-center border rounded-lg overflow-hidden focus-within:ring-2 focus-within:ring-blue-400">
                <input id="chatbox-input" type="text" placeholder="Nhập tin nhắn..."
                    class="w-full px-3 py-2 outline-none text-sm" />
                <button id="chatbox-send" class="bg-blue-500 text-white px-4 py-2 hover:bg-blue-600 transition">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>


    {{-- Footer --}}
    @include('client.partials.footer')

    <!-- Thêm script này cuối cùng -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const chatBtn = document.getElementById("chatbox-button");
            const chatModal = document.getElementById("chatbox-modal");
            const closeChat = document.getElementById("chatbox-close");
            const chatInput = document.getElementById("chatbox-input");
            const chatSend = document.getElementById("chatbox-send");
            const chatContent = document.getElementById("chatbox-content");

            let lastSender = null;

            chatBtn?.addEventListener("click", () => {
                chatModal?.classList.toggle("hidden");
            });

            closeChat?.addEventListener("click", () => {
                chatModal?.classList.add("hidden");
            });

            function appendMessage(content, from = "user") {
                const wrapper = document.createElement("div");
                wrapper.className = "flex mb-3 " + (from === "user" ? "justify-end" : "justify-start");

                const bubbleContainer = document.createElement("div");
                bubbleContainer.className = "max-w-[75%]";

                if (lastSender !== from) {
                    const meta = document.createElement("div");
                    meta.className = "flex items-center gap-2 text-xs text-gray-500 mb-1";

                    const icon = document.createElement("span");
                    icon.textContent = from === "user" ? "👤" : "🤖";

                    const name = document.createElement("span");
                    name.textContent = from === "user" ? "Bạn" : "Trợ lý Y tế";

                    meta.appendChild(icon);
                    meta.appendChild(name);
                    bubbleContainer.appendChild(meta);

                    lastSender = from;
                }

                const bubble = document.createElement("div");
                bubble.className = "px-4 py-2 text-sm rounded-xl";
                bubble.classList.add(
                    ...(from === "user" ?
                        ["bg-blue-100", "text-gray-900", "rounded-br-none"] :
                        ["bg-gray-100", "text-gray-800", "rounded-bl-none"])
                );
                bubble.textContent = content;

                bubbleContainer.appendChild(bubble);
                wrapper.appendChild(bubbleContainer);
                chatContent.appendChild(wrapper);
                chatContent.scrollTop = chatContent.scrollHeight;
            }

            chatSend?.addEventListener("click", () => {
                const message = chatInput.value.trim();
                if (!message) return;

                appendMessage(message, "user");
                chatInput.value = "";

                setTimeout(() => {
                    appendMessage("Cảm ơn bạn! Chúng tôi sẽ liên hệ để xác nhận lịch hẹn sớm nhất.",
                        "bot");
                }, 700);
            });

            chatInput?.addEventListener("keydown", e => {
                if (e.key === "Enter") chatSend.click();
            });
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
