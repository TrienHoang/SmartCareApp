@extends('admin.dashboard')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0 text-gray-800">
                            <i class="fas fa-plus-circle text-primary me-2"></i>
                            Thêm dịch vụ mới
                        </h1>
                        <p class="text-muted mb-0">Tạo dịch vụ mới cho hệ thống</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Main Form Card -->
        <div class="row">
            <div class="col-lg-8 col-xl-9">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-gradient-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-edit me-2"></i>
                            Thông tin dịch vụ
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <!-- Service Name -->
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label fw-semibold">
                                        <i class="fas fa-tag text-primary me-1"></i>
                                        Tên dịch vụ <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name" value="{{ old('name') }}"
                                        class="form-control @error('name') is-invalid @enderror" placeholder="Nhập tên dịch vụ...">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Category -->
                                <div class="col-md-6 mb-3">
                                    <label for="service_cate_id" class="form-label fw-semibold">
                                        <i class="fas fa-folder text-primary me-1"></i>
                                        Danh mục <span class="text-danger">*</span>
                                    </label>
                                    <select name="service_cate_id"
                                        class="form-select @error('service_cate_id') is-invalid @enderror">
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('service_cate_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('service_cate_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Department -->
                                <div class="col-md-6 mb-3">
                                    <label for="department_id" class="form-label fw-semibold">
                                        <i class="fas fa-building text-info me-1"></i>
                                        Khoa phụ trách <span class="text-danger">*</span>
                                    </label>
                                    <select name="department_id" id="department_id"
                                        class="form-select @error('department_id') is-invalid @enderror">
                                        <option value="">-- Chọn khoa --</option>
                                        @foreach ($departments as $dept)
                                            <option value="{{ $dept->id }}"
                                                {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                                {{ $dept->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Doctors -->
                                <div class="col-md-6 mb-3">
                                    <label for="doctors" class="form-label fw-semibold">
                                        <i class="fas fa-user-md text-primary me-1"></i>
                                        Bác sĩ thực hiện
                                    </label>
                                    <div class="flex justify-between mb-2">
                                        <button type="button" onclick="document.querySelectorAll('.doctors-container input[type=checkbox]').forEach(cb => cb.checked = true)" class="btn btn-sm btn-outline-primary">Chọn tất cả</button>
                                        <button type="button" onclick="document.querySelectorAll('.doctors-container input[type=checkbox]').forEach(cb => cb.checked = false)" class="btn btn-sm btn-outline-secondary">Bỏ chọn tất cả</button>
                                    </div>
                                    <div class="doctors-container max-h-64 overflow-y-auto border border-gray-200 rounded-lg p-4">
                                        <p class="text-gray-500">Vui lòng chọn khoa để hiển thị danh sách bác sĩ.</p>
                                    </div>
                                    @error('doctors')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Image -->
                            <div class="mb-3">
                                <label for="image" class="form-label fw-semibold">
                                    <i class="fas fa-image text-primary me-1"></i>
                                    Ảnh dịch vụ
                                </label>
                                <input type="file" name="image"
                                    class="form-control @error('image') is-invalid @enderror">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label fw-semibold">
                                    <i class="fas fa-align-left text-primary me-1"></i>
                                    Mô tả
                                </label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" placeholder="Nhập mô tả dịch vụ...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nội dung chi tiết -->
                            <div class="mb-4">
                                <label for="content" class="form-label fw-semibold">
                                    <i class="fas fa-file-alt text-primary me-1"></i>
                                    Nội dung chi tiết
                                </label>
                                <textarea name="content" id="content" rows="10" class="form-control @error('content') is-invalid @enderror">{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <!-- Price -->
                                <div class="col-md-4 mb-3">
                                    <label for="price" class="form-label fw-semibold">
                                        <i class="fas fa-dollar-sign text-success me-1"></i>
                                        Giá (VNĐ) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="price" value="{{ old('price') }}"
                                        class="form-control @error('price') is-invalid @enderror" placeholder="0">
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Duration -->
                                <div class="col-md-4 mb-3">
                                    <label for="duration" class="form-label fw-semibold">
                                        <i class="fas fa-clock text-info me-1"></i>
                                        Thời gian (phút) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="duration" value="{{ old('duration') }}"
                                        class="form-control @error('duration') is-invalid @enderror" placeholder="0">
                                    @error('duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div class="col-md-4 mb-3">
                                    <label for="status" class="form-label fw-semibold">
                                        <i class="fas fa-toggle-on text-warning me-1"></i>
                                        Trạng thái <span class="text-danger">*</span>
                                    </label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Quay lại
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg px-4">
                                    <i class="fas fa-save me-2"></i>
                                    Lưu
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="col-lg-4 col-xl-3">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-info-circle text-info me-2"></i>
                            Hướng dẫn
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="small text-muted">
                            <div class="mb-3">
                                <i class="fas fa-lightbulb text-warning me-2"></i>
                                <strong>Lưu ý:</strong>
                            </div>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Tên dịch vụ nên rõ ràng và dễ hiểu
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Mô tả chi tiết giúp khách hàng hiểu rõ hơn
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Giá cả phải phù hợp với thị trường
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Thời gian thực hiện cần chính xác
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Chọn đúng bác sĩ theo chuyên khoa
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS -->
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .card {
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid #e3e6f0;
            transition: all 0.3s ease;
        }

        .form-control:hover,
        .form-select:hover {
            border-color: #667eea;
        }

        .alert {
            border-radius: 8px;
            border: none;
        }

        .form-label {
            margin-bottom: 0.5rem;
        }

        .invalid-feedback {
            display: block;
        }

        .max-h-64 {
            max-height: 16rem;
        }
    </style>

    @push('scripts')
        <script src="https://cdn.ckeditor.com/4.20.2/standard/ckeditor.js"></script>
        <script>
            CKEDITOR.replace('content');

            document.getElementById('department_id').addEventListener('change', function() {
                const departmentId = this.value;
                const doctorsContainer = document.querySelector('.doctors-container');
                
                if (!doctorsContainer) {
                    console.error('Doctors container not found');
                    return;
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                if (!csrfToken) {
                    console.error('CSRF token not found');
                    return;
                }

                doctorsContainer.innerHTML = '<p class="text-gray-500">Đang tải...</p>';

                fetch(`/admin/doctors-by-department/${departmentId}`, {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Doctors data:', data);
                    doctorsContainer.innerHTML = '';

                    if (data.length === 0) {
                        doctorsContainer.innerHTML = '<p class="text-gray-500">Không có bác sĩ nào trong chuyên khoa này.</p>';
                        return;
                    }

                    data.forEach(doctor => {
                        const isChecked = {{ json_encode(old('doctors', [])) }}.includes(String(doctor.id)) ? 'checked' : '';
                        doctorsContainer.innerHTML += `
                            <label class="flex items-center mb-2">
                                <input type="checkbox" name="doctors[]" value="${doctor.id}" ${isChecked}
                                       class="form-check-input h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-gray-700">${doctor.user.full_name || 'No name'}</span>
                            </label>
                        `;
                    });
                })
                .catch(error => {
                    console.error('Error fetching doctors:', error);
                    doctorsContainer.innerHTML = '<p class="text-red-500">Lỗi khi tải danh sách bác sĩ.</p>';
                });
            });

            // Khôi phục danh sách bác sĩ khi tải lại trang nếu có old('department_id')
            document.addEventListener('DOMContentLoaded', function() {
                const departmentId = document.getElementById('department_id').value;
                if (departmentId) {
                    document.getElementById('department_id').dispatchEvent(new Event('change'));
                }
            });
        </script>
    @endpush
@endsection