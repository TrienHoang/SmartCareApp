$(document).ready(function () {
    const $input = $('#patient_name');
    const $hidden = $('#patient_id_hidden');

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
            $input.val(ui.item.label);       // Gán tên vào ô hiển thị
            $hidden.val(ui.item.value);      // Gán ID vào hidden input
            return false;
        }
    });

    // Gán lại tên khi reload nếu có patient_id
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
});
    