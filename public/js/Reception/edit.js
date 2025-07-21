$(document).ready(function () {
    const $doctor = $('#doctor_id');
    const $serviceSelect = $('#service_id');
    const $servicePrice = $('#service_price');
    const $dateInput = $('#appointment_date');
    const $slotSelect = $('#appointment_slot');
    const $vacationNotice = $('#vacation-notice');
    const $vacationText = $('#vacation-text');

    let flatpickrDate;

    const oldAppointmentTimeGlobal = $slotSelect.data('old');

    function resetForm() {
        $serviceSelect.html('<option value="">Chọn dịch vụ</option>').trigger('change.select2');
        $servicePrice.val('');
        $slotSelect.html('<option value="">Chọn giờ</option>').prop('disabled', true);
        $dateInput.prop('disabled', true);
        $vacationNotice?.addClass('d-none');
        if (flatpickrDate) flatpickrDate.destroy();
    }

    function loadServices(doctorId, callback) {
        $.get(doctorServicesUrl.replace(':id', doctorId), services => {
            const oldServiceId = $serviceSelect.data('old');
            let options = '<option value="">Chọn dịch vụ</option>';
            services.forEach(service => {
                const selected = oldServiceId == service.id ? 'selected' : '';
                options += `<option value="${service.id}" data-price="${service.price}" ${selected}>${service.name}</option>`;
            });
            $serviceSelect.html(options).trigger('change.select2');

            if (oldServiceId) {
                $serviceSelect.val(oldServiceId).trigger('change');
            }

            if (typeof callback === 'function') callback();
        }).fail(() => toastr.error('Không thể tải danh sách dịch vụ'));
    }

    function loadWorkingDays(doctorId, callback) {
        $.get(window.doctorWorkingDaysUrl.replace(':id', doctorId), ({ vacationDates }) => {
            $dateInput.prop('disabled', false);

            if (flatpickrDate) flatpickrDate.destroy();

            flatpickrDate = flatpickr($dateInput[0], {
                dateFormat: "Y-m-d",
                minDate: "today",
                disableMobile: true,
                locale: 'vi',
                disable: [
                    ...vacationDates,
                    date => date.getDay() === 0
                ],
                onChange: loadAvailableSlots
            });

            if (vacationDates?.length) {
                $vacationText?.text(`Bác sĩ nghỉ: ${vacationDates.join(', ')}`);
                $vacationNotice?.removeClass('d-none');
            } else {
                $vacationNotice?.addClass('d-none');
            }

            if (typeof callback === 'function') callback();
        }).fail(() => toastr.error('Không thể tải lịch làm việc'));
    }

    function loadAvailableSlots() {
        const doctorId = $doctor.val();
        const date = $dateInput.val();
        const serviceId = $serviceSelect.val();

        if (!doctorId || !date || !serviceId) {
            $slotSelect.html('<option value="">Chọn giờ</option>').prop('disabled', true);
            return;
        }

        $slotSelect.html('<option>Đang tải...</option>').prop('disabled', true);

        $.get(`/receptionist/appointments/doctor/${doctorId}/available-slots`, { date, service_id: serviceId, current_appointment_id: window.currentAppointmentId }, slots => {
            $slotSelect.empty();
            if (!slots.length) {
                $slotSelect.append('<option value="">Không còn giờ trống</option>');
            } else {
                $slotSelect.append('<option value="">Chọn giờ</option>');
                slots.forEach(slot => {
                    const value = `${date} ${slot}`;
                    const selected = (value === oldAppointmentTimeGlobal) ? 'selected' : '';
                    $slotSelect.append(`<option value="${value}" ${selected}>${slot}</option>`);
                });
            }
            $slotSelect.prop('disabled', false);
        }).fail(() => {
            toastr.error('Không thể tải giờ trống');
            $slotSelect.html('<option value="">Chọn giờ</option>').prop('disabled', true);
        });
    }

    $doctor.on('change', function () {
        const doctorId = $(this).val();
        if (!doctorId) {
            resetForm();
            return;
        }

        loadServices(doctorId, () => {
            loadWorkingDays(doctorId, () => {
                if ($dateInput.val()) {
                    loadAvailableSlots();
                }
            });
        });
    });

    $serviceSelect.on('change', function () {
        const price = parseFloat($(this).find(':selected').data('price')) || 0;
        $servicePrice.val(price.toLocaleString('vi-VN') + ' ₫');
        if ($dateInput.val()) {
            loadAvailableSlots();
        }
    });

    if ($doctor.val()) {
        $doctor.trigger('change');
    }
});
