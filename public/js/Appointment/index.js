function updateStatus(appointmentId, currentStatus) {
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    const form = document.getElementById('statusForm');
    const statusSelect = document.getElementById('statusSelect');
    const currentStatusInput = document.getElementById('currentStatusInput');

    form.action = `/admin/appointments/${appointmentId}/update-status`;
    currentStatusInput.value = currentStatus;

    for (let option of statusSelect.options) {
        option.disabled = true;

        if (currentStatus === 'pending') {
            if (['confirmed', 'cancelled'].includes(option.value)) {
                option.disabled = false;
            }
        }

        if (currentStatus === 'confirmed') {
            if (['completed', 'cancelled'].includes(option.value)) {
                option.disabled = false;
            }
        }

        if (currentStatus === 'completed' || currentStatus === 'cancelled') {
            if (option.value === currentStatus) {
                option.disabled = false;
            }
        }
    }

    statusSelect.value = currentStatus;
    modal.show();
}

function cancelAppointment(appointmentId) {
    if (confirm('Bạn có chắc chắn muốn hủy lịch hẹn này?')) {
        updateStatus(appointmentId, 'cancelled');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    setInterval(function () {
        if (document.visibilityState === 'visible') {
            window.location.reload();
        }
    }, 300000);
});

function changePagination(perPage) {
    const url = new URL(window.location);
    url.searchParams.set('per_page', perPage);
    window.location.href = url.toString();
}

function showCancelModal(appointmentId) {
    const modal = new bootstrap.Modal(document.getElementById('cancelModal'));
    const form = document.getElementById('cancelForm');
    form.action = `/admin/appointments/${appointmentId}/cancel`;
    modal.show();
}
