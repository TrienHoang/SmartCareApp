let selectedFiles = [];

document.querySelectorAll('.category-radio').forEach(radio => {
    radio.addEventListener('change', function () {
        document.querySelectorAll('.category-card').forEach(card => card.classList.remove('selected'));
        this.closest('.category-card').classList.add('selected');

        const customDiv = document.getElementById('customCategoryDiv');
        if (this.value === 'Khác') {
            customDiv.classList.remove('hidden');
            customDiv.querySelector('input').required = true;
        } else {
            customDiv.classList.add('hidden');
            customDiv.querySelector('input').required = false;
            customDiv.querySelector('input').value = ''; // Clear value khi ẩn
        }
    });
});

const dropZone = document.getElementById('dropZone');
const fileInput = document.getElementById('files');
const selectedFilesDiv = document.getElementById('selectedFiles');
const filesList = document.getElementById('filesList');
const errorBox = document.getElementById('errorMessages');
const errorList = document.getElementById('errorList');

dropZone.addEventListener('dragover', e => {
    e.preventDefault();
    dropZone.classList.add('drag-over');
});
dropZone.addEventListener('dragleave', e => {
    e.preventDefault();
    dropZone.classList.remove('drag-over');
});
dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dropZone.classList.remove('drag-over');
    handleFiles(Array.from(e.dataTransfer.files));
});
fileInput.addEventListener('change', () => {
    handleFiles(Array.from(fileInput.files));
});

function handleFiles(files) {
    // Validate file types and sizes
    const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    const maxSize = 10 * 1024 * 1024; // 10MB

    const validFiles = [];
    const errors = [];

    files.forEach(file => {
        if (!allowedTypes.includes(file.type)) {
            errors.push(`File "${file.name}" không đúng định dạng cho phép`);
        } else if (file.size > maxSize) {
            errors.push(`File "${file.name}" vượt quá 10MB`);
        } else {
            validFiles.push(file);
        }
    });

    if (errors.length > 0) {
        showClientErrors(errors);
        return;
    }

    selectedFiles = validFiles;
    updateFileInput();
    displaySelectedFiles();
    clearErrors();
}

function displaySelectedFiles() {
    if (selectedFiles.length === 0) {
        selectedFilesDiv.classList.add('hidden');
        return;
    }
    selectedFilesDiv.classList.remove('hidden');
    filesList.innerHTML = '';
    selectedFiles.forEach((file, index) => {
        const item = document.createElement('div');
        item.className = 'file-preview flex justify-between items-center bg-gray-50 p-4 rounded-xl border border-gray-200 mb-2';
        const icon = getFileIcon(file.name);
        const size = formatFileSize(file.size);
        item.innerHTML = `
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 flex justify-center items-center rounded bg-white shadow"><i class="${icon}"></i></div>
                <div><div class="font-medium">${file.name}</div><div class="text-sm text-gray-500">${size}</div></div>
            </div>
            <button type="button" class="text-red-600 hover:text-red-800" onclick="removeFile(${index})"><i class="fas fa-times"></i></button>`;
        filesList.appendChild(item);
    });
}

function removeFile(index) {
    selectedFiles.splice(index, 1);
    updateFileInput();
    displaySelectedFiles();
}

function updateFileInput() {
    const dt = new DataTransfer();
    selectedFiles.forEach(f => dt.items.add(f));
    fileInput.files = dt.files;
}

function getFileIcon(name) {
    const ext = name.split('.').pop().toLowerCase();
    const map = {
        pdf: 'fas fa-file-pdf text-red-500',
        doc: 'fas fa-file-word text-blue-500',
        docx: 'fas fa-file-word text-blue-500',
        jpg: 'fas fa-file-image text-purple-500',
        jpeg: 'fas fa-file-image text-purple-500',
        png: 'fas fa-file-image text-purple-500',
        gif: 'fas fa-file-image text-purple-500'
    };
    return map[ext] || 'fas fa-file text-gray-500';
}

function formatFileSize(bytes) {
    if (!bytes) return '0B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function showClientErrors(errors) {
    errorBox.classList.remove('hidden');
    errorList.innerHTML = '';
    errors.forEach(error => {
        const li = document.createElement('li');
        li.textContent = error;
        errorList.appendChild(li);
    });
}

function clearErrors() {
    errorBox.classList.add('hidden');
    errorList.innerHTML = '';
}

function resetForm() {
    document.getElementById('uploadForm').reset();
    selectedFiles = [];
    updateFileInput();
    displaySelectedFiles();
    clearErrors();

    // Reset category selection
    document.querySelectorAll('.category-card').forEach(card => card.classList.remove('selected'));
    document.getElementById('customCategoryDiv').classList.add('hidden');
}

document.getElementById('uploadForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    clearErrors();

    // Client-side validation
    const appointmentId = document.getElementById('appointment_id').value;
    if (!appointmentId) {
        Swal.fire({
            icon: 'warning',
            title: 'Chưa chọn lịch hẹn',
            text: 'Vui lòng chọn lịch hẹn liên quan'
        });
        return;
    }

    // Client-side validation
    const category = document.querySelector('input[name="file_category"]:checked');
    if (!category) {
        Swal.fire({
            icon: 'warning',
            title: 'Chưa chọn loại tài liệu',
            text: 'Hãy chọn loại tài liệu trước khi tải lên'
        });
        return;
    }

    // Validate custom category if needed
    if (category.value === 'Khác') {
        const customCategory = document.querySelector('input[name="custom_category"]');
        if (!customCategory || !customCategory.value.trim()) {
            Swal.fire({
                icon: 'warning',
                title: 'Chưa nhập loại tài liệu',
                text: 'Vui lòng nhập loại tài liệu khác'
            });
            return;
        }
    }

    if (selectedFiles.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Chưa chọn file',
            text: 'Bạn cần chọn ít nhất 1 file để tải lên'
        });
        return;
    }

    const form = e.target;
    const formData = new FormData();

    // Add CSRF token
    formData.append('_token', document.querySelector('input[name="_token"]').value);

    // Add form data
    formData.append('file_category', category.value);

    const customCategory = document.querySelector('input[name="custom_category"]');
    if (category.value === 'Khác' && customCategory && customCategory.value.trim()) {
        formData.append('custom_category', customCategory.value.trim());
    }

    formData.append('note', form.note.value || '');
    formData.append('appointment_id', form.appointment_id.value || '');

    // Add files
    selectedFiles.forEach(file => formData.append('files[]', file));

    // Show progress modal
    const modal = document.getElementById('uploadModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const progressDetail = document.getElementById('progressDetail');

    try {
        const response = await fetch(form.dataset.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const data = await response.json();

        if (!response.ok) {
            throw data;
        }

        // Simulate progress
        let progress = 0;
        const interval = setInterval(() => {
            progress += 20;
            if (progressBar) progressBar.style.width = `${progress}%`;
            if (progressText) progressText.innerText = `${progress}% hoàn thành`;
            if (progressDetail) progressDetail.innerText = `${Math.min(Math.ceil(progress / 100 * selectedFiles.length), selectedFiles.length)} / ${selectedFiles.length} file`;

            if (progress >= 100) {
                clearInterval(interval);
                setTimeout(() => {
                    if (modal) {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    }
                    Swal.fire({
                        icon: 'success',
                        title: 'Tải lên thành công',
                        text: data.message || 'Tài liệu đã được tải lên thành công',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        const redirectUrl = form.dataset.successUrl;
                        if (redirectUrl) {
                            window.location.href = redirectUrl;
                        }
                    });
                }, 500);
            }
        }, 200);

    } catch (err) {
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        console.error('Upload error:', err);

        if (err.errors) {
            // Show server validation errors
            errorBox.classList.remove('hidden');
            errorList.innerHTML = '';

            for (const field in err.errors) {
                err.errors[field].forEach(msg => {
                    const li = document.createElement('li');
                    li.textContent = msg;
                    errorList.appendChild(li);
                });
            }
        } else {
            // Show generic error
            Swal.fire({
                icon: 'error',
                title: 'Lỗi tải lên',
                text: err.message || 'Đã có lỗi xảy ra. Vui lòng thử lại.',
            });
        }
    }
});