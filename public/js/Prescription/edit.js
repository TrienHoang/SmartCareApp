function addMedicine() {
    const container = document.getElementById('medicine-list');

    // Tạo dropdown option từ biến medicineOptions
    let optionsHtml = '<option value="">-- Chọn thuốc --</option>';
    medicineOptions.forEach(med => {
        optionsHtml += `<option value="${med.id}">${med.name} (${med.unit})</option>`;
    });

    const item = `
        <div class="medicine-item border p-2 mb-2">
            <select name="medicines[${medicineIndex}][medicine_id]" class="form-select mb-2" required>
                ${optionsHtml}
            </select>

            <input type="number" name="medicines[${medicineIndex}][quantity]"
                class="form-control mb-2" placeholder="Số lượng" min="1" required>

            <textarea name="medicines[${medicineIndex}][usage_instructions]" class="form-control" placeholder="Hướng dẫn sử dụng"></textarea>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', item);
    medicineIndex++;
}
