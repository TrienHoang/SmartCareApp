$(document).ready(function () {
    const $input = $('#patient_name');
    const $hidden = $('#patient_id_hidden');
    const $doctor = $('#doctor_id');
    const $serviceSelect = $('#service_id');
    const $timeInput = $('#appointment_time');
    const $treatmentPlan = $('#treatment_plan_id');
    const $treatmentWrapper = $('#treatment-plan-wrapper');
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

    // Ẩn treatment plan ban đầu nếu chưa có bệnh nhân
    if (!$hidden.val()) {
        $treatmentWrapper.hide();
    }

    // Autocomplete bệnh nhân
    $input.autocomplete({
        source: function (request, response) {
            $.ajax({
                url: '/admin/appointments/patients/search',
                data: { q: request.term },
                success: function (data) {
                    response(data.map(p => ({
                        label: p.full_name,
                        value: p.id
                    })));
                }
            });
        },
        minLength: 1,
        delay: 250,
        select: function (event, ui) {
            $input.val(ui.item.label);
            $hidden.val(ui.item.value);

            // Load kế hoạch điều trị
            loadTreatmentPlans(ui.item.value);
            $treatmentWrapper.show();

            return false;
        }
    });

    // Load danh sách kế hoạch điều trị của bệnh nhân
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
        }).fail(() => {
            toastr.error('Không thể tải kế hoạch điều trị');
        });
    }

    // Chọn treatment plan → load doctor, service, thời gian
    $treatmentPlan.on('change', function () {
        const planId = $(this).val();
        if (!planId) {
            $doctor.val('').trigger('change.select2');

            // Bật lại tất cả các bác sĩ
            $('#doctor_id option').each(function () {
                $(this).prop('disabled', false);
            });

            // Refresh lại Select2
            $doctor.select2('destroy').select2({
                width: '100%',
                placeholder: 'Chọn mục...',
                allowClear: true
            });

            return;
        }

        const planUrl = window.treatmentPlanDetailsUrl.replace('__ID__', planId);
        $.get(planUrl, function (data) {
            const doctorId = data.doctor_id;
            const serviceId = data.service_id;
            const expected = data.expected_start_date;

            if (!serviceId && !expected) {
                toastr.warning('Kế hoạch điều trị chưa có bước điều trị nào!');
                return;
            }

            // Set bác sĩ
            $doctor.val(doctorId).trigger('change');

            // Chỉ hiển thị đúng bác sĩ này
            $('#doctor_id option').each(function () {
                const val = $(this).val();
                if (val != doctorId && val !== "") {
                    $(this).prop('disabled', true); // ✅ Disable
                } else {
                    $(this).prop('disabled', false); // ✅ Enable lại bác sĩ đúng
                }
            });

            // Gợi ý dịch vụ
            if (serviceId) $serviceSelect.data('old', serviceId);

            // Gợi ý thời gian
            if (expected) $timeInput.data('old', expected);
        }).fail(() => {
            toastr.error('Không thể tải thông tin kế hoạch điều trị');
        });
    });

    // Khi chọn bác sĩ
    $doctor.on('change', function () {
        const doctorId = $(this).val();
        $serviceSelect.html('<option value="">Đang tải dịch vụ...</option>');

        if (!doctorId) {
            $serviceSelect.html('<option value="">Chọn dịch vụ</option>').trigger('change.select2');
            $timeInput.prop('disabled', true).val('');
            if (flatpickrInstance) flatpickrInstance.destroy();
            $('#vacation-notice').addClass('d-none');
            return;
        }

        // Load dịch vụ
        $.get(window.doctorServicesUrl.replace(':id', doctorId), function (services) {
            let html = '<option value="">Chọn dịch vụ</option>';
            const oldServiceId = $serviceSelect.data('old');
            services.forEach(service => {
                html += `<option value="${service.id}" ${oldServiceId == service.id ? 'selected' : ''}>
                    ${service.name} (${service.department?.name ?? 'Không rõ khoa'})
                </option>`;
            });
            $serviceSelect.html(html).trigger('change.select2');
        }).fail(() => toastr.error('Không thể tải danh sách dịch vụ'));

        // Load lịch làm việc
        $.get(window.doctorWorkingDaysUrl.replace(':id', doctorId), function ({ daysOfWeek, specificDates, vacationDates }) {
            if (flatpickrInstance) flatpickrInstance.destroy();

            const oldTime = $timeInput.data('old') || $timeInput.val();
            if (!oldTime) $timeInput.val('');

            flatpickrInstance = flatpickr("#appointment_time", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                minDate: "today",
                disableMobile: true,
                locale: 'vi',
                enable: specificDates.length > 0 ? specificDates : function (date) {
                    const day = date.getDay();
                    const str = date.toISOString().split('T')[0];
                    if (vacationDates.includes(str)) return false;
                    return daysOfWeek.includes(day);
                },
                onReady: function (_, __, instance) {
                    if (oldTime) {
                        const oldDate = new Date(oldTime);
                        const oldStr = oldDate.toISOString().split('T')[0];
                        const oldDay = oldDate.getDay();
                        let valid = specificDates.length > 0
                            ? specificDates.includes(oldStr)
                            : daysOfWeek.includes(oldDay);

                        if (valid && !vacationDates.includes(oldStr)) {
                            instance.setDate(oldTime, true);
                            $timeInput.val(oldTime);
                        }
                    }
                },
                onChange: function (dates, _, instance) {
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

            const $notice = $('#vacation-notice');
            const $text = $('#vacation-text');
            if (vacationDates.length > 0) {
                const info = formatDateRange(vacationDates);
                $text.text(`Bác sĩ nghỉ phép: ${info}`);
                $notice.removeClass('d-none');
                toastr.info(`Bác sĩ nghỉ phép: ${info}`, 'Thông báo');
            } else {
                $notice.addClass('d-none');
            }
        }).fail(() => {
            toastr.error('Không thể tải lịch làm việc');
            $timeInput.prop('disabled', true);
            if (flatpickrInstance) flatpickrInstance.destroy();
        });
    });

    // Reload lại form khi có dữ liệu cũ
    const oldDoctorId = $doctor.val();
    const oldServiceId = $serviceSelect.val();
    const oldTime = $timeInput.val();
    if (oldServiceId) $serviceSelect.data('old', oldServiceId);
    if (oldTime) $timeInput.data('old', oldTime);
    if (oldDoctorId) $doctor.trigger('change');

    $('form').on('submit', function () {
        const val = $timeInput.val();
        if (val && flatpickrInstance) $timeInput.val(val);
    });
    // Ẩn/hiện dropdown kế hoạch điều trị theo bệnh nhân
    $input.on('autocompleteselect', function (event, ui) {
        const patientId = ui.item.value;
        if (patientId) {
            $('#treatment-plan-wrapper').show();
            loadTreatmentPlans(patientId); // function riêng để load kế hoạch từ route
        } else {
            $('#treatment-plan-wrapper').hide();
        }
    });
});