$(function () {
    $('#medical_record_id').select2({
        placeholder: 'Tìm hồ sơ theo tên bệnh nhân...',
        ajax: {
            url: medicalRecordSearchUrl,
            dataType: 'json',
            delay: 250,
            data: params => ({ q: params.term }),
            processResults: data => ({ results: data })
        },
        minimumInputLength: 1
    });

    let index = initialMedicineIndex;
    $('#add-medicine').on('click', function () {
        const container = $('#medicines-container');
        const template = $('.medicine-item').first().clone();
        template.attr('data-index', index);

        template.find('select, input').each(function () {
            const name = $(this).attr('name');
            if (name) {
                $(this).attr('name', name.replace(/\[\d+\]/, '[' + index + ']'));
                $(this).val('');
            }
        });

        template.find('.remove-medicine').prop('disabled', false);
        container.append(template);
        index++;
        updateRemoveButtons();
    });

    $(document).on('click', '.remove-medicine', function () {
        $(this).closest('.medicine-item').remove();
        updateRemoveButtons();
    });

    function updateRemoveButtons() {
        const items = $('.medicine-item');
        items.find('.remove-medicine').prop('disabled', items.length === 1);
    }
});
