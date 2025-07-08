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

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .active-link {
            font-weight: 600;
            color: #2563eb;
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
    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')

</body>

</html>
