
let selectedFiles = [];

// Category selection logic
document.querySelectorAll('.category-radio').forEach(radio => {
    radio.addEventListener('change', function () {
        // Remove selected class from all cards
        document.querySelectorAll('.category-card').forEach(card => {
            card.classList.remove('selected');
        });

        // Add selected class to current card
        this.closest('.category-card').classList.add('selected');

        const customDiv = document.getElementById('customCategoryDiv');
        if (this.value === 'Khác') {
            customDiv.classList.remove('hidden');
            customDiv.querySelector('input').required = true;
        } else {
            customDiv.classList.add('hidden');
            customDiv.querySelector('input').required = false;
        }
    });
});

// File handling
const dropZone = document.getElementById('dropZone');
const fileInput = document.getElementById('files');
const selectedFilesDiv = document.getElementById('selectedFiles');
const filesList = document.getElementById('filesList');

// Drag and drop handlers
dropZone.addEventListener('dragover', handleDragOver);
dropZone.addEventListener('drop', handleDrop);
dropZone.addEventListener('dragleave', handleDragLeave);

function handleDragOver(e) {
    e.preventDefault();
    dropZone.classList.add('drag-over');
}

function handleDragLeave(e) {
    e.preventDefault();
    if (!dropZone.contains(e.relatedTarget)) {
        dropZone.classList.remove('drag-over');
    }
}

function handleDrop(e) {
    e.preventDefault();
    dropZone.classList.remove('drag-over');
    const files = Array.from(e.dataTransfer.files);
    handleFiles(files);
}

fileInput.addEventListener('change', function () {
    const files = Array.from(this.files);
    handleFiles(files);
});

function handleFiles(files) {
    selectedFiles = files;
    displaySelectedFiles();
}

function displaySelectedFiles() {
    if (selectedFiles.length === 0) {
        selectedFilesDiv.classList.add('hidden');
        return;
    }

    selectedFilesDiv.classList.remove('hidden');
    filesList.innerHTML = '';

    selectedFiles.forEach((file, index) => {
        const fileItem = document.createElement('div');
        fileItem.className =
            'file-preview flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-200 hover:shadow-md transition-all';

        const fileInfo = document.createElement('div');
        fileInfo.className = 'flex items-center space-x-4';

        const icon = getFileIcon(file.name);
        const size = formatFileSize(file.size);

        fileInfo.innerHTML = `
                    <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center shadow-sm">
                        <i class="${icon} text-lg"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">${file.name}</p>
                        <p class="text-sm text-gray-500">${size}</p>
                    </div>
                `;

        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className =
            'w-8 h-8 bg-red-100 text-red-600 rounded-full hover:bg-red-200 transition-colors flex items-center justify-center';
        removeBtn.innerHTML = '<i class="fas fa-times text-sm"></i>';
        removeBtn.onclick = () => removeFile(index);

        fileItem.appendChild(fileInfo);
        fileItem.appendChild(removeBtn);
        filesList.appendChild(fileItem);
    });
}

function removeFile(index) {
    selectedFiles.splice(index, 1);
    updateFileInput();
    displaySelectedFiles();
}

function updateFileInput() {
    const dt = new DataTransfer();
    selectedFiles.forEach(file => dt.items.add(file));
    fileInput.files = dt.files;
}

function getFileIcon(fileName) {
    const extension = fileName.split('.').pop().toLowerCase();
    const iconMap = {
        'pdf': 'fas fa-file-pdf text-red-500',
        'doc': 'fas fa-file-word text-blue-500',
        'docx': 'fas fa-file-word text-blue-500',
        'jpg': 'fas fa-file-image text-purple-500',
        'jpeg': 'fas fa-file-image text-purple-500',
        'png': 'fas fa-file-image text-purple-500',
        'gif': 'fas fa-file-image text-purple-500'
    };
    return iconMap[extension] || 'fas fa-file text-gray-500';
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function resetForm() {
    document.getElementById('uploadForm').reset();
    selectedFiles = [];
    selectedFilesDiv.classList.add('hidden');
    document.getElementById('customCategoryDiv').classList.add('hidden');
    document.querySelectorAll('.category-card').forEach(card => {
        card.classList.remove('selected');
    });
}

// Form submission with progress
document.getElementById('uploadForm').addEventListener('submit', function (e) {
    e.preventDefault();

    // Validation
    if (selectedFiles.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Chưa chọn file',
            text: 'Vui lòng chọn ít nhất một file để tải lên',
            confirmButtonColor: '#3b82f6'
        });
        return;
    }

    const categorySelected = document.querySelector('input[name="file_category"]:checked');
    if (!categorySelected) {
        Swal.fire({
            icon: 'warning',
            title: 'Chưa chọn loại tài liệu',
            text: 'Vui lòng chọn phân loại cho tài liệu',
            confirmButtonColor: '#3b82f6'
        });
        return;
    }

    // Show upload modal
    const uploadModal = document.getElementById('uploadModal');
    uploadModal.classList.remove('hidden');
    uploadModal.classList.add('flex');

    // Simulate upload progress
    let progress = 0;
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const progressDetail = document.getElementById('progressDetail');

    const uploadInterval = setInterval(() => {
        progress += Math.random() * 15;
        if (progress > 100) progress = 100;

        progressBar.style.width = progress + '%';
        progressText.textContent = Math.round(progress) + '% hoàn thành';
        progressDetail.textContent =
            `${Math.min(Math.ceil(progress / 100 * selectedFiles.length), selectedFiles.length)} / ${selectedFiles.length} file`;

        if (progress >= 100) {
            clearInterval(uploadInterval);
            setTimeout(() => {
                uploadModal.classList.add('hidden');
                uploadModal.classList.remove('flex');

                Swal.fire({
                    icon: 'success',
                    title: 'Tải lên thành công!',
                    text: 'Tài liệu đã được tải lên và lưu trữ an toàn',
                    confirmButtonColor: '#3b82f6',
                    timer: 3000,
                    timerProgressBar: true
                }).then(() => {
                    // Redirect or reload
                    window.location.href = '#';
                });
            }, 500);
        }
    }, 200);
});
