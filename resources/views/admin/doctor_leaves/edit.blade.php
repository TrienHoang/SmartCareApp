@extends('admin.dashboard')

@section('title', 'Chỉnh sửa Ngày nghỉ Bác sĩ')

@section('content')
<div class="content-wrapper">
    <!-- Header Section -->
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bx bx-calendar-check me-2 text-primary" style="font-size: 24px;"></i>
                        <h2 class="content-header-title mb-0">Chỉnh sửa Ngày nghỉ Bác sĩ</h2>
                    </div>
                    <div class="breadcrumb-wrapper">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="">
                                    <a href="{{ route('admin.dashboard.index') }}" class="text-decoration-none">
                                        Trang chủ >
                                    </a>
                                </li>
                                <li class="">
                                    <a href="{{ route('admin.doctor_leaves.index') }}" class="text-decoration-none">
                                        Ngày nghỉ Bác sĩ >
                                    </a>
                                </li>
                                <li class="breadcrumb-item active">Chỉnh sửa</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-body">
        <div class="row">
            <div class="col-12">
                <!-- Form Card -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="avatar-wrapper me-3">
                                <div class="avatar avatar-lg bg-primary">
                                    <i class="bx bx-edit text-white" style="font-size: 20px;"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="card-title mb-1">Thông tin Ngày nghỉ</h4>
                                <small class="text-muted">Cập nhật thông tin ngày nghỉ của bác sĩ</small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <!-- Error Messages -->
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bx bx-error-circle me-2" style="font-size: 18px;"></i>
                                    <strong>Vui lòng kiểm tra lại thông tin:</strong>
                                </div>
                                <ul class="mb-0 ps-4">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-error-circle me-2" style="font-size: 18px;"></i>
                                    {{ session('error') }}
                                </div>
                            </div>
                        @endif

                        <!-- Form -->
                        <form action="{{ route('admin.doctor_leaves.update', $leave->id) }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <!-- Left Column -->
                                <div class="col-lg-8">
                                    <div class="form-section mb-4">
                                        <div class="section-header mb-3">
                                            <h5 class="section-title">
                                                <i class="bx bx-info-circle text-primary me-2"></i>
                                                Thông tin cơ bản
                                            </h5>
                                        </div>
                                        <!-- Doctor Name -->
                                        <div class="form-group mb-3">
                                            <label class="form-label d-flex align-items-center">
                                                <i class="bx bx-user me-2 text-muted"></i>
                                                Bác sĩ
                                            </label>
                                            <input type="text" class="form-control" value="{{ $leave->doctor->user->full_name ?? 'Không rõ' }}" readonly>
                                        </div>
                                        <!-- Start Date -->
                                        <div class="form-group mb-3">
                                            <label for="start_date" class="form-label d-flex align-items-center">
                                                <i class="bx bx-calendar me-2 text-muted"></i>
                                                Ngày bắt đầu <span class="text-danger ms-1">*</span>
                                            </label>
                                            <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                                id="start_date" name="start_date"
                                                value="{{ old('start_date', $leave->start_date) }}" required>
                                            @error('start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!-- End Date -->
                                        <div class="form-group mb-3">
                                            <label for="end_date" class="form-label d-flex align-items-center">
                                                <i class="bx bx-calendar me-2 text-muted"></i>
                                                Ngày kết thúc <span class="text-danger ms-1">*</span>
                                            </label>
                                            <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                                id="end_date" name="end_date"
                                                value="{{ old('end_date', $leave->end_date) }}" required>
                                            @error('end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!-- Reason -->
                                        <div class="form-group mb-3">
                                            <label for="reason" class="form-label d-flex align-items-center">
                                                <i class="bx bx-message-square-dots me-2 text-muted"></i>
                                                Lý do nghỉ
                                            </label>
                                            <textarea class="form-control @error('reason') is-invalid @enderror" id="reason" name="reason" rows="3" placeholder="Nhập lý do nghỉ...">{{ old('reason', $leave->reason) }}</textarea>
                                            @error('reason')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <!-- Right Column -->
                                <div class="col-lg-4">
                                    <div class="sticky-sidebar">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-header bg-primary text-white mb-2">
                                                <h6 class="mb-0">
                                                    <i class="bx bx-cog me-2"></i>
                                                    Trạng thái & Hành động
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <!-- Approved -->
                                                <div class="form-group mb-3">
                                                    <label for="approved" class="form-label d-flex align-items-center">
                                                        <i class="bx bx-check-circle me-2 text-muted"></i>
                                                        Trạng thái duyệt
                                                    </label>
                                                    <select id="approved" name="approved" class="form-select">
                                                        <option value="0" {{ $leave->approved == 0 ? 'selected' : '' }}>Chưa duyệt</option>
                                                        <option value="1" {{ $leave->approved == 1 ? 'selected' : '' }}>Đã duyệt</option>
                                                    </select>
                                                </div>
                                                <!-- Action Buttons -->
                                                <div class="action-buttons mt-4">
                                                    <button type="submit" class="btn btn-primary w-100 mb-2">
                                                        <i class="bx bx-save me-2"></i>
                                                        Cập nhật ngày nghỉ
                                                    </button>
                                                    <a href="{{ route('admin.doctor_leaves.index') }}" class="btn btn-outline-secondary w-100">
                                                        <i class="bx bx-x me-2"></i>
                                                        Hủy bỏ
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Quick Tips -->
                                        <div class="card border-0 shadow-sm mt-3">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0 text-muted">
                                                    <i class="bx bx-bulb me-2"></i>
                                                    Mẹo sử dụng
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <ul class="list-unstyled mb-0 quick-tips">
                                                    <li><i class="bx bx-check text-success me-2"></i>Điền đầy đủ thông tin ngày nghỉ</li>
                                                    <li><i class="bx bx-check text-success me-2"></i>Kiểm tra kỹ ngày bắt đầu và kết thúc</li>
                                                    <li><i class="bx bx-check text-success me-2"></i>Chỉ duyệt khi hợp lệ</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-section {
        background: #fff;
        border-radius: 8px;
        padding: 1.5rem;
        border: 1px solid #e3e6f0;
        margin-bottom: 1.5rem;
    }
    .section-header {
        border-bottom: 2px solid #f8f9fc;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
    }
    .section-title {
        color: #5a5c69;
        font-weight: 600;
        margin: 0;
    }
    .sticky-sidebar {
        position: sticky;
        top: 2rem;
    }
    .quick-tips li {
        padding: 0.25rem 0;
        font-size: 0.875rem;
        color: #6c757d;
    }
    .btn {
        border-radius: 0.375rem;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        transition: all 0.2s ease;
    }
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
    .btn-outline-secondary:hover {
        transform: translateY(-1px);
    }
    @media (max-width: 991px) {
        .sticky-sidebar {
            position: static;
            margin-top: 2rem;
        }
    }
    @media (max-width: 768px) {
        .form-section {
            padding: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Client-side validation
    $('form').on('submit', function(e) {
        const start = $('#start_date').val();
        const end = $('#end_date').val();
        if (!start || !end) {
            e.preventDefault();
            alert('Vui lòng nhập đầy đủ ngày bắt đầu và ngày kết thúc.');
            return;
        }
        if (start > end) {
            e.preventDefault();
            alert('Ngày bắt đầu không được lớn hơn ngày kết thúc.');
            $('#start_date').focus();
        }
    });
</script>
@endpush
