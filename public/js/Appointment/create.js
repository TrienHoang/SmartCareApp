$(document).ready(function () {
    const $input = $('#patient_name');
    const $hidden = $('#patient_id_hidden');
    const $doctor = $('#doctor_id');
    const $serviceSelect = $('#service_id');
    const $timeInput = $('#appointment_time');
    let flatpickrInstance;

    // Helper function để format ngày tháng
    function formatDateRange(dates) {
        if (!dates || dates.length === 0) return '';
        
        const sortedDates = [...dates].sort();
        const groups = [];
        let currentGroup = [sortedDates[0]];
        
        for (let i = 1; i < sortedDates.length; i++) {
            const prevDate = new Date(sortedDates[i - 1]);
            const currDate = new Date(sortedDates[i]);
            const diffDays = (currDate - prevDate) / (1000 * 60 * 60 * 24);
            
            if (diffDays === 1) {
                currentGroup.push(sortedDates[i]);
            } else {
                groups.push(currentGroup);
                currentGroup = [sortedDates[i]];
            }
        }
        groups.push(currentGroup);
        
        return groups.map(group => {
            if (group.length === 1) {
                return group[0];
            } else if (group.length === 2) {
                return `${group[0]}, ${group[1]}`;
            } else {
                return `${group[0]} đến ${group[group.length - 1]}`;
            }
        }).join(', ');
    }

    // Autocomplete bệnh nhân
    $input.autocomplete({
        source: function (request, response) {
            $.ajax({
                url: '/admin/appointments/patients/search',
                dataType: 'json',
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
            return false;
        }
    });

    // Nếu reload có patient_id → set lại tên
    const oldPatientId = $hidden.val();
    if (oldPatientId) {
        $.ajax({
            url: '/admin/appointments/patients/search',
            data: { q: '' },
            success: function (data) {
                const match = data.find(p => p.id == oldPatientId);
                if (match) {
                    $input.val(match.full_name);
                }
            }
        });
    }

    // Khi chọn bác sĩ
    $doctor.on('change', function () {
        const doctorId = $(this).val();
        $serviceSelect.html('<option value="">Đang tải dịch vụ...</option>');

        if (doctorId) {
            // Load dịch vụ
            $.ajax({
                url: doctorServicesUrl.replace(':id', doctorId),
                type: 'GET',
                dataType: 'json',
                success: function (services) {
                    let options = '<option value="">Chọn dịch vụ</option>';
                    const oldServiceId = $('select[name="service_id"]').data('old');

                    services.forEach(service => {
                        options += `<option value="${service.id}" ${oldServiceId == service.id ? 'selected' : ''}>
                        ${service.name} (${service.department?.name ?? 'Không rõ khoa'})
                    </option>`;
                    });

                    $serviceSelect.html(options).trigger('change.select2');
                },
                error: function(xhr, status, error) {
                    console.error('Lỗi khi tải dịch vụ:', error);
                    toastr.error('Không thể tải danh sách dịch vụ');
                }
            });

            // Load ngày làm việc
            $.ajax({
                url: window.doctorWorkingDaysUrl.replace(':id', doctorId),
                type: 'GET',
                dataType: 'json',
                success: function ({ daysOfWeek, specificDates, vacationDates }) {
                    // Destroy instance cũ trước khi tạo mới
                    if (flatpickrInstance) {
                        flatpickrInstance.destroy();
                        flatpickrInstance = null;
                    }

                    // Clear input value trước khi tạo flatpickr mới (trừ khi có giá trị cũ)
                    const currentValue = $timeInput.val();
                    if (!currentValue && !$timeInput.data('old')) {
                        $timeInput.val('');
                    }

                    // Tạo flatpickr instance mới
                    flatpickrInstance = flatpickr("#appointment_time", {
                        enableTime: true,
                        dateFormat: "Y-m-d H:i",
                        time_24hr: true,
                        minDate: "today",
                        disableMobile: true,
                        locale: 'vi',
                        // Sử dụng enable để chỉ cho phép chọn ngày làm việc
                        enable: specificDates.length > 0 ? specificDates : function(date) {
                            const dayOfWeek = date.getDay();
                            const dateStr = date.toISOString().split('T')[0];
                            
                            // Không cho phép chọn ngày nghỉ phép
                            if (vacationDates.includes(dateStr)) {
                                return false;
                            }
                            
                            // Nếu có specificDates, chỉ enable những ngày đó
                            if (specificDates.length > 0) {
                                return specificDates.includes(dateStr);
                            }
                            
                            // Nếu không có specificDates, dùng daysOfWeek
                            return daysOfWeek.includes(dayOfWeek);
                        },
                        onReady: function (selectedDates, dateStr, instance) {
                            // Sau khi flatpickr ready, mới set giá trị cũ nếu có
                            const oldTime = $timeInput.data('old') || $timeInput.val();
                            if (oldTime) {
                                // Kiểm tra xem thời gian cũ có hợp lệ không
                                const oldDate = new Date(oldTime);
                                const oldDateStr = oldDate.toISOString().split('T')[0];
                                const oldDayOfWeek = oldDate.getDay();
                                
                                let isValidDate = false;
                                
                                // Kiểm tra xem ngày cũ có trong danh sách ngày có thể chọn không
                                if (specificDates.length > 0) {
                                    isValidDate = specificDates.includes(oldDateStr);
                                } else {
                                    isValidDate = daysOfWeek.includes(oldDayOfWeek);
                                }
                                
                                // Kiểm tra không phải ngày nghỉ phép
                                if (isValidDate && !vacationDates.includes(oldDateStr)) {
                                    instance.setDate(oldTime, true);
                                    $timeInput.val(oldTime); // Đảm bảo giá trị được set trong input
                                }
                            }
                        },
                        onChange: function(selectedDates, dateStr, instance) {
                            // Kiểm tra xem ngày đã chọn có hợp lệ không
                            if (selectedDates.length > 0) {
                                const selectedDate = selectedDates[0].toISOString().split('T')[0];
                                if (vacationDates.includes(selectedDate)) {
                                    toastr.warning('Bác sĩ nghỉ phép vào ngày này, vui lòng chọn ngày khác');
                                    instance.clear();
                                    return;
                                }
                            }
                        }
                    });

                    // Enable input sau khi tạo flatpickr
                    $timeInput.prop('disabled', false);
                    
                    // Hiển thị thông báo về lịch nghỉ nếu có
                    const $vacationNotice = $('#vacation-notice');
                    const $vacationText = $('#vacation-text');
                    
                    if (vacationDates && vacationDates.length > 0) {
                        const vacationInfo = formatDateRange(vacationDates);
                        $vacationText.text(`Bác sĩ nghỉ phép: ${vacationInfo}`);
                        $vacationNotice.removeClass('d-none');
                        
                        toastr.info(`Bác sĩ nghỉ phép: ${vacationInfo}`, 'Thông tin lịch nghỉ', {
                            timeOut: 8000,
                            extendedTimeOut: 3000
                        });
                    } else {
                        $vacationNotice.addClass('d-none');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Lỗi khi tải lịch làm việc:', error);
                    toastr.error('Không thể tải lịch làm việc của bác sĩ');
                    $timeInput.prop('disabled', true);
                    
                    // Destroy flatpickr nếu có lỗi
                    if (flatpickrInstance) {
                        flatpickrInstance.destroy();
                        flatpickrInstance = null;
                    }
                }
            });
        } else {
            // Reset service select
            $serviceSelect.html('<option value="">Chọn dịch vụ</option>').trigger('change.select2');
            
            // Disable và clear time input
            $timeInput.prop('disabled', true).val('');
            
            // Destroy flatpickr instance
            if (flatpickrInstance) {
                flatpickrInstance.destroy();
                flatpickrInstance = null;
            }
            
            // Ẩn thông báo nghỉ phép
            $('#vacation-notice').addClass('d-none');
        }
    });

    // Khi reload lại form lỗi validate
    const oldDoctorId = $doctor.val();
    const oldServiceId = $serviceSelect.val();
    const oldTime = $timeInput.val();

    // Gán các old giá trị vào data-* để dùng khi ajax load xong
    if (oldServiceId) {
        $serviceSelect.data('old', oldServiceId);
    }

    if (oldTime) {
        $timeInput.data('old', oldTime);
    }

    // Lưu giá trị hiện tại của input trước khi trigger change
    const currentTimeValue = $timeInput.val();
    if (currentTimeValue) {
        $timeInput.data('old', currentTimeValue);
    }

    if (oldDoctorId) {
        $doctor.trigger('change');
    }

    // Xử lý khi form được submit để đảm bảo thời gian không bị mất
    $('form').on('submit', function(e) {
        const timeValue = $timeInput.val();
        if (timeValue && flatpickrInstance) {
            // Đảm bảo giá trị được set chính xác
            $timeInput.val(timeValue);
        }
    });
});