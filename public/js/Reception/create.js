$(document).ready(function () {
    const $input = $('#patient_name');
    const $hidden = $('#patient_id_hidden');
    const $doctor = $('#doctor_id');
    const $serviceSelect = $('#service_id');
    const $servicePrice = $('#service_price');
    const $dateInput = $('#appointment_date');
    const $slotSelect = $('#appointment_slot');
    const $vacationNotice = $('#vacation-notice');
    const $vacationText = $('#vacation-text');

    let flatpickrDate;

    function resetForm() {
        $doctor.html('<option value="">-- Vui lòng chọn dịch vụ --</option>').trigger('change.select2');
        $servicePrice.val('');
        $slotSelect.html('<option value="">Chọn giờ</option>').prop('disabled', true);
        $dateInput.val('').prop('disabled', true);
        $vacationNotice.addClass('d-none');
        if (flatpickrDate) flatpickrDate.destroy();
    }

    // 🔍 Autocomplete bệnh nhân
    $input.autocomplete({
        source(request, response) {
            $.get('/receptionist/appointments/patients/search', { q: request.term }, data => {
                response(data.map(p => ({
                    label: p.full_name + (p.role_id == 5 ? ' (Mới đến)' : ''),
                    value: p.id
                })));
            });
        },
        select(_, ui) {
            $input.val(ui.item.label);
            $hidden.val(ui.item.value);
            resetForm();
            return false;
        }
    });

    // 👉 Chọn dịch vụ → Hiển thị giá + Load bác sĩ theo dịch vụ
    $serviceSelect.on('change', function () {
        const price = parseFloat($(this).find(':selected').data('price')) || 0;
        $('#service_price').val(price.toLocaleString('vi-VN', { minimumFractionDigits: 0, maximumFractionDigits: 0 }) + ' ₫');

        const serviceId = $(this).val();

        if (!serviceId) {
            $doctor.html('<option value="">-- Vui lòng chọn dịch vụ --</option>').trigger('change.select2');
            return;
        }

        $.get(window.serviceDoctorsUrl.replace(':id', serviceId), data => {
            const html = data.map(doc => `
                <option value="${doc.id}">
                    ${doc.user.full_name}
                </option>`).join('');
            $doctor.html('<option value="">Chọn bác sĩ</option>' + html).trigger('change.select2');
        }).fail(() => toastr.error('Không thể tải danh sách bác sĩ'));
    });

    // 👉 Chọn bác sĩ → Load lịch làm việc và giờ khám
    $doctor.on('change', function () {
        const doctorId = $(this).val();
        if (!doctorId) {
            $dateInput.prop('disabled', true).val('');
            $slotSelect.html('<option value="">Chọn giờ</option>').prop('disabled', true);
            if (flatpickrDate) flatpickrDate.destroy();
            return;
        }

        $.get(window.doctorWorkingDaysUrl.replace(':id', doctorId), ({ specificDates, vacationDates }) => {
            $dateInput.prop('disabled', false);

            if (flatpickrDate) flatpickrDate.destroy();

            // Chỉ cho phép chọn các ngày có trong specificDates, trừ ngày nghỉ phép
            flatpickrDate = flatpickr($dateInput[0], {
                dateFormat: "Y-m-d",
                minDate: "today",
                disableMobile: true,
                locale: 'vi',
                disable: [
                    function (date) {
                        const str = flatpickr.formatDate(date, 'Y-m-d');
                        // disable nếu KHÔNG thuộc ngày bác sĩ đăng ký hoặc là ngày nghỉ
                        return !specificDates.includes(str) || vacationDates.includes(str);
                    }
                ],
                onChange: loadAvailableSlots
            });

            if (vacationDates && vacationDates.length) {
                const info = vacationDates.join(', ');
                $vacationText.text(`Bác sĩ nghỉ: ${info}`);
                $vacationNotice.removeClass('d-none');
            } else {
                $vacationNotice.addClass('d-none');
            }
        }).fail(() => toastr.error('Không thể tải lịch làm việc'));
    });

    function loadAvailableSlots() {
        const doctorId = $doctor.val();
        const date = $dateInput.val();

        if (!doctorId || !date) {
            $slotSelect.html('<option value="">Chọn giờ</option>').prop('disabled', true);
            return;
        }

        $slotSelect.html('<option>Đang tải...</option>').prop('disabled', true);

        $.get(`/receptionist/appointments/doctor/${doctorId}/available-slots`, { date, service_id: $('#service_id').val() }, slots => {
            $slotSelect.empty();
            if (!slots.length) {
                $slotSelect.append('<option value="">Không còn giờ trống</option>');
            } else {
                $slotSelect.append('<option value="">Chọn giờ</option>');
                slots.forEach(slot => {
                    $slotSelect.append(`<option value="${date} ${slot}">${slot}</option>`);
                });
            }
            $slotSelect.prop('disabled', false);
        }).fail(() => {
            toastr.error('Không thể tải giờ trống');
            $slotSelect.html('<option value="">Chọn giờ</option>').prop('disabled', true);
        });
    }

    // Phương thức thanh toán
    $('#payment_method').on('change', function () {
        const val = $(this).val();
        let note = '';
        let status = '';

        if (val === 'cash') {
            note = 'Thanh toán sẽ được xử lý ngay.';
            status = 'confirmed';
        } else {
            note = 'Cần xác nhận thanh toán sau khi nhận tiền.';
            status = 'pending';
        }

        $('#payment_note').text(note);
        $('#status_display').text(status === 'confirmed' ? 'Đã xác nhận' : 'Chờ xác nhận');
        $('input[name="status"]').val(status);
    }).trigger('change');

    // ⚡ Reload dữ liệu cũ nếu có
    if ($serviceSelect.val()) {
        $serviceSelect.trigger('change');

        if ($doctor.val()) {
            $doctor.trigger('change');
        }
    }
});
