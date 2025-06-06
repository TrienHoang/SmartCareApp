let medicineIndex = 1;

function addMedicine() {
    const template = document.getElementById('medicine-template').innerHTML;
    const newItem = template.replace(/__index__/g, medicineIndex);
    document.getElementById('medicine-list').insertAdjacentHTML('beforeend', newItem);
    medicineIndex++;
}