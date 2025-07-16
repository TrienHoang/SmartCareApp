@extends('admin.dashboard')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-edit me-2 text-primary"></i>
                Chỉnh sửa dịch vụ
            </h1>
            <p class="text-muted mb-0">Cập nhật thông tin dịch vụ</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.services.index') }}">Dịch vụ</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa</li>
            </ol>
        </nav>
    </div>

    <!-- Flash Messages -->
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
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cog me-2"></i>
                        Thông tin dịch vụ
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.services.update', $service->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Service Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">
                                <i class="fas fa-tag me-2 text-primary"></i>
                                Tên dịch vụ <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name"
                                   value="{{ old('name', $service->name) }}" 
                                   class="form-control form-control-lg @error('name') is-invalid @enderror"
                                   placeholder="Nhập tên dịch vụ...">
                            @error('name')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">
                                <i class="fas fa-align-left me-2 text-primary"></i>
                                Mô tả
                            </label>
                            <textarea name="description" 
                                      id="description"
                                      rows="4"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Nhập mô tả dịch vụ...">{{ old('description', $service->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Price and Duration Row -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="price" class="form-label fw-semibold">
                                    <i class="fas fa-money-bill-wave me-2 text-success"></i>
                                    Giá (VNĐ) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" 
                                           name="price" 
                                           id="price"
                                           value="{{ old('price', $service->price) }}" 
                                           class="form-control @error('price') is-invalid @enderror"
                                           placeholder="0">
                                    <span class="input-group-text">VNĐ</span>
                                    @error('price')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="duration" class="form-label fw-semibold">
                                    <i class="fas fa-clock me-2 text-info"></i>
                                    Thời gian (phút) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" 
                                           name="duration" 
                                           id="duration"
                                           value="{{ old('duration', $service->duration) }}" 
                                           class="form-control @error('duration') is-invalid @enderror"
                                           placeholder="0">
                                    <span class="input-group-text">phút</span>
                                    @error('duration')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Category -->
                        <div class="mb-4">
                            <label for="service_cate_id" class="form-label fw-semibold">
                                <i class="fas fa-folder me-2 text-warning"></i>
                                Danh mục <span class="text-danger">*</span>
                            </label>
                            <select name="service_cate_id" 
                                    id="service_cate_id"
                                    class="form-select @error('service_cate_id') is-invalid @enderror">
                                <option value="">-- Chọn danh mục --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('service_cate_id', $service->service_cate_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('service_cate_id')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label for="status" class="form-label fw-semibold">
                                <i class="fas fa-toggle-on me-2 text-secondary"></i>
                                Trạng thái <span class="text-danger">*</span>
                            </label>
                            <select name="status" 
                                    id="status"
                                    class="form-select @error('status') is-invalid @enderror">
                                <option value="active" {{ old('status', $service->status) == 'active' ? 'selected' : '' }}>
                                    <i class="fas fa-check-circle"></i> Hoạt động
                                </option>
                                <option value="inactive" {{ old('status', $service->status) == 'inactive' ? 'selected' : '' }}>
                                    <i class="fas fa-times-circle"></i> Không hoạt động
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between pt-3 border-top">
                            <a href="{{ route('admin.services.index') }}" 
                               class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-arrow-left me-2"></i>
                                Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg px-4">
                                <i class="fas fa-save me-2"></i>
                                Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Right Sidebar -->
        <div class="col-lg-4">
            <!-- Service Info Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Thông tin hiện tại
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="text-muted">ID:</span>
                                <span class="fw-bold">#{{ $service->id }}</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="text-muted">Trạng thái:</span>
                                <span class="badge {{ $service->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $service->status == 'active' ? 'Hoạt động' : 'Không hoạt động' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="text-muted">Giá hiện tại:</span>
                                <span class="fw-bold text-success">{{ number_format($service->price) }} VNĐ</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center py-2">
                                <span class="text-muted">Thời gian:</span>
                                <span class="fw-bold text-info">{{ $service->duration }} phút</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tips Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">
                        <i class="fas fa-lightbulb me-2"></i>
                        Gợi ý
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Tên dịch vụ nên ngắn gọn và dễ hiểu</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Mô tả chi tiết giúp khách hàng hiểu rõ hơn</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Giá cả hợp lý so với thị trường</small>
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Thời gian thực hiện chính xác</small>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        Thao tác nhanh
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.services.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-list me-2"></i>
                            Danh sách dịch vụ
                        </a>
                        <button type="button" class="btn btn-outline-success btn-sm" onclick="document.querySelector('form').submit()">
                            <i class="fas fa-save me-2"></i>
                            Lưu thay đổi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.form-control:focus, .form-select:focus {
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

.btn-outline-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(108, 117, 125, 0.2);
}

.card {
    border-radius: 15px;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.alert {
    border-radius: 10px;
    border: none;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #e3e6f0;
    transition: all 0.3s ease;
}

.form-control:hover, .form-select:hover {
    border-color: #667eea;
}

.breadcrumb-item + .breadcrumb-item::before {
    color: #667eea;
}

.input-group-text {
    background-color: #f8f9fc;
    border-color: #e3e6f0;
    color: #5a5c69;
}

.form-label {
    color: #5a5c69;
    margin-bottom: 0.5rem;
}

.text-primary {
    color: #667eea !important;
}
</style>
@endsection