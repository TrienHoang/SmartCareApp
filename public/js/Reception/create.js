$(document).ready(function () {
    const $input = $('#patient_name');
    const $hidden = $('#patient_id_hidden');
    const $doctor = $('#doctor_id');
    const $serviceSelect = $('#service_id');
    const $dateInput = $('#appointment_date');
    const $slotSelect = $('#appointment_slot');
    const $vacationNotice = $('#vacation-notice');
    const $vacationText = $('#vacation-text');

    let flatpickrDate;

    function formatDateRange(dates) {
        if (!dates?.length) return '';
        const sorted = [...dates].sort();
        const groups = [];
        let group = [sorted[0]];
        for (let i = 1; i < sorted.length; i++) {
            const prev = new Date(sorted[i - 1]);
            const curr = new Date(sorted[i]);
            const diff = (curr - prev) / (1000 * 60 * 60 * 24);
            if (diff === 1) group.push(sorted[i]);
            else {
                groups.push(group);
                group = [sorted[i]];
            }
        }
        groups.push(group);
        return groups.map(group => {
            if (group.length === 1) return group[0];
            else return `${group[0]} đến ${group[group.length - 1]}`;
        }).join(', ');
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

    function resetForm() {
        $doctor.val('').trigger('change.select2');
        $serviceSelect.html('<option value="">Chọn dịch vụ</option>').trigger('change.select2');
        $slotSelect.html('<option value="">Chọn giờ</option>').prop('disabled', true);
        $dateInput.val('').prop('disabled', true);
        $vacationNotice.addClass('d-none');
        if (flatpickrDate) flatpickrDate.destroy();
    }

    $doctor.on('change', function () {
        const doctorId = $(this).val();

        if (!doctorId) {
            resetForm();
            return;
        }

        loadServices(doctorId);
        loadWorkingDays(doctorId);
    });

    $serviceSelect.on('change', function () {
        loadAvailableSlots();
    });

    function loadServices(doctorId) {
        $.get(window.doctorServicesUrl.replace(':id', doctorId), data => {
            const html = data.map(service => `
                <option value="${service.id}">
                    ${service.name} (${service.department?.name ?? 'Không rõ khoa'})
                </option>`).join('');
            $serviceSelect.html('<option value="">Chọn dịch vụ</option>' + html).trigger('change.select2');
        }).fail(() => toastr.error('Không thể tải danh sách dịch vụ'));
    }

    function loadWorkingDays(doctorId) {
        $.get(window.doctorWorkingDaysUrl.replace(':id', doctorId), ({ vacationDates }) => {
            $dateInput.prop('disabled', false);

            if (flatpickrDate) flatpickrDate.destroy();

            flatpickrDate = flatpickr($dateInput[0], {
                dateFormat: "Y-m-d",
                minDate: "today",
                disableMobile: true,
                locale: 'vi',
                disable: [
                    // Disable vacation dates
                    ...vacationDates,
                    // Disable Sundays
                    function (date) {
                        return date.getDay() === 0;
                    }
                ],
                onChange: loadAvailableSlots
            });

            if (vacationDates.length) {
                const info = formatDateRange(vacationDates);
                $vacationText.text(`Bác sĩ nghỉ: ${info}`);
                $vacationNotice.removeClass('d-none');
            } else {
                $vacationNotice.addClass('d-none');
            }
        }).fail(() => toastr.error('Không thể tải lịch làm việc'));
    }

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

    // ⚡ Khởi tạo lại dữ liệu cũ
    if ($doctor.val()) {
        $doctor.trigger('change');
    }
});
