<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Doctor Dashboard')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    @include('doctor.partials.header')

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            overflow: hidden; /* Prevent body scroll, let specific divs handle it */
        }

        .layout-wrapper {
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .layout-container {
            display: flex;
            flex: 1; /* Allow container to take available height */
            overflow: hidden; /* Hide scrollbars, child elements handle scrolling */
        }

        .layout-menu.menu-vertical {
            height: 100vh; /* Sidebar takes full viewport height */
            flex-shrink: 0;
            overflow-y: auto; /* Enable scrolling for sidebar content */
            width: 260px; /* Default sidebar width */
            transition: all 0.3s ease; /* Smooth transition for sidebar toggle */
            background-color: #f8f9fa; /* Example background */
            border-right: 1px solid #e9ecef;
        }

        .layout-menu.menu-vertical.hide-menu {
            margin-left: -260px; /* Hide sidebar off-screen */
        }

        .layout-page {
            display: flex;
            flex-direction: column;
            flex-grow: 1; /* Allow page content to take remaining width */
            overflow: hidden; /* Hide scrollbars, child elements handle scrolling */
        }

        .content-wrapper {
            flex-grow: 1;
            overflow: hidden; /* Hide scrollbars, let container-p-y handle */
            display: flex;
            flex-direction: column;
        }

        .container-xxl.container-p-y {
            flex-grow: 1;
            overflow-y: auto; /* Specific content area scrolls */
            padding: 1.5rem;
        }

        .content-footer.footer {
            flex-shrink: 0;
            padding: 1rem 1.5rem;
            background-color: #f8f9fa;
            border-top: 1px solid #e9ecef;
        }

        /* Responsive Adjustments */
        @media (max-width: 991.98px) { /* Medium devices (tablets, 768px and up) */
            .layout-menu.menu-vertical {
                position: fixed; /* Make sidebar fixed to allow toggle */
                top: 0;
                left: 0;
                z-index: 1050; /* Ensure it's above other content */
                transform: translateX(-100%); /* Start hidden */
                width: 260px;
                height: 100vh;
                background-color: #f8f9fa;
            }

            .layout-menu.menu-vertical.show-menu {
                transform: translateX(0%); /* Show sidebar */
            }

            .layout-page {
                margin-left: 0 !important; /* Reset margin on smaller screens */
                width: 100%; /* Take full width */
            }

            .layout-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1040;
                display: none; /* Hidden by default */
            }
            .layout-overlay.show {
                display: block; /* Show when sidebar is open */
            }

            .navbar .navbar-toggler {
                display: block; /* Show hamburger icon */
            }
        }

        @media (min-width: 992px) { /* Large devices (desktops, 992px and up) */
            .layout-menu.menu-vertical {
                transform: translateX(0%); /* Ensure sidebar is visible */
            }
            .navbar .navbar-toggler {
                display: none; /* Hide hamburger icon on desktop */
            }
            .layout-overlay {
                display: none !important; /* Ensure overlay is hidden */
            }
        }
    </style>
</head>
<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('doctor.partials.sidebar')
            <div class="layout-page">
                @include('doctor.partials.navbar')
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                    </div>
                    @include('doctor.partials.footer')
                    <div class="layout-overlay" onclick="toggleSidebar()"></div> </div>
                </div>
            </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    @include('doctor.partials.scripts')

    <script>
        // Function to toggle sidebar visibility and overlay on mobile
        function toggleSidebar() {
            const sidebar = document.querySelector('.layout-menu.menu-vertical');
            const overlay = document.querySelector('.layout-overlay');
            sidebar.classList.toggle('show-menu');
            overlay.classList.toggle('show');
        }

        $(document).ready(function() {
            @if (session('success'))
                toastr.success("{{ session('success') }}");
            @endif
            @if (session('error'))
                toastr.error("{{ session('error') }}");
            @endif
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error("{{ $error }}");
                @endforeach
            @endif
            @if (session('date_swapped'))
                toastr.warning("Ngày bắt đầu lớn hơn ngày kết thúc. Hệ thống đã tự động hoán đổi giúp bạn.",
                    "Cảnh báo");
            @endif
        });
    </script>
    @stack('scripts')
    @yield('scripts')
</body>
</html>
