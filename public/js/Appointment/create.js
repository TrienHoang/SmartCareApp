$(document).ready(function () {
    $('#patient_id').select2({
        placeholder: 'Tìm bệnh nhân theo tên...',
        width: '100%',
        ajax: {
            url: '/admin/appointments/patients/search',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data.map(patient => ({
                        id: patient.id,
                        text: patient.full_name
                    }))
                };
            },
            cache: true
        },
        minimumInputLength: 1
    });

    $(document).on('select2:open', () => {
        let select2SearchField = document.querySelector('.select2-container--open .select2-search__field');
        if (select2SearchField) {
            select2SearchField.focus();
        }
    });

    let oldPatientId = $('meta[name="old-patient-id"]').attr('content');
    if (oldPatientId) {
        $.ajax({
            url: '/admin/appointments/patients/search',
            data: { q: '' },
            success: function (data) {
                let match = data.find(p => p.id == oldPatientId);
                if (match) {
                    let option = new Option(match.full_name, match.id, true, true);
                    $('#patient_id').append(option).trigger('change');
                }
            }
        });
    }
});
