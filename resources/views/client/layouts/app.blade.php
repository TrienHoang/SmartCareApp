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

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Các tiện ích có thể tái sử dụng */
        .active-link {
            font-weight: 600;
            color: #2563eb;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    <!-- Header -->
    @include('client.partials.header')

    <!-- Main Content -->
    <main class="flex-1 container mx-auto px-4 py-6">
        @yield('content')
    </main>

    <!-- Footer -->
    @includeIf('client.partials.footer')

    @stack('scripts')
</body>
</html>
