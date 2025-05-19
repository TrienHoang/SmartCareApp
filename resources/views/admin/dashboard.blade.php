<!DOCTYPE html>
<html lang="vi">
<head>
    @include('admin.partials.header')
</head>
<body>
  <!-- Google Tag Manager noscript -->
  <noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5DDHKGP" height="0" width="0" style="display: none; visibility: hidden"></iframe>
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
</body>
</html>
