$(document).ready(function () {
    const $doctor = $('#doctor_id');
    const $serviceSelect = $('#service_id');
    const $servicePrice = $('#service_price');
    const $treatmentPlan = $('#treatment_plan_id');
    const $dateInput = $('#appointment_date');
    const $slotSelect = $('#appointment_slot');
    const patientId = $('#patient_id').val();
    const selectedPlanId = window.selectedPlanId;

    const treatmentPlanDetailsUrl = $('#treatmentPlanDetailsUrl').val();
    const doctorWorkingDaysUrl = $('#doctorWorkingDaysUrl').val();
    const serviceDoctorsUrl = '/admin/appointments/services/:id/doctors';
    const availableTimesUrl = '/admin/appointments/doctor/__DOCTOR__/available-times';

    $('#doctor_id, #service_id, #status, #treatment_plan_id').select2({ width: '100%' });

    let flatpickrInstance;
    let vacationDates = [];

    function destroyFlatpickr() {
        if (flatpickrInstance) {
            flatpickrInstance.destroy();
            flatpickrInstance = null;
        }
    }

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

    function loadAvailableSlots(doctorId, dateStr) {
        if (!doctorId || !dateStr) return;

        const url = availableTimesUrl.replace('__DOCTOR__', doctorId)
            + '?date=' + encodeURIComponent(dateStr)
            + '&service_id=' + encodeURIComponent($serviceSelect.val() || '')
            + '&treatment_plan_id=' + encodeURIComponent($treatmentPlan.val() || '');

        $.get(url, function (slots) {
            $slotSelect.empty().append('<option value="">Chọn giờ</option>');
            if (slots.length === 0) {
                $slotSelect.append('<option disabled>Không có giờ trống</option>');
                return;
            }

            slots.forEach(time => {
                const full = `${dateStr} ${time}`;
                const isSelected = full === $slotSelect.data('selected') ? 'selected' : '';
                $slotSelect.append(`<option value="${full}" ${isSelected}>${time}</option>`);
            });
        }).fail(() => toastr.error('Không thể tải giờ khám'));
    }

    function loadWorkingDays(doctorId) {
        destroyFlatpickr();

        $.get(doctorWorkingDaysUrl.replace(':id', doctorId), function ({ specificDates, vacationDates: vacations }) {
            vacationDates = vacations || [];
            const workingSpecific = specificDates || [];

            flatpickrInstance = flatpickr("#appointment_date", {
                dateFormat: "Y-m-d",
                minDate: "today",
                disableMobile: true,
                locale: "vi",
                disable: [
                    function (date) {
                        const str = flatpickr.formatDate(date, 'Y-m-d');

                        // Chỉ bật chọn nếu ngày nằm trong workingSpecific và không nằm trong vacationDates
                        return !workingSpecific.includes(str) || vacationDates.includes(str);
                    }
                ],
                onChange: function (selectedDates, dateStr) {
                    if (vacationDates.includes(dateStr)) {
                        toastr.warning('Bác sĩ nghỉ phép ngày này, vui lòng chọn ngày khác.', 'Cảnh báo');
                        flatpickrInstance.clear();
                        return;
                    }
                    if (selectedDates.length) {
                        loadAvailableSlots($doctor.val(), dateStr);
                    }
                }
            });

            if (vacationDates.length > 0) {
                const info = formatDateRange(vacationDates);
                $('#vacation-text').text(`Bác sĩ nghỉ phép: ${info}`);
                $('#vacation-notice').removeClass('d-none');
                toastr.info(`Bác sĩ nghỉ phép: ${info}`, 'Thông báo');
            } else {
                $('#vacation-notice').addClass('d-none');
            }
        }).fail(() => toastr.error('Không thể tải lịch làm việc'));
    }

    function loadDoctorsByService(serviceId, selectedDoctorId = null) {
        if (!serviceId) {
            $doctor.html('<option value="">Chọn bác sĩ</option>').trigger('change.select2');
            return;
        }

        const url = serviceDoctorsUrl.replace(':id', serviceId);

        $.get(url, function (doctors) {
            let options = '<option value="">Chọn bác sĩ</option>';
            doctors.forEach(doctor => {
                const selected = selectedDoctorId == doctor.id ? 'selected' : '';
                options += `<option value="${doctor.id}" ${selected}>${doctor.user?.full_name ?? 'Không rõ'}</option>`;
            });

            $doctor.html(options).prop('disabled', false).trigger('change.select2');

            if (selectedDoctorId) {
                $doctor.val(selectedDoctorId).trigger('change');
            }
        }).fail(() => toastr.error('Không thể tải danh sách bác sĩ'));
    }

    function loadTreatmentPlans(patientId, selectedPlanId = null) {
        $.get(`/admin/appointments/treatment-plans/by-patient/${patientId}`, function (plans) {
            let options = `<option value="">-- Không chọn --</option>`;
            plans.forEach(plan => {
                const selected = plan.id == selectedPlanId ? 'selected' : '';
                options += `<option value="${plan.id}" ${selected}>${plan.plan_title} - ${plan.doctor_name}</option>`;
            });
            $treatmentPlan.html(options).trigger('change.select2');
        }).fail(() => toastr.error('Không thể tải danh sách kế hoạch điều trị'));
    }

    $treatmentPlan.on('change', function () {
        const planId = $(this).val();
        if (!planId) {
            $doctor.prop('disabled', false);
            $('input[name="doctor_id"]').remove();
            return;
        }

        $.get(treatmentPlanDetailsUrl.replace(':id', planId), function (response) {
            const selectedDoctorId = response.doctor_id;
            const selectedServiceId = response.service_id;

            $('<input type="hidden" name="doctor_id">').val(selectedDoctorId).appendTo('form');
            $doctor.prop('disabled', true);

            if (selectedServiceId) {
                $serviceSelect.val(selectedServiceId).trigger('change.select2');
                $serviceSelect.data('old', selectedServiceId);
            }

            loadDoctorsByService(selectedServiceId, selectedDoctorId);

            if (response.expected_start_date) {
                const dt = new Date(response.expected_start_date);
                const dateStr = dt.toISOString().slice(0, 10);
                const timeStr = dt.toISOString().slice(11, 16);
                $dateInput.val(dateStr);
                $slotSelect.html(`<option selected value="${dateStr} ${timeStr}">${timeStr}</option>`);
            }
        }).fail(() => toastr.error('Không thể tải thông tin kế hoạch điều trị'));
    });

    $doctor.on('change', function () {
        const id = $(this).val();
        if (id) {
            loadWorkingDays(id);
        } else {
            $dateInput.val('');
            $slotSelect.empty().append('<option value="">Chọn giờ</option>');
            destroyFlatpickr();
            $('#vacation-notice').addClass('d-none');
        }
    });

    $serviceSelect.on('change', function () {
        const selectedService = $(this).val();
        const selectedOption = $(this).find(':selected');
        const price = selectedOption.data('price') || '';
        $servicePrice.val(price ? price.toLocaleString() + ' ₫' : '');

        if (!$treatmentPlan.val()) {
            loadDoctorsByService(selectedService);
        }
    });

    const fullOldTime = $('#appointment_slot option[selected]').val();
    if (fullOldTime) {
        $slotSelect.data('selected', fullOldTime);
        const dateStr = fullOldTime.split(' ')[0];
        $dateInput.val(dateStr);
    }

    if ($serviceSelect.val()) $serviceSelect.data('old', $serviceSelect.val());

    loadTreatmentPlans(patientId, selectedPlanId);

    setTimeout(() => {
        const selectedPlan = $treatmentPlan.val();
        const selectedDoctorId = window.selectedDoctorId || $doctor.val(); // 👈 đảm bảo có giá trị ban đầu

        if (selectedPlan) {
            $treatmentPlan.trigger('change');
            $doctor.prop('disabled', true);
        } else if ($serviceSelect.val()) {
            loadDoctorsByService($serviceSelect.val(), selectedDoctorId);
            $doctor.prop('disabled', false);
        } else {
            // Nếu không có cả kế hoạch lẫn dịch vụ → reset luôn bác sĩ
            $doctor.html('<option value="">Chọn bác sĩ</option>').trigger('change.select2');
            $doctor.prop('disabled', false);
        }
    }, 300); // 👈 có thể giảm delay nếu dữ liệu DOM đã sẵn sàng





    $('form').on('submit', function (e) {
        const selectedDate = $dateInput.val();
        if (!selectedDate) return;

        const isVacation = vacationDates.includes(selectedDate);
        if (isVacation) {
            e.preventDefault();
            toastr.warning('Bác sĩ đã nghỉ phép vào ngày này. Vui lòng chọn ngày khác.', 'Lưu ý');
            $dateInput.addClass('is-invalid');
            setTimeout(() => $dateInput.removeClass('is-invalid'), 3000);
        }
    });
});
