@extends('doctor.dashboard')

@section('title', 'Tải lên File')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center space-x-2 text-gray-600 mb-2">
                    <a href="{{ route('doctor.files.index') }}" class="hover:text-blue-600">
                        <i class="fas fa-folder"></i> Quản lý File
                    </a>
                    <i class="fas fa-chevron-right text-sm"></i>
                    <span>Tải lên File</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Tải lên File mới</h1>
                <p class="text-gray-600 mt-1">Tải lên tài liệu và hình ảnh cho bệnh nhân</p>
            </div>

            <!-- Upload Form -->
            <div class="bg-white rounded-lg shadow-sm border">
                <form method="POST" action="{{ route('doctor.files.store') }}" enctype="multipart/form-data"
                    id="uploadForm">
                    @csrf

                    <div class="p-6 space-y-6">
                        <!-- Appointment Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Cuộc hẹn <span class="text-red-500">*</span>
                            </label>
                            <select name="appointment_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 @error('appointment_id') border-red-500 @enderror">
                                <option value="">Chọn cuộc hẹn...</option>
                                @foreach ($appointments as $appointment)
                                    <option value="{{ $appointment->id }}"
                                        {{ old('appointment_id') == $appointment->id || ($selectedAppointment && $selectedAppointment->id == $appointment->id) ? 'selected' : '' }}>
                                        {{ $appointment->patient->full_name ?? 'N/A' }} -
                                        {{ $appointment->appointment_time->format('d/m/Y H:i') }}
                                        ({{ ucfirst($appointment->status) }})
                                    </option>
                                @endforeach
                            </select>
                            <div id="appointmentFiles" class="mt-4 hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Các file đã tải lên cho cuộc hẹn
                                    này:</label>
                                <ul class="list-disc list-inside text-sm text-gray-600 space-y-1" id="appointmentFilesList">
                                    <!-- AJAX sẽ chèn dữ liệu ở đây -->
                                </ul>
                            </div>
                            @error('appointment_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- File Category -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Danh mục File <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3">
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="file_category" value="Kết quả xét nghiệm"
                                        {{ old('file_category') == 'Kết quả xét nghiệm' ? 'checked' : '' }}
                                        class="text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm">Kết quả xét nghiệm</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="file_category" value="Hình ảnh y tế"
                                        {{ old('file_category') == 'Hình ảnh y tế' ? 'checked' : '' }}
                                        class="text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm">Hình ảnh y tế</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="file_category" value="Đơn thuốc"
                                        {{ old('file_category') == 'Đơn thuốc' ? 'checked' : '' }}
                                        class="text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm">Đơn thuốc</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="file_category" value="Báo cáo khám"
                                        {{ old('file_category') == 'Báo cáo khám' ? 'checked' : '' }}
                                        class="text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm">Báo cáo khám</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="file_category" value="Giấy tờ"
                                        {{ old('file_category') == 'Giấy tờ' ? 'checked' : '' }}
                                        class="text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm">Giấy tờ</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="file_category" value="Khác"
                                        {{ old('file_category') == 'Khác' ? 'checked' : '' }}
                                        class="text-blue-600 focus:ring-blue-500" id="categoryOther">
                                    <span class="text-sm">Khác</span>
                                </label>
                            </div>

                            <!-- Custom Category Input -->
                            <div id="customCategoryDiv" class="hidden">
                                <input type="text" name="custom_category" placeholder="Nhập danh mục khác..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                            </div>
                            @error('file_category')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- File Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Chọn File <span class="text-red-500">*</span>
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors"
                                id="dropZone">
                                <div id="uploadArea">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-lg text-gray-600 mb-2">Kéo thả file vào đây hoặc</p>
                                    <label for="files"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg cursor-pointer">
                                        Chọn File
                                    </label>
                                    <input type="file" name="files[]" id="files" multiple
                                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif" class="hidden" required>
                                    <p class="text-sm text-gray-500 mt-2">
                                        Hỗ trợ: PDF, DOC, DOCX, JPG, JPEG, PNG, GIF (Tối đa 10MB mỗi file)
                                    </p>
                                </div>
                            </div>
                            @error('files.*')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Selected Files Preview -->
                        <div id="selectedFiles" class="hidden">
                            <h3 class="text-sm font-medium text-gray-700 mb-3">File đã chọn:</h3>
                            <div id="filesList" class="space-y-2"></div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Ghi chú (không bắt buộc)
                            </label>
                            <textarea name="description" rows="3" placeholder="Mô tả ngắn về file..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="border-t bg-gray-50 px-6 py-4 flex justify-between items-center">
                        <a href="{{ route('doctor.files.index') }}" class="text-gray-600 hover:text-gray-800">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                        <div class="flex space-x-3">
                            <button type="button" onclick="resetForm()"
                                class="px-4 py-2 text-gray-600 hover:text-gray-800 border border-gray-300 rounded-md">
                                Đặt lại
                            </button>
                            <button type="submit" id="submitBtn"
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md flex items-center space-x-2">
                                <i class="fas fa-upload"></i>
                                <span>Tải lên</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Upload Progress Modal -->
    <div id="uploadModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96">
            <h3 class="text-lg font-semibold mb-4 text-center">Đang tải lên...</h3>
            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                <div id="progressBar" class="bg-blue-600 h-2.5 rounded-full transition-all duration-300"
                    style="width: 0%"></div>
            </div>
            <p id="progressText" class="text-center text-gray-600">Đang chuẩn bị...</p>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        let selectedFiles = [];

        // Category selection logic
        document.querySelectorAll('input[name="file_category"]').forEach(radio => {
            radio.addEventListener('change', function() {
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
            dropZone.classList.add('border-blue-400', 'bg-blue-50');
        }

        function handleDragLeave(e) {
            e.preventDefault();
            dropZone.classList.remove('border-blue-400', 'bg-blue-50');
        }

        function handleDrop(e) {
            e.preventDefault();
            dropZone.classList.remove('border-blue-400', 'bg-blue-50');

            const files = Array.from(e.dataTransfer.files);
            handleFiles(files);
        }

        fileInput.addEventListener('change', function() {
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
                fileItem.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-md';

                const fileInfo = document.createElement('div');
                fileInfo.className = 'flex items-center space-x-3';

                const icon = getFileIcon(file.name);
                const size = formatFileSize(file.size);

                fileInfo.innerHTML = `
            <i class="${icon}"></i>
            <div>
                <p class="font-medium text-gray-900">${file.name}</p>
                <p class="text-sm text-gray-500">${size}</p>
            </div>
        `;

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'text-red-600 hover:text-red-800';
                removeBtn.innerHTML = '<i class="fas fa-times"></i>';
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
        }

        // Form submission with progress
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();

            updateFileInput(); // BẮT BUỘC có dòng này

            if (selectedFiles.length === 0) {
                alert('Vui lòng chọn ít nhất một file để tải lên');
                return;
            }

            const categoryOther = document.getElementById('categoryOther');
            if (categoryOther && categoryOther.checked) {
                const customCategory = document.querySelector('input[name="custom_category"]').value;
                if (!customCategory.trim()) {
                    alert('Vui lòng nhập danh mục khác');
                    return;
                }
                categoryOther.value = customCategory;
            }

            const formData = new FormData(this);

            // Hiện modal
            const uploadModal = document.getElementById('uploadModal');
            uploadModal.classList.remove('hidden');
            uploadModal.classList.add('flex');

            const xhr = new XMLHttpRequest();

            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percentComplete = (e.loaded / e.total) * 100;
                    document.getElementById('progressBar').style.width = percentComplete + '%';
                    document.getElementById('progressText').textContent = Math.round(percentComplete) +
                        '% hoàn thành';
                }
            });

            xhr.addEventListener('load', function() {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success && response.redirect_url) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });

                        // Sau 1 giây redirect
                        setTimeout(() => {
                            window.location.href = response.redirect_url;
                        }, 1000);

                    } else {
                        alert(response.message || 'Có lỗi xảy ra khi tải file');
                        document.getElementById('uploadModal').classList.add('hidden');
                    }
                } catch (e) {
                    alert('Phản hồi không hợp lệ từ server');
                    document.getElementById('uploadModal').classList.add('hidden');
                }
            });


            xhr.open('POST', this.action);
            // xhr.setRequestHeader(...) => Có thể bỏ nếu bạn đã dùng @csrf trong form
            xhr.send(formData);
        });
        const appointmentSelect = document.querySelector('select[name="appointment_id"]');
        const filesContainer = document.getElementById('appointmentFiles');
        const appointmentFilesList = document.getElementById('appointmentFilesList');

        const baseUrl = "{{ route('doctor.files.byAppointment', ['appointmentId' => '__ID__']) }}";

        appointmentSelect.addEventListener('change', function() {
            const appointmentId = this.value;

            if (!appointmentId) {
                filesContainer.classList.add('hidden');
                appointmentFilesList.innerHTML = '';
                return;
            }

            const url = baseUrl.replace('__ID__', appointmentId);

            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error('Không thể lấy danh sách file.');
                    return response.json();
                })
                .then(data => {
                    if (!data.files || data.files.length === 0) {
                        appointmentFilesList.innerHTML =
                            `<li class="text-gray-500 italic">Không có file nào được tải lên cho cuộc hẹn này.</li>`;
                    } else {
                        appointmentFilesList.innerHTML = '';
                        data.files.forEach(file => {
                            const formattedTime = new Date(file.uploaded_at).toLocaleString('vi-VN', {
                                timeZone: 'Asia/Ho_Chi_Minh',
                                year: 'numeric',
                                month: '2-digit',
                                day: '2-digit',
                                hour: '2-digit',
                                minute: '2-digit',
                                second: '2-digit'
                            });
                            const item = document.createElement('li');
                            item.innerHTML =
                                `<strong>${file.file_name}</strong> <span class="text-gray-500">(${file.file_category})</span> - <span class="text-gray-400 text-xs">${formattedTime}</span>`;
                            appointmentFilesList.appendChild(item);
                        });
                    }
                    filesContainer.classList.remove('hidden');
                })
                .catch(err => {
                    console.error(err);
                    appointmentFilesList.innerHTML =
                        `<li class="text-red-500 italic">Lỗi khi tải danh sách file.</li>`;
                    filesContainer.classList.remove('hidden');
                });
        });
    </script>
@endpush
