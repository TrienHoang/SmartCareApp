<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    {{-- Footer --}}
    @include('client.partials.footer')

    <!-- Đặt sau lucide -->

    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.3/dist/js/splide.min.js"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- AlpineJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        lucide.createIcons();
        document.addEventListener('DOMContentLoaded', function() {
            new Splide('#testimonial-slider', {
                type: 'loop',
                perPage: 3,
                autoplay: true,
                interval: 3000, // 3 giây
                pauseOnHover: true,
                pauseOnFocus: true,
                gap: '1rem',
                breakpoints: {
                    1024: {
                        perPage: 2
                    },
                    640: {
                        perPage: 1
                    },
                }
            }).mount();

            // doctor slider
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
                    },
                }
            }).mount();

            $(document).ready(function() {
                @if (session('success'))
                    toastr.success("{{ session('success') }}");
                @endif
                @if (session('error'))
                    toastr.error("{{ session('error') }}");
                @endif
                @if (session('warning'))
                    toastr.warning("{{ session('warning') }}", "Cảnh báo");
                @endif
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        toastr.error("{{ $error }}");
                    @endforeach
                @endif
                @if (session('date_swapped'))
                    toastr.warning(
                        "Ngày bắt đầu lớn hơn ngày kết thúc. Hệ thống đã tự động hoán đổi giúp bạn.",
                        "Cảnh báo");
                @endif
            });

            toastr.success('Toastr hoạt động ngon lành rồi nè!');
            // animation Count Number hẹ hẹ hẹ

            const counters = document.querySelectorAll(".counter");

            function animateCounter(counter) {
                const target = +counter.getAttribute("data-number");
                const step = +counter.getAttribute("data-step") || 1;
                const duration = 1500; // thời gian tổng cộng
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
