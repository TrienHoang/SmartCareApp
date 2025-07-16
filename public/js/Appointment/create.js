$(document).ready(function () {
    const $input = $('#patient_name');
    const $hidden = $('#patient_id_hidden');
    const $doctor = $('#doctor_id');
    const $serviceSelect = $('#service_id');
    const $timeInput = $('#appointment_time');
    const $treatmentPlan = $('#treatment_plan_id');
    const $treatmentWrapper = $('#treatment-plan-wrapper');
    const $vacationNotice = $('#vacation-notice');
    const $vacationText = $('#vacation-text');
    let flatpickrInstance;

    function formatDateRange(dates) {
        if (!dates || dates.length === 0) return '';
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
            else if (group.length === 2) return `${group[0]}, ${group[1]}`;
            else return `${group[0]} đến ${group[group.length - 1]}`;
        }).join(', ');
    }

    if (!$hidden.val()) $treatmentWrapper.hide();

    // Autocomplete bệnh nhân
    $input.autocomplete({
        source(request, response) {
            $.ajax({
                url: '/admin/appointments/patients/search',
                data: { q: request.term },
                success(data) {
                    response(data.map(p => ({
                        label: p.full_name,
                        value: p.id
                    })));
                }
            });
        },
        minLength: 1,
        delay: 250,
        select(event, ui) {
            $input.val(ui.item.label);
            $hidden.val(ui.item.value);

            // Reset tất cả khi chọn bệnh nhân mới
            $treatmentPlan.val('').trigger('change');
            $doctor.val('').trigger('change.select2');
            $serviceSelect.html('<option value="">Chọn dịch vụ</option>').trigger('change.select2');
            $timeInput.val('').prop('disabled', true);
            if (flatpickrInstance) flatpickrInstance.destroy();
            $vacationNotice.addClass('d-none');

            loadTreatmentPlans(ui.item.value);
            $treatmentWrapper.show();
            return false;
        }
    });

    function loadTreatmentPlans(patientId) {
        const url = window.treatmentPlansByPatientUrl.replace(':id', patientId);
        $.get(url, function (plans) {
            $treatmentPlan.empty().append(`<option value="">-- Không chọn --</option>`);
            if (plans.length === 0) {
                $treatmentPlan.append(`<option disabled selected>Không có kế hoạch điều trị</option>`);
                return;
            }
            plans.forEach(plan => {
                $treatmentPlan.append(`<option value="${plan.id}">${plan.plan_title} - ${plan.doctor_name ?? ''}</option>`);
            });
        }).fail(() => toastr.error('Không thể tải kế hoạch điều trị'));
    }

    $treatmentPlan.on('change', function () {
        const planId = $(this).val();

        if (!planId) {
            $doctor.val('').trigger('change.select2');
            $('#doctor_id option').prop('disabled', false);
            $doctor.select2('destroy').select2({
                width: '100%',
                placeholder: 'Chọn mục...',
                allowClear: true
            });
            return;
        }

        const planUrl = window.treatmentPlanDetailsUrl.replace('__ID__', planId);
        $.get(planUrl, function (data) {
            const { doctor_id, service_id, expected_start_date } = data;

            if (!service_id && !expected_start_date) {
                toastr.warning('Kế hoạch điều trị chưa có bước điều trị nào!');
                return;
            }

            $doctor.val(doctor_id).trigger('change');

            $('#doctor_id option').each(function () {
                const val = $(this).val();
                $(this).prop('disabled', val != doctor_id && val !== "");
            });

            if (service_id) $serviceSelect.data('old', service_id);
            if (expected_start_date) {
                const formatted = new Date(expected_start_date).toISOString().slice(0, 16).replace('T', ' ');
                $timeInput.data('old', formatted);
            }
        }).fail(() => toastr.error('Không thể tải thông tin kế hoạch điều trị'));
    });

    $doctor.on('change', function () {
        const doctorId = $(this).val();

        $serviceSelect.html('<option value="">Đang tải dịch vụ...</option>');

        if (!doctorId) {
            $serviceSelect.html('<option value="">Chọn dịch vụ</option>').trigger('change.select2');
            $timeInput.val('').prop('disabled', true);
            if (flatpickrInstance) flatpickrInstance.destroy();
            $vacationNotice.addClass('d-none');
            return;
        }

        // Load dịch vụ
        $.get(window.doctorServicesUrl.replace(':id', doctorId), function (services) {
            const oldServiceId = $serviceSelect.data('old');
            const html = services.map(service =>
                `<option value="${service.id}" ${oldServiceId == service.id ? 'selected' : ''}>
                    ${service.name} (${service.department?.name ?? 'Không rõ khoa'})
                </option>`).join('');
            $serviceSelect.html('<option value="">Chọn dịch vụ</option>' + html).trigger('change.select2');
        }).fail(() => toastr.error('Không thể tải danh sách dịch vụ'));

        // Load lịch làm việc và khởi tạo Flatpickr
        $.get(window.doctorWorkingDaysUrl.replace(':id', doctorId), function ({ daysOfWeek, specificDates, vacationDates }) {
            if (flatpickrInstance) flatpickrInstance.destroy();
            const oldTime = $timeInput.data('old') || $timeInput.val();
            if (!oldTime) $timeInput.val('');

            const sundays = [];
            const today = new Date();
            const nextYear = new Date();
            nextYear.setFullYear(today.getFullYear() + 1);

            for (let d = new Date(today); d <= nextYear; d.setDate(d.getDate() + 1)) {
                if (d.getDay() === 0) {
                    sundays.push(d.toISOString().split('T')[0]);
                }
            }

            // Ghép thêm vacationDates
            const disabledDates = sundays.concat(vacationDates);

            flatpickrInstance = flatpickr("#appointment_time", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",   // Format input
                altFormat: "Y-m-d",        // Format so sánh
                time_24hr: true,
                minDate: "today",
                disableMobile: true,
                locale: 'vi',
                disable: [
                    function (date) {
                        const day = date.getDay();
                        const str = date.toISOString().split('T')[0];
                        // Disable nếu là Chủ nhật hoặc trong ngày nghỉ
                        if (day === 0) return true; // Chủ nhật
                        if (vacationDates.includes(str)) return true; // ngày nghỉ
                        return false;
                    }
                ],
                onReady(_, __, instance) {
                    if (oldTime) {
                        const d = new Date(oldTime);
                        const str = d.toISOString().split('T')[0];
                        if ((specificDates.includes(str) || daysOfWeek.includes(d.getDay())) && !vacationDates.includes(str)) {
                            instance.setDate(oldTime, true);
                            $timeInput.val(oldTime);
                        }
                    }
                },
                onChange(dates, _, instance) {
                    if (dates.length > 0) {
                        const dateStr = dates[0].toISOString().split('T')[0];
                        if (vacationDates.includes(dateStr)) {
                            toastr.warning('Bác sĩ nghỉ ngày này, vui lòng chọn ngày khác');
                            instance.clear();
                        }
                    }
                }
            });

            $timeInput.prop('disabled', false);

            if (vacationDates.length > 0) {
                const info = formatDateRange(vacationDates);
                $vacationText.text(`Bác sĩ nghỉ phép: ${info}`);
                $vacationNotice.removeClass('d-none');
                toastr.info(`Bác sĩ nghỉ phép: ${info}`, 'Thông báo');
            } else {
                $vacationNotice.addClass('d-none');
            }
        }).fail(() => {
            toastr.error('Không thể tải lịch làm việc');
            $timeInput.prop('disabled', true);
            if (flatpickrInstance) flatpickrInstance.destroy();
        });
    });

    // Khởi tạo lại dữ liệu cũ khi reload
    const oldDoctorId = $doctor.val();
    const oldServiceId = $serviceSelect.val();
    const oldTime = $timeInput.val();
    if (oldServiceId) $serviceSelect.data('old', oldServiceId);
    if (oldTime) $timeInput.data('old', oldTime);
    if (oldDoctorId) $doctor.trigger('change');

    // Đảm bảo giữ lại thời gian khi submit form
    $('form').on('submit', function () {
        const val = $timeInput.val();
        if (val && flatpickrInstance) $timeInput.val(val);
    });
});
