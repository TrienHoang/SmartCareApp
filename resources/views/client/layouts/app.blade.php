<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SmartCare - Hệ thống Y tế')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.3/dist/css/splide.min.css">

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
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.3/dist/js/splide.min.js"></script>
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

            // banner slider
            new Splide('#hero-slider', {
                type: 'fade',
                rewind: true,
                autoplay: true,
                interval: 5000,
                pauseOnHover: false,
                arrows: false,
                pagination: true,
            }).mount();

            if (window.lucide) {
                lucide.createIcons();
            }
        });
    </script>
    @stack('scripts')

</body>

</html>
