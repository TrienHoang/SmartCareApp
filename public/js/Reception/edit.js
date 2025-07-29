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
        $doctor.html('<option value="">Chọn bác sĩ</option>').trigger('change.select2');
        $servicePrice.val('');
        $slotSelect.html('<option value="">Chọn giờ</option>').prop('disabled', true);
        $dateInput.prop('disabled', true);
        $vacationNotice?.addClass('d-none');
        if (flatpickrDate) flatpickrDate.destroy();
    }

    function loadDoctors(serviceId, callback) {
        $.get(window.serviceDoctorsUrl.replace(':id', serviceId), doctors => {
            const oldDoctorId = $doctor.data('old');
            let options = '<option value="">Chọn bác sĩ</option>';
            doctors.forEach(doc => {
                const selected = oldDoctorId == doc.id ? 'selected' : '';
                options += `<option value="${doc.id}" ${selected}>${doc.user.full_name}</option>`;
            });
            $doctor.html(options).trigger('change.select2');

            if (typeof callback === 'function') callback();
        }).fail(() => toastr.error('Không thể tải danh sách bác sĩ'));
    }

    function loadWorkingDays(doctorId, callback) {
        $.get(window.doctorWorkingDaysUrl.replace(':id', doctorId), ({ specificDates, vacationDates }) => {
            $dateInput.prop('disabled', false);

            if (flatpickrDate) flatpickrDate.destroy();

            // Chỉ cho phép chọn những ngày trong specificDates và không bị nghỉ phép
            flatpickrDate = flatpickr($dateInput[0], {
                dateFormat: "Y-m-d",
                minDate: "today",
                disableMobile: true,
                locale: 'vi',
                disable: [
                    function (date) {
                        const str = flatpickr.formatDate(date, 'Y-m-d');
                        return !specificDates.includes(str) || vacationDates.includes(str);
                    }
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

        $.get(`/receptionist/appointments/doctor/${doctorId}/available-slots`, {
            date,
            service_id: serviceId,
            current_appointment_id: window.currentAppointmentId
        }, slots => {
            $slotSelect.empty();

            const oldFull = $slotSelect.data('old') || '';
            const oldTimeOnly = oldFull.split(' ')[1] ?? '';

            if (!slots.length) {
                $slotSelect.append('<option value="">Không còn giờ trống</option>');
            } else {
                $slotSelect.append('<option value="">Chọn giờ</option>');
                let hasOld = false;

                slots.forEach(slot => {
                    const value = `${date} ${slot}`;
                    const selected = (slot === oldTimeOnly) ? 'selected' : '';
                    if (selected) hasOld = true;

                    $slotSelect.append(`<option value="${value}" ${selected}>${slot}</option>`);
                });

                // Nếu giờ cũ không còn trong danh sách, vẫn hiển thị để tránh mất dữ liệu khi edit
                if (!hasOld && oldTimeOnly) {
                    $slotSelect.append(`<option value="${oldFull}" selected>${oldTimeOnly} (giờ đã bận)</option>`);
                }
            }
            $slotSelect.prop('disabled', false);
        }).fail(() => {
            toastr.error('Không thể tải giờ trống');
            $slotSelect.html('<option value="">Chọn giờ</option>').prop('disabled', true);
        });
    }

    $serviceSelect.on('change', function () {
        const price = parseFloat($(this).find(':selected').data('price')) || 0;
        $servicePrice.val(price.toLocaleString('vi-VN') + ' ₫');

        const serviceId = $(this).val();
        if (!serviceId) {
            resetForm();
            return;
        }

        loadDoctors(serviceId, () => {
            const selectedDoctorId = $doctor.val();
            if (selectedDoctorId) {
                loadWorkingDays(selectedDoctorId, () => {
                    if ($dateInput.val()) {
                        loadAvailableSlots();
                    }
                });
            }
        });
    });

    $doctor.on('change', function () {
        const doctorId = $(this).val();
        if (!doctorId) {
            $dateInput.prop('disabled', true).val('');
            $slotSelect.html('<option value="">Chọn giờ</option>').prop('disabled', true);
            if (flatpickrDate) flatpickrDate.destroy();
            return;
        }

        loadWorkingDays(doctorId, () => {
            if ($dateInput.val()) {
                loadAvailableSlots();
            }
        });
    });

    // Tự động load lại dữ liệu cũ nếu có
    if ($serviceSelect.val()) {
        $serviceSelect.trigger('change');
    }
});
