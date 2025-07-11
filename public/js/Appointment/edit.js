$(document).ready(function () {
    const $doctor = $('#doctor_id');
    const $serviceSelect = $('#service_id');
    const $timeInput = $('#appointment_time');
    const $treatmentPlan = $('#treatment_plan_id');
    const treatmentPlanDetailsUrl = $('#treatmentPlanDetailsUrl').val();
    const doctorServicesUrl = $('#doctorServicesUrl').val();
    const doctorWorkingDaysUrl = $('#doctorWorkingDaysUrl').val();
    const patientId = $('#patient_id').val();
    const selectedPlanId = '{{ $appointment->treatment_plan_id }}';

    loadTreatmentPlans(patientId, selectedPlanId);

    let flatpickrInstance;

    function destroyFlatpickr() {
        if (flatpickrInstance && typeof flatpickrInstance.destroy === 'function') {
            flatpickrInstance.destroy();
        }
        flatpickrInstance = null;
    }

    function formatDateRange(dates) {
        if (!dates || dates.length === 0) return '';
        const sortedDates = [...dates].sort();
        const groups = [];
        let currentGroup = [sortedDates[0]];

        for (let i = 1; i < sortedDates.length; i++) {
            const prev = new Date(sortedDates[i - 1]);
            const curr = new Date(sortedDates[i]);
            const diffDays = (curr - prev) / (1000 * 60 * 60 * 24);

            if (diffDays === 1) {
                currentGroup.push(sortedDates[i]);
            } else {
                groups.push(currentGroup);
                currentGroup = [sortedDates[i]];
            }
        }
        groups.push(currentGroup);

        return groups.map(group => {
            if (group.length === 1) return group[0];
            if (group.length === 2) return `${group[0]}, ${group[1]}`;
            return `${group[0]} đến ${group[group.length - 1]}`;
        }).join(', ');
    }

    $('#doctor_id, #service_id, #status, #treatment_plan_id').select2({ width: '100%' });

    function loadServices(doctorId) {
        $.ajax({
            url: doctorServicesUrl.replace(':id', doctorId),
            method: 'GET',
            success: function (services) {
                let options = '<option value="">Chọn dịch vụ</option>';
                const oldServiceId = $serviceSelect.data('old');
                services.forEach(service => {
                    const selected = oldServiceId == service.id ? 'selected' : '';
                    options += `<option value="${service.id}" ${selected}>${service.name} (${service.department?.name ?? 'Không rõ khoa'})</option>`;
                });
                $serviceSelect.html(options).trigger('change.select2');
            },
            error: () => {
                toastr.error('Không thể tải danh sách dịch vụ');
            }
        });
    }

    function loadWorkingDays(doctorId) {
        $.ajax({
            url: doctorWorkingDaysUrl.replace(':id', doctorId),
            method: 'GET',
            success: function ({ daysOfWeek, specificDates, vacationDates }) {
                destroyFlatpickr();

                const currentValue = $timeInput.val();
                if (!currentValue && !$timeInput.data('old')) $timeInput.val('');

                flatpickrInstance = flatpickr("#appointment_time", {
                    enableTime: true,
                    dateFormat: "Y-m-d H:i",
                    time_24hr: true,
                    minDate: "today",
                    disableMobile: true,
                    locale: 'vi',
                    enable: specificDates.length > 0 ? specificDates : function (date) {
                        const day = date.getDay();
                        const dateStr = date.toISOString().split('T')[0];
                        if (vacationDates.includes(dateStr)) return false;
                        return daysOfWeek.includes(day);
                    },
                    onReady: function () {
                        const oldTime = $timeInput.data('old') || $timeInput.val();
                        if (oldTime) {
                            const oldDate = new Date(oldTime);
                            const oldDateStr = oldDate.toISOString().split('T')[0];
                            const oldDay = oldDate.getDay();

                            let isValid = specificDates.length > 0
                                ? specificDates.includes(oldDateStr)
                                : daysOfWeek.includes(oldDay);

                            if (isValid && !vacationDates.includes(oldDateStr)) {
                                flatpickrInstance.setDate(oldTime, true);
                                $timeInput.val(oldTime);
                            }
                        }
                    },
                    onChange: function (selectedDates) {
                        if (selectedDates.length > 0) {
                            const dateStr = selectedDates[0].toISOString().split('T')[0];
                            if (vacationDates.includes(dateStr)) {
                                toastr.warning('Bác sĩ nghỉ phép ngày này, chọn ngày khác');
                                flatpickrInstance.clear();
                            }
                        }
                    }
                });

                $timeInput.prop('disabled', false);

                const $notice = $('#vacation-notice');
                const $text = $('#vacation-text');
                if (vacationDates.length > 0) {
                    const message = formatDateRange(vacationDates);
                    $text.text(`Bác sĩ nghỉ phép: ${message}`);
                    $notice.removeClass('d-none');
                    toastr.info(`Bác sĩ nghỉ phép: ${message}`, 'Thông báo', { timeOut: 8000 });
                } else {
                    $notice.addClass('d-none');
                }
            },
            error: () => {
                toastr.error('Không thể tải lịch làm việc bác sĩ');
                $timeInput.prop('disabled', true);
                destroyFlatpickr();
            }
        });
    }

    function loadTreatmentPlans(patientId, selectedPlanId = null) {
        $.ajax({
            url: `/admin/appointments/treatment-plans/by-patient/${patientId}`,
            method: 'GET',
            success: function (plans) {
                let options = `<option value="">-- Không chọn --</option>`;
                plans.forEach(plan => {
                    const selected = plan.id == selectedPlanId ? 'selected' : '';
                    options += `<option value="${plan.id}" ${selected}>${plan.plan_title} - ${plan.doctor_name}</option>`;
                });
                $('#treatment_plan_id').html(options).trigger('change.select2');
            },
            error: function () {
                toastr.error('Không thể tải danh sách kế hoạch điều trị');
            }
        });
    }

    $treatmentPlan.on('change', function () {
        if ($treatmentPlan.prop('disabled')) return;

        const planId = $(this).val();
        if (!planId) {
            $('#doctor_id option').each(function () {
                $(this).prop('disabled', false).show();
            });
            $doctor.prop('disabled', false).val('').trigger('change.select2');

            $('input[name="doctor_id"]').remove();
            return;
        }

        $.ajax({
            url: treatmentPlanDetailsUrl.replace(':id', planId),
            method: 'GET',
            success: function (response) {
                const selectedDoctorId = response.doctor_id;

                $('#doctor_id option').each(function () {
                    const val = $(this).val();
                    if (val === '') return;
                    if (parseInt(val) === selectedDoctorId) {
                        $(this).prop('disabled', false).show();
                    } else {
                        $(this).prop('disabled', true).hide();
                    }
                });

                $doctor.val(selectedDoctorId).trigger('change.select2');
                $doctor.prop('disabled', true);

                let hiddenDoctor = $('input[name="doctor_id"]');
                if (hiddenDoctor.length === 0) {
                    hiddenDoctor = $('<input type="hidden" name="doctor_id" />').appendTo('form');
                }
                hiddenDoctor.val(selectedDoctorId);

                if (response.service_id) {
                    $serviceSelect.val(response.service_id).trigger('change.select2');
                }

                if (response.expected_start_date) {
                    const dt = new Date(response.expected_start_date);
                    const formatted = dt.toISOString().slice(0, 16);
                    $timeInput.val(formatted);
                }
            },
            error: function () {
                toastr.error('Không thể tải thông tin kế hoạch điều trị');
            }
        });
    });

    $doctor.on('change', function () {
        const id = $(this).val();
        console.log('doctor_id changed to:', id);
        if (id) {
            loadServices(id);
            loadWorkingDays(id);
        } else {
            $serviceSelect.html('<option value="">Chọn dịch vụ</option>').trigger('change.select2');
            $timeInput.prop('disabled', true).val('');
            destroyFlatpickr();
            $('#vacation-notice').addClass('d-none');
        }
    });

    if ($timeInput.val()) $timeInput.data('old', $timeInput.val());
    if ($serviceSelect.val()) $serviceSelect.data('old', $serviceSelect.val());
    if ($doctor.val()) $doctor.trigger('change');
    if ($treatmentPlan.val()) $treatmentPlan.trigger('change');

    $('form').on('submit', function () {
        const timeVal = $timeInput.val();
        if (timeVal && flatpickrInstance) {
            $timeInput.val(timeVal);
        }
    });
});
