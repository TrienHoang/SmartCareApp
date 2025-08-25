$(document).ready(function () {
    // Toastr
    if (typeof toastr !== 'undefined') {
        if (window._toastrSuccess) {
            toastr.success(window._toastrSuccess);
        }
        if (window._toastrError) {
            toastr.error(window._toastrError);
        }
        if (window._toastrErrors && Array.isArray(window._toastrErrors)) {
            window._toastrErrors.forEach(e => toastr.error(e));
        }
        if (window._toastrDateSwapped) {
            toastr.warning("Ngày bắt đầu lớn hơn ngày kết thúc. Hệ thống đã tự động hoán đổi giúp bạn.", "Cảnh báo");
        }
    }

    // Select2
    if ($('.select2').length && typeof $.fn.select2 !== 'undefined') {
        $('.select2').select2();
    }

    // flatpickr
    if ($('.flatpickr').length && typeof flatpickr !== 'undefined') {
        $('.flatpickr').flatpickr();
    }

    // TinyMCE
    if ($('textarea.tinymce').length && typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: 'textarea.tinymce',
            license_key: 'gpl',
            plugins: 'link image code',
            toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | code'
        });
    }
});
