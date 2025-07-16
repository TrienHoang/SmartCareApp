@extends('admin.dashboard')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-eye me-2 text-primary"></i>
                Chi tiết dịch vụ
            </h1>
            <p class="text-muted mb-0">Xem thông tin chi tiết của dịch vụ</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.services.index') }}">Dịch vụ</a></li>
                <li class="breadcrumb-item active">Chi tiết</li>
            </ol>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Left Column - Service Details -->
        <div class="col-lg-8">
            <!-- Basic Information Card -->
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Thông tin cơ bản
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- Service Name -->
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="info-label">
                                    <i class="fas fa-tag me-2 text-primary"></i>
                                    Tên dịch vụ
                                </label>
                                <div class="info-value">{{ $service->name }}</div>
                            </div>
                        </div>

                        <!-- Category -->
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="info-label">
                                    <i class="fas fa-folder me-2 text-warning"></i>
                                    Danh mục
                                </label>
                                <div class="info-value">
                                    <span class="badge bg-warning text-dark fs-6">
                                        {{ $service->category->name ?? 'Không xác định' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Price -->
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="info-label">
                                    <i class="fas fa-money-bill-wave me-2 text-success"></i>
                                    Giá dịch vụ
                                </label>
                                <div class="info-value text-success fw-bold fs-5">
                                    {{ number_format($service->price) }} VNĐ
                                </div>
                            </div>
                        </div>

                        <!-- Duration -->
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="info-label">
                                    <i class="fas fa-clock me-2 text-info"></i>
                                    Thời gian thực hiện
                                </label>
                                <div class="info-value text-info fw-bold fs-5">
                                    {{ $service->duration }} phút
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="info-label">
                                    <i class="fas fa-toggle-on me-2 text-secondary"></i>
                                    Trạng thái
                                </label>
                                <div class="info-value">
                                    @if ($service->status == 'active')
                                        <span class="badge bg-success fs-6 px-3 py-2">
                                            <i class="fas fa-check-circle me-1"></i>
                                            Hoạt động
                                        </span>
                                    @else
                                        <span class="badge bg-secondary fs-6 px-3 py-2">
                                            <i class="fas fa-times-circle me-1"></i>
                                            Không hoạt động
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description Card -->
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header bg-gradient-info text-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-align-left me-2"></i>
                        Mô tả dịch vụ
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="description-content">
                        @if($service->description)
                            <p class="mb-0 lh-lg">{{ $service->description }}</p>
                        @else
                            <div class="text-muted text-center py-3">
                                <i class="fas fa-info-circle me-2"></i>
                                Không có mô tả
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Additional Info -->
        <div class="col-lg-4">
            <!-- Service Stats -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Thống kê
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="stat-item">
                                <div class="stat-number text-primary">
                                    <i class="fas fa-hashtag"></i>
                                    {{ $service->id }}
                                </div>
                                <div class="stat-label">ID Dịch vụ</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <div class="stat-number text-success">
                                    <i class="fas fa-dollar-sign"></i>
                                    {{ number_format($service->price / 1000) }}K
                                </div>
                                <div class="stat-label">Giá (K VNĐ)</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-gradient-success text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        Thao tác nhanh
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.services.edit', $service->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>
                            Chỉnh sửa
                        </a>
                        <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Quay lại danh sách
                        </a>
                    </div>
                </div>
            </div>

            <!-- Service Info -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-warning text-dark">
                    <h6 class="mb-0">
                        <i class="fas fa-lightbulb me-2"></i>
                        Thông tin thêm
                    </h6>
                </div>
                <div class="card-body">
                    <div class="info-list">
                        <div class="info-row">
                            <span class="info-icon">
                                <i class="fas fa-calendar-alt text-primary"></i>
                            </span>
                            <div class="info-content">
                                <div class="info-title">Thời gian</div>
                                <div class="info-desc">{{ $service->duration }} phút thực hiện</div>
                            </div>
                        </div>
                        <div class="info-row">
                            <span class="info-icon">
                                <i class="fas fa-star text-warning"></i>
                            </span>
                            <div class="info-content">
                                <div class="info-title">Chất lượng</div>
                                <div class="info-desc">Dịch vụ chuyên nghiệp</div>
                            </div>
                        </div>
                        <div class="info-row">
                            <span class="info-icon">
                                <i class="fas fa-shield-alt text-success"></i>
                            </span>
                            <div class="info-content">
                                <div class="info-title">Bảo hành</div>
                                <div class="info-desc">Đảm bảo chất lượng</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                    <i class="fas fa-arrow-left me-2"></i>
                    Quay lại
                </a>
                <a href="{{ route('admin.services.edit', $service->id) }}" class="btn btn-primary btn-lg px-4">
                    <i class="fas fa-edit me-2"></i>
                    Chỉnh sửa
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #54a3ff 0%, #006fe6 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.info-item {
    padding: 1rem;
    border-radius: 10px;
    background: #f8f9fc;
    border: 1px solid #e3e6f0;
    transition: all 0.3s ease;
}

.info-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.info-label {
    font-weight: 600;
    color: #5a5c69;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
    display: block;
}

.info-value {
    font-size: 1.1rem;
    font-weight: 500;
    color: #2c3e50;
}

.description-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #495057;
}

.stat-item {
    padding: 1rem 0;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.85rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-list {
    space-y: 1rem;
}

.info-row {
    display: flex;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e9ecef;
}

.info-row:last-child {
    border-bottom: none;
}

.info-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f8f9fc;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    flex-shrink: 0;
}

.info-content {
    flex: 1;
}

.info-title {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.25rem;
}

.info-desc {
    color: #6c757d;
    font-size: 0.9rem;
}

.card {
    border-radius: 15px;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-3px);
}

.btn {
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
}

.breadcrumb-item + .breadcrumb-item::before {
    color: #667eea;
}

.text-primary {
    color: #667eea !important;
}
</style>
@endsection