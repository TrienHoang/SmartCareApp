$(document).ready(function () {
    const $input = $('#patient_name');
    const $hidden = $('#patient_id_hidden');
    const $doctor = $('#doctor_id');
    const $serviceSelect = $('#service_id');
    const $servicePrice = $('#service_price');
    const $treatmentPlan = $('#treatment_plan_id');
    const $treatmentWrapper = $('#treatment-plan-wrapper');
    const $vacationNotice = $('#vacation-notice');
    const $vacationText = $('#vacation-text');
    const $dateInput = $('#appointment_date');
    const $slotSelect = $('#appointment_slot');
    let flatpickrInstance;
    let vacationDates = [];

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

            $treatmentPlan.val('').trigger('change');
            $doctor.val('').trigger('change.select2');
            $serviceSelect.val('').trigger('change.select2');
            $slotSelect.empty().append(`<option value="">Chọn giờ</option>`);
            $dateInput.val('');
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
            $serviceSelect.val('').trigger('change.select2');
            $doctor.html('<option value="">Chọn bác sĩ</option>').trigger('change.select2');
            $doctor.removeData('old');
            $slotSelect.empty().append(`<option value="">Chọn giờ</option>`);
            $dateInput.val('');
            return;
        }

        const planUrl = window.treatmentPlanDetailsUrl.replace('__ID__', planId);

        $.get(planUrl, function (data) {
            const { doctor_id, service_id } = data;

            $serviceSelect.val(service_id).trigger('change');
            $doctor.data('old', doctor_id);
        }).fail(() => toastr.error('Không thể tải thông tin kế hoạch điều trị'));
    });

    $serviceSelect.on('change', function () {
        const serviceId = $(this).val();
        const price = $(this).find(':selected').data('price') || '';
        $servicePrice.val(price ? price.toLocaleString() + ' VND' : '');

        $doctor.html('<option value="">Đang tải bác sĩ...</option>');

        if (!serviceId) {
            $doctor.html('<option value="">Chọn bác sĩ</option>').trigger('change.select2');
            $dateInput.val('');
            $slotSelect.empty().append(`<option value="">Chọn giờ</option>`);
            return;
        }

        $.get(`/admin/appointments/services/${serviceId}/doctors`, function (doctors) {
            const oldDoctorId = $doctor.data('old');
            const options = doctors.map(doc =>
                `<option value="${doc.id}" ${oldDoctorId == doc.id ? 'selected' : ''}>
                    ${doc.user.full_name}
                </option>`
            );
            $doctor.html('<option value="">Chọn bác sĩ</option>' + options.join(''));

            if (oldDoctorId) {
                $doctor.val(oldDoctorId).trigger('change.select2');
                $doctor.trigger('change');
            } else {
                $doctor.trigger('change.select2');
            }
        });
    });

    $doctor.on('change', function () {
        const doctorId = $(this).val();
        $slotSelect.empty().append(`<option value="">Chọn giờ</option>`);
        $dateInput.val('');

        if (!doctorId) {
            $dateInput.val('');
            return;
        }

        $.get(window.doctorWorkingDaysUrl.replace(':id', doctorId), function ({ daysOfWeek, specificDates, vacationDates: vacations }) {
            vacationDates = vacations || [];

            if (flatpickrInstance) flatpickrInstance.destroy();

            flatpickrInstance = flatpickr("#appointment_date", {
                dateFormat: "Y-m-d",
                minDate: "today",
                locale: "vi",
                disableMobile: true,
                disable: [
                    function (date) {
                        const str = flatpickr.formatDate(date, 'Y-m-d');
                        return date.getDay() === 0 || vacationDates.includes(str);
                    }
                ],
                onDayCreate: function (_, __, fp, dayElem) {
                    const date = flatpickr.formatDate(dayElem.dateObj, 'Y-m-d');
                    dayElem.classList.remove('vacation-day');
                    dayElem.removeAttribute('title');

                    if (vacationDates.includes(date)) {
                        dayElem.classList.add('flatpickr-disabled', 'vacation-day');
                        dayElem.setAttribute('title', 'Bác sĩ nghỉ phép');
                    }
                },
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
                $vacationText.text(`Bác sĩ nghỉ phép: ${info}`);
                $vacationNotice.removeClass('d-none');
                toastr.info(`Bác sĩ nghỉ phép: ${info}`, 'Thông báo');
            } else {
                $vacationNotice.addClass('d-none');
            }
        }).fail(() => {
            toastr.error('Không thể tải lịch làm việc');
            if (flatpickrInstance) flatpickrInstance.destroy();
        });
    });

    function loadAvailableSlots(doctorId, dateStr) {
        const url = window.doctorAvailableTimesUrl.replace('__DOCTOR__', doctorId) +
            '?date=' + encodeURIComponent(dateStr) +
            '&service_id=' + encodeURIComponent($serviceSelect.val() || '') +
            '&treatment_plan_id=' + encodeURIComponent($treatmentPlan.val() || '');

        // 👉 CHÈN ĐOẠN DƯỚI VÀO ĐÂY
        $.get(url, function (slots) {
            console.log('Slots:', slots); // ✅ log kết quả server trả về

            $slotSelect.empty().append(`<option value="">Chọn giờ</option>`);
            if (slots.length === 0) {
                $slotSelect.append(`<option disabled>Không có giờ trống</option>`);
            } else {
                slots.forEach(time => {
                    $slotSelect.append(`<option value="${dateStr} ${time}">${time}</option>`);
                });
            }
        }).fail((jqXHR, textStatus, errorThrown) => {
            console.error('Error:', textStatus, errorThrown); // ✅ log thông báo lỗi
            console.log('Response:', jqXHR.responseText); // ✅ log nội dung lỗi (ví dụ: 500 server error)
            toastr.error('Không thể tải giờ khám');
        });
    }


    // Reload cũ
    const oldServiceId = $serviceSelect.val();
    const oldDoctorId = $doctor.val();
    const oldDate = $dateInput.val();
    if (oldServiceId) $serviceSelect.trigger('change');
    if (oldDoctorId) $doctor.data('old', oldDoctorId);
    if (oldDate) $dateInput.val(oldDate);
});
