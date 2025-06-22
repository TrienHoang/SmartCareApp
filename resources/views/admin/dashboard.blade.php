<!DOCTYPE html>
<html lang="vi">
<head>
    {{-- CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

    @include('admin.partials.header')

    {{-- Layout style --}}
    <style>
        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden;
        }
        .layout-wrapper { height: 100vh; display: flex; flex-direction: column; }
        .layout-container { display: flex; flex: 1; overflow: hidden; }
        .layout-menu.menu-vertical { height: 100vh; flex-shrink: 0; overflow-y: auto; }
        .layout-page { display: flex; flex-direction: column; flex-grow: 1; overflow: hidden; }
        .content-wrapper { flex-grow: 1; overflow: hidden; display: flex; flex-direction: column; }
        .container-xxl.container-p-y { flex-grow: 1; overflow-y: auto; padding: 1.5rem; }
        .content-footer.footer { flex-shrink: 0; }
    </style>
</head>

<body>
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5DDHKGP" height="0" width="0" style="display: none; visibility: hidden"></iframe>
    </noscript>

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

    {{-- Scripts --}}
    @include('admin.partials.scripts')

    {{-- Toastr / Errors / Notifications --}}
    <script>
        window._toastrSuccess = {!! json_encode(session('success')) !!};
        window._toastrError = {!! json_encode(session('error')) !!};
        window._toastrDateSwapped = {!! json_encode(session('date_swapped')) !!};
        window._toastrErrors = {!! json_encode($errors->all()) !!};
    </script>

    @stack('scripts')
    @yield('scripts')
</body>
</html>
