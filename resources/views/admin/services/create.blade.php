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
                    <form action="{{ route('admin.services.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <!-- Service Name -->
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label fw-semibold">
                                    <i class="fas fa-tag text-primary me-1"></i>
                                    Tên dịch vụ
                                </label>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror">
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Category -->
                            <div class="col-md-6 mb-3">
                                <label for="service_cate_id" class="form-label fw-semibold">
                                    <i class="fas fa-folder text-primary me-1"></i>
                                    Danh mục
                                </label>
                                <select name="service_cate_id" class="form-select @error('service_cate_id') is-invalid @enderror">
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('service_cate_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('service_cate_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">
                                <i class="fas fa-align-left text-primary me-1"></i>
                                Mô tả
                            </label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row">
                            <!-- Price -->
                            <div class="col-md-4 mb-3">
                                <label for="price" class="form-label fw-semibold">
                                    <i class="fas fa-dollar-sign text-success me-1"></i>
                                    Giá (VNĐ)
                                </label>
                                <input type="number" name="price" value="{{ old('price') }}" class="form-control @error('price') is-invalid @enderror">
                                @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Duration -->
                            <div class="col-md-4 mb-3">
                                <label for="duration" class="form-label fw-semibold">
                                    <i class="fas fa-clock text-info me-1"></i>
                                    Thời gian (phút)
                                </label>
                                <input type="number" name="duration" value="{{ old('duration') }}" class="form-control @error('duration') is-invalid @enderror">
                                @error('duration')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-4 mb-3">
                                <label for="status" class="form-label fw-semibold">
                                    <i class="fas fa-toggle-on text-warning me-1"></i>
                                    Trạng thái
                                </label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
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

.card {
    border-radius: 10px;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #e3e6f0;
    transition: all 0.3s ease;
}

.form-control:hover, .form-select:hover {
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
</style>
@endsection