function addMedicine() {
    const container = document.getElementById('medicine-list');

    let optionsHtml = '<option value="">-- Chọn thuốc --</option>';
    medicineOptions.forEach(med => {
        optionsHtml += `<option value="${med.id}">
            ${med.name} (${med.unit}) - ${med.formatted_price} – Còn: ${med.stock}
            ${med.stock < 10 ? '⚠️ Cảnh báo: gần hết' : ''}
        </option>`;
    });

    const item = `
        <div class="medicine-item border rounded p-3 mb-3 shadow-sm bg-light position-relative">
            <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-medicine-btn" aria-label="Xóa"></button>
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label">Tên thuốc</label>
                    <select name="medicines[${medicineIndex}][medicine_id]" class="form-select" required>
                        ${optionsHtml}
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Số lượng</label>
                    <input type="number" name="medicines[${medicineIndex}][quantity]"
                        class="form-control" min="1" value="1" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Hướng dẫn sử dụng</label>
                    <textarea name="medicines[${medicineIndex}][usage_instructions]" rows="2"
                        class="form-control" placeholder="Ví dụ: Uống sau ăn"></textarea>
                </div>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', item);
    medicineIndex++;
}

// Gán sự kiện xoá thuốc khi click nút "X"
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-medicine-btn')) {
        e.target.closest('.medicine-item').remove();
    }
});
