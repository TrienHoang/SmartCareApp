@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="bx bx-error-circle me-2" style="font-size: 20px;"></i>
            <div>
                <strong>Đã xảy ra lỗi!</strong> Vui lòng kiểm tra lại các trường bên dưới.
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <!-- Left Column -->
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="bx bx-edit-alt me-2 text-primary"></i>Thông tin cơ bản
                </h5>
            </div>
            <div class="card-body p-4">
                <!-- Service Category -->
                <div class="mb-4">
                    <label for="service_cate_id" class="form-label fw-medium">
                        <i class="bx bx-category me-1 text-info"></i>Danh mục dịch vụ
                        <span class="text-danger">*</span>
                    </label>
                    <select name="service_cate_id" id="service_cate_id" 
                            class="form-select form-select-lg @error('service_cate_id') is-invalid @enderror">
                        <option value="">-- Chọn danh mục dịch vụ --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('service_cate_id', $service->service_cate_id ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('service_cate_id')
                        <div class="invalid-feedback">
                            <i class="bx bx-error-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Service Name -->
                <div class="mb-4">
                    <label for="name" class="form-label fw-medium">
                        <i class="bx bx-rename me-1 text-primary"></i>Tên dịch vụ
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control form-control-lg @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name"
                           value="{{ old('name', $service->name ?? '') }}" 
                           placeholder="Nhập tên dịch vụ..."
                           required>
                    @error('name')
                        <div class="invalid-feedback">
                            <i class="bx bx-error-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label for="description" class="form-label fw-medium">
                        <i class="bx bx-file-blank me-1 text-secondary"></i>Mô tả dịch vụ
                    </label>
                    <textarea name="description" 
                              id="description" 
                              class="form-control @error('description') is-invalid @enderror" 
                              rows="4"
                              placeholder="Nhập mô tả chi tiết về dịch vụ...">{{ old('description', $service->description ?? '') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">
                            <i class="bx bx-error-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                    <div class="form-text">
                        <i class="bx bx-info-circle me-1"></i>Mô tả sẽ giúp khách hàng hiểu rõ hơn về dịch vụ
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="col-lg-4">
        <!-- Pricing & Duration Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="bx bx-money me-2 text-success"></i>Giá & Thời gian
                </h5>
            </div>
            <div class="card-body p-4">
                <!-- Price -->
                <div class="mb-4">
                    <label for="price" class="form-label fw-medium">
                        <i class="bx bx-dollar me-1 text-success"></i>Giá dịch vụ
                        <span class="text-danger">*</span>
                    </label>
                    <div class="input-group input-group-lg">
                        <input type="number" 
                               name="price" 
                               id="price" 
                               class="form-control @error('price') is-invalid @enderror"
                               value="{{ old('price', $service->price ?? '') }}" 
                               step="1000" 
                               min="0" 
                               placeholder="0"
                               required>
                        <span class="input-group-text">đ</span>
                        @error('price')
                            <div class="invalid-feedback">
                                <i class="bx bx-error-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-text">
                        <i class="bx bx-info-circle me-1"></i>Nhập giá theo đơn vị VNĐ
                    </div>
                </div>

                <!-- Duration -->
                <div class="mb-3">
                    <label for="duration" class="form-label fw-medium">
                        <i class="bx bx-time me-1 text-warning"></i>Thời lượng
                        <span class="text-danger">*</span>
                    </label>
                    <div class="input-group input-group-lg">
                        <input type="number" 
                               name="duration" 
                               id="duration" 
                               class="form-control @error('duration') is-invalid @enderror"
                               value="{{ old('duration', $service->duration ?? '') }}" 
                               min="1" 
                               placeholder="60"
                               required>
                        <span class="input-group-text">phút</span>
                        @error('duration')
                            <div class="invalid-feedback">
                                <i class="bx bx-error-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-text">
                        <i class="bx bx-info-circle me-1"></i>Thời gian thực hiện dịch vụ
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="bx bx-check-circle me-2 text-info"></i>Trạng thái
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="status" class="form-label fw-medium">
                        <i class="bx bx-toggle-left me-1 text-info"></i>Trạng thái hoạt động
                        <span class="text-danger">*</span>
                    </label>
                    <select name="status" 
                            id="status" 
                            class="form-select form-select-lg @error('status') is-invalid @enderror">
                        <option value="">-- Chọn trạng thái --</option>
                        <option value="active" 
                                {{ old('status', $service->status ?? '') == 'active' ? 'selected' : '' }}>
                            <i class="bx bx-check-circle"></i> Kích hoạt
                        </option>
                        <option value="inactive" 
                                {{ old('status', $service->status ?? '') == 'inactive' ? 'selected' : '' }}>
                            <i class="bx bx-x-circle"></i> Tạm ngưng
                        </option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">
                            <i class="bx bx-error-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                    <div class="form-text">
                        <i class="bx bx-info-circle me-1"></i>Chỉ dịch vụ kích hoạt mới hiển thị cho khách hàng
                    </div>
                </div>

                <!-- Status Preview -->
                <div class="status-preview p-3 rounded bg-light">
                    <small class="text-muted d-block mb-2">Xem trước trạng thái:</small>
                    <span id="status-badge" class="badge bg-secondary">Chưa chọn</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="bx bx-bolt me-2 text-warning"></i>Thao tác nhanh
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i>Quay lại danh sách
                    </a>
                    <button type="button" class="btn btn-outline-info btn-sm" onclick="fillSampleData()">
                        <i class="bx bx-data me-1"></i>Điền dữ liệu mẫu
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearForm()">
                        <i class="bx bx-refresh me-1"></i>Xóa tất cả
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}

.form-label {
    color: #495057;
    margin-bottom: 0.75rem;
}

.form-control:focus,
.form-select:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.invalid-feedback {
    display: block;
    font-size: 0.875rem;
}

.status-preview {
    border: 1px solid #dee2e6;
}

.alert {
    border: none;
    border-radius: 0.5rem;
}

.form-text {
    font-size: 0.825rem;
    color: #6c757d;
}

.input-group-text {
    background-color: #f8f9fa;
    border-color: #ced4da;
}

@media (max-width: 768px) {
    .col-lg-4 .card {
        margin-bottom: 1rem;
    }
}
</style>

<script>
// Status preview functionality
document.getElementById('status').addEventListener('change', function() {
    const badge = document.getElementById('status-badge');
    const value = this.value;
    
    badge.className = 'badge ';
    
    if (value === 'active') {
        badge.className += 'bg-success';
        badge.innerHTML = '<i class="bx bx-check-circle me-1"></i>Kích hoạt';
    } else if (value === 'inactive') {
        badge.className += 'bg-danger';
        badge.innerHTML = '<i class="bx bx-x-circle me-1"></i>Tạm ngưng';
    } else {
        badge.className += 'bg-secondary';
        badge.innerHTML = 'Chưa chọn';
    }
});

// Fill sample data
function fillSampleData() {
    document.getElementById('name').value = 'Massage thư giãn';
    document.getElementById('description').value = 'Dịch vụ massage thư giãn toàn thân giúp giảm stress và mệt mỏi sau ngày làm việc dài.';
    document.getElementById('price').value = '300000';
    document.getElementById('duration').value = '60';
    document.getElementById('status').value = 'active';
    
    // Trigger status change
    document.getElementById('status').dispatchEvent(new Event('change'));
}

// Clear form
function clearForm() {
    if (confirm('Bạn có chắc chắn muốn xóa tất cả dữ liệu đã nhập?')) {
        document.getElementById('name').value = '';
        document.getElementById('description').value = '';
        document.getElementById('price').value = '';
        document.getElementById('duration').value = '';
        document.getElementById('status').value = '';
        document.getElementById('service_cate_id').value = '';
        
        // Reset status badge
        const badge = document.getElementById('status-badge');
        badge.className = 'badge bg-secondary';
        badge.innerHTML = 'Chưa chọn';
    }
}

// Initialize status badge on page load
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('status').dispatchEvent(new Event('change'));
});
</script>