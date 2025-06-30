@extends('doctor.dashboard')

@section('title', 'Quản lý File')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Quản lý File</h1>
                <p class="text-gray-600 mt-1">Quản lý tài liệu và hình ảnh của bệnh nhân</p>
            </div>
            <a href="#"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
                <i class="fas fa-plus"></i>
                <span>Tải lên File</span>
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border p-4 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tìm kiếm</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Tên file..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Danh mục</label>
                    <select name="category"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">Tất cả danh mục</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Appointment Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cuộc hẹn</label>
                    <select name="appointment_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">Tất cả cuộc hẹn</option>
                        @foreach ($appointments as $appointment)
                            <option value="{{ $appointment->id }}"
                                {{ request('appointment_id') == $appointment->id ? 'selected' : '' }}>
                                {{ $appointment->patient->full_name ?? 'N/A' }} -
                                {{ $appointment->appointment_time->format('d/m/Y H:i') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit -->
                <div class="flex items-end">
                    <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md mr-2">
                        <i class="fas fa-search"></i> Lọc
                    </button>
                    <a href="{{ route('doctor.files.index') }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md">
                        <i class="fas fa-times"></i> Xóa lọc
                    </a>
                </div>
            </form>
        </div>

        <!-- Files Grid -->
        @if ($files->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($files as $file)
                    <div class="bg-white rounded-lg shadow-sm border hover:shadow-md transition-shadow">
                        <!-- File Preview -->
                        <div class="p-4 border-b">
                            @if ($file->is_image && $file->file_exists)
                                <div class="aspect-w-16 aspect-h-9 mb-3">
                                    <img src="{{ $file->file_url }}" alt="{{ $file->file_name }}"
                                        class="w-full h-32 object-cover rounded-md">
                                </div>
                            @else
                                <div class="flex items-center justify-center h-32 bg-gray-50 rounded-md">
                                    <i class="{{ $file->file_icon }} text-4xl"></i>
                                </div>
                            @endif
                        </div>

                        <!-- File Info -->
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 truncate mb-2" title="{{ $file->file_name }}">
                                {{ $file->file_name }}
                            </h3>

                            <div class="space-y-2 text-sm text-gray-600">
                                <div class="flex justify-between">
                                    <span>Danh mục:</span>
                                    <span class="font-medium">{{ $file->file_category }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Kích thước:</span>
                                    <span>{{ $file->file_size_formatted }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Bệnh nhân:</span>
                                    <span class="font-medium">{{ $file->appointment->patient->full_name ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Ngày tải:</span>
                                    <span>{{ $file->uploaded_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>

                            <!-- Status -->
                            @if (!$file->file_exists)
                                <div class="mt-3 px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full inline-block">
                                    <i class="fas fa-exclamation-triangle"></i> File không tồn tại
                                </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="p-4 border-t bg-gray-50 flex justify-between items-center">
                            <div class="flex space-x-2">
                                <a href="#"
                                    class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if ($file->file_exists)
                                    <a href="#"
                                        class="text-green-600 hover:text-green-800">
                                        <i class="fas fa-download"></i>
                                    </a>
                                @endif
                            </div>

                            <div class="flex space-x-2">
                                <button onclick="editCategory({{ $file->id }}, '{{ $file->file_category }}')"
                                    class="text-orange-600 hover:text-orange-800">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteFile({{ $file->id }})" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $files->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-folder-open text-6xl text-gray-400 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Chưa có file nào</h3>
                <p class="text-gray-600 mb-4">Bắt đầu bằng cách tải lên file đầu tiên của bạn</p>
                <a href="{{ route('doctor.files.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                    Tải lên File
                </a>
            </div>
        @endif
    </div>

    <!-- Edit Category Modal -->
    <div id="editCategoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96">
            <h3 class="text-lg font-semibold mb-4">Chỉnh sửa danh mục</h3>
            <form id="editCategoryForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Danh mục</label>
                    <input type="text" id="categoryInput"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                        required>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800 border border-gray-300 rounded-md">
                        Hủy
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                        Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96">
            <h3 class="text-lg font-semibold mb-4 text-red-600">
                <i class="fas fa-exclamation-triangle"></i> Xác nhận xóa
            </h3>
            <p class="text-gray-600 mb-4">Bạn có chắc chắn muốn xóa file này? Hành động này không thể hoàn tác.</p>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeDeleteModal()"
                    class="px-4 py-2 text-gray-600 hover:text-gray-800 border border-gray-300 rounded-md">
                    Hủy
                </button>
                <button type="button" id="confirmDeleteBtn"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md">
                    Xóa
                </button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        let currentFileId = null;

        // Edit Category Functions
        function editCategory(fileId, currentCategory) {
            currentFileId = fileId;
            document.getElementById('categoryInput').value = currentCategory;
            document.getElementById('editCategoryModal').classList.remove('hidden');
            document.getElementById('editCategoryModal').classList.add('flex');
        }

        function closeEditModal() {
            document.getElementById('editCategoryModal').classList.add('hidden');
            document.getElementById('editCategoryModal').classList.remove('flex');
            currentFileId = null;
        }

        // Delete Functions
        function deleteFile(fileId) {
            currentFileId = fileId;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
            currentFileId = null;
        }

        // Form Submissions
        document.getElementById('editCategoryForm').addEventListener('submit', function(e) {
            e.preventDefault();

            if (!currentFileId) return;

            const category = document.getElementById('categoryInput').value;

            fetch(`/doctor/files/${currentFileId}/update-category`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        file_category: category
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Có lỗi xảy ra khi cập nhật danh mục');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi cập nhật danh mục');
                });
        });

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (!currentFileId) return;

            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/doctor/files/${currentFileId}`;

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';

            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            form.appendChild(methodInput);
            form.appendChild(tokenInput);
            document.body.appendChild(form);
            form.submit();
        });

        // Close modals when clicking outside
        document.getElementById('editCategoryModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });

        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>
@endpush
