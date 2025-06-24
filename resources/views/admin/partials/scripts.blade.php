<!-- jQuery và phụ thuộc cốt lõi -->
{{-- <script src="{{ asset('admin/assets/vendor/libs/jquery/jquery.js') }}"></script> --}}
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Bootstrap (chỉ giữ nếu không dùng CDN trong dashboard.blade.php) -->
{{-- <script src="{{ asset('admin/assets/vendor/js/bootstrap.js') }}"></script> --}}

<!-- Các thư viện phụ thuộc jQuery -->
<script src="{{ asset('admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('admin/assets/vendor/libs/hammer/hammer.js') }}"></script>
<script src="{{ asset('admin/assets/vendor/libs/i18n/i18n.js') }}"></script>
<script src="{{ asset('admin/assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
<script src="{{ asset('admin/assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('admin/assets/vendor/libs/select2/select2.js') }}"></script>
<script src="{{ asset('admin/assets/vendor/libs/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('admin/assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>

<!-- Script theme -->
<script src="{{ asset('admin/assets/vendor/js/menu.js') }}"></script>
<script src="{{ asset('admin/assets/js/main.js') }}"></script>
<script src="{{ asset('admin/assets/js/dashboards-analytics.js') }}"></script>

<!-- Trong scripts.blade.php -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- Thư viện bổ sung -->
<script src="{{ asset('admin/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>