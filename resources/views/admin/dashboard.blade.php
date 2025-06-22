<!DOCTYPE html>
<html lang="vi">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    @include('admin.partials.header')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            overflow: hidden;
            /* ❗Không để body scroll */
        }

        .layout-wrapper {
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .layout-container {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        .layout-menu.menu-vertical {
            height: 100vh;
            flex-shrink: 0;
            overflow-y: auto;
        }

        .layout-page {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            overflow: hidden;
        }

        .content-wrapper {
            flex-grow: 1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .container-xxl.container-p-y {
            flex-grow: 1;
            overflow-y: auto;
            /* ✅ Content scroll riêng */
            padding: 1.5rem;
        }

        .content-footer.footer {
            flex-shrink: 0;
        }
    </style>

</head>

<body>
    <!-- Google Tag Manager noscript -->
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5DDHKGP" height="0" width="0"
            style="display: none; visibility: hidden"></iframe>
    </noscript>
    <!-- End Google Tag Manager noscript -->

    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('admin.partials.sidebar')

            <div class="layout-page">
                @include('admin.partials.navbar')

                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                    </div>

                    @include('admin.partials.footer')
                </div>
            </div>
        </div>
    </div>

    @include('admin.partials.scripts')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
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
