function addMedicine() {
    const container = document.getElementById('medicine-list');
    const currentCount = container.querySelectorAll('.medicine-item').length;

    const item = document.createElement('div');
    item.className = 'medicine-item medicine-item-new';
    item.setAttribute('data-index', medicineIndex);

    item.innerHTML = `
        <div class="medicine-item-header">
            <div class="medicine-item-title">
                <div class="medicine-item-number">${currentCount + 1}</div>
                Thuốc ${currentCount + 1}
            </div>
            <button type="button" class="remove-medicine-btn" aria-label="Xóa thuốc">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="row g-3">
            <div class="col-md-5">
                <label class="form-label">
                    <i class="fas fa-prescription-bottle"></i>
                    Tên thuốc
                </label>
                <input type="text" name="medicines[${medicineIndex}][medicine_name]"
                    class="form-control medicine-autocomplete" placeholder="Nhập tên thuốc…">
                <input type="hidden" name="medicines[${medicineIndex}][medicine_id]" class="medicine-hidden-id">
            </div>
            <div class="col-md-2">
                <label class="form-label">
                    <i class="fas fa-sort-numeric-up"></i>
                    Số lượng
                </label>
                <input type="number" name="medicines[${medicineIndex}][quantity]"
                    class="form-control" value="1" min="1" max="1000">
            </div>
            <div class="col-md-5">
                <label class="form-label">
                    <i class="fas fa-instructions"></i>
                    Hướng dẫn sử dụng
                </label>
                <textarea name="medicines[${medicineIndex}][usage_instructions]" rows="2"
                    class="form-control" placeholder="Ví dụ: Uống sau ăn, ngày 2 lần"></textarea>
            </div>
        </div>
    `;

    container.appendChild(item);

    initAutocomplete(item);

    medicineIndex++;
    updateMedicineNumbers();
    showNotification('Đã thêm thuốc mới', 'success');
}

function initAutocomplete(container) {
    const $input = $(container).find('.medicine-autocomplete');
    const $hidden = $(container).find('.medicine-hidden-id');

    $input.autocomplete({
        source: function (request, response) {
            $.ajax({
                url: `/admin/prescriptions/medicines/search`,
                dataType: 'json',
                data: { q: request.term },
                success: function (data) {
                    response(data.map(item => ({
                        label: item.text,
                        value: item.id
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
}

function updateMedicineNumbers() {
    const medicineItems = document.querySelectorAll('.medicine-item');
    medicineItems.forEach((item, index) => {
        const numberElement = item.querySelector('.medicine-item-number');
        if (numberElement) {
            numberElement.textContent = index + 1;
        }
    });
}

document.addEventListener('click', function (e) {
    if (e.target.closest('.remove-medicine-btn')) {
        const medicineItem = e.target.closest('.medicine-item');
        medicineItem.remove();
        updateMedicineNumbers();
        showNotification('Đã xóa thuốc', 'success');
    }
});
