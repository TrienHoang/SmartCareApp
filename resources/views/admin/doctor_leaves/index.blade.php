@extends('admin.dashboard')

@section('title', 'Quản lý ngày nghỉ Bác sĩ')

@section('content')
<div class="container-fluid py-4 animate__animated animate__fadeIn">
    <!-- Enhanced Header Section -->
    <div class="content-header row">
        <div class="content-header-left col-md-8 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-circle bg-primary me-3 ">
                            <i class="bx bx-calendar-alt text-white "></i>
                        </div>
                        <div>
                            <h2 class="content-header-title mb-0 text-primary fw-bold">Quản lý Ngày nghỉ Bác sĩ</h2>
                            <p class="text-muted mb-0">Quản lý và duyệt/từ chối các yêu cầu nghỉ của bác sĩ</p>
                        </div>
                    </div>
                    <div class="breadcrumb-wrapper col-12">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent p-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.dashboard.index') }}" class="text-decoration-none">Trang chủ</a>
                                </li>
                                <li class="breadcrumb-item active text-primary fw-semibold">Ngày nghỉ Bác sĩ</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 col-12">
            <div class="card gradient-card bg-gradient-success">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="avatar bg-rgba-white mr-2">
                            <div class="avatar-content">
                                <i class="bx bx-user font-medium-5"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-white mb-0">{{ $stats['total_doctors'] ?? $doctorLeaves->pluck('doctor_id')->unique()->count() }}</h4>
                            <small class="text-white">Tổng bác sĩ nghỉ</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="card gradient-card bg-gradient-info">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="avatar bg-rgba-white mr-2">
                            <div class="avatar-content">
                                <i class="bx bx-calendar-check font-medium-5"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-white mb-0">{{ $stats['total_leaves'] ?? $doctorLeaves->total() }}</h4>
                            <small class="text-white">Tổng ngày nghỉ</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="card gradient-card bg-gradient-warning">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="avatar bg-rgba-white mr-2">
                            <div class="avatar-content">
                                <i class="bx bx-time font-medium-5"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-white mb-0">{{ $stats['pending'] ?? $doctorLeaves->where('approved', false)->count() }}</h4>
                            <small class="text-white">Chưa duyệt</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="card gradient-card bg-gradient-danger">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="avatar bg-rgba-white mr-2">
                            <div class="avatar-content">
                                <i class="bx bx-check-circle font-medium-5"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-white mb-0">{{ $stats['approved'] ?? $doctorLeaves->where('approved', true)->count() }}</h4>
                            <small class="text-white">Đã duyệt</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Notification Messages --}}
    @foreach (['success', 'error', 'info'] as $msg)
        @if (session($msg))
            <div class="alert alert-{{ $msg == 'error' ? 'danger' : $msg }} alert-dismissible fade show" role="alert">
                {{ session($msg) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    @endforeach

    <!-- Filter Form -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('admin.doctor_leaves.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-6 col-lg-3">
                    <label for="keyword" class="form-label fw-semibold mb-0 small">Tên bác sĩ</label>
                    <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Tìm bác sĩ..." value="{{ request('keyword') }}">
                </div>
                <div class="col-md-6 col-lg-3">
                    <label for="start_date" class="form-label fw-semibold mb-0 small">Từ ngày</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-6 col-lg-3">
                    <label for="end_date" class="form-label fw-semibold mb-0 small">Đến ngày</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-6 col-lg-3">
                    <label for="approved" class="form-label fw-semibold mb-0 small">Trạng thái</label>
                    <select name="approved" id="approved" class="form-select">
                        <option value="">-- Tất cả --</option>
                        <option value="1" {{ request('approved') === '1' ? 'selected' : '' }}>Đã duyệt</option>
                        <option value="0" {{ request('approved') === '0' ? 'selected' : '' }}>Chưa duyệt</option>
                    </select>
                </div>
                <div class="col-12 d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary d-flex align-items-center">
                        <i class="bx bx-search me-1"></i> Lọc
                    </button>
                    <a href="{{ route('admin.doctor_leaves.index') }}" class="btn btn-secondary d-flex align-items-center">
                        <i class="bx bx-reset me-1"></i> Đặt lại
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Doctor Leaves List -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bx bx-list-ul me-2"></i> Danh sách Ngày nghỉ</h5>
        </div>
        <div class="card-body p-0">
            @if($doctorLeaves->isEmpty())
                <div class="text-center text-muted py-5 empty-state">
                    <i class="bx bx-info-circle mb-2" style="font-size: 3rem;"></i>
                    <p class="mb-0">Không có dữ liệu ngày nghỉ nào.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Bác sĩ</th>
                                <th>Bắt đầu</th>
                                <th>Kết thúc</th>
                                <th class="d-none d-lg-table-cell">Ngày tạo</th>
                                <th class="d-none d-lg-table-cell">Lý do</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($doctorLeaves as $leave)
                                <tr>
                                    <td>{{ $leave->doctor->user->full_name ?? 'Không rõ' }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($leave->start_date)->format('d/m/Y') }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($leave->end_date)->format('d/m/Y') }}</td>
                                    <td class="d-none d-lg-table-cell text-center">{{ \Carbon\Carbon::parse($leave->created_at)->format('H:i d/m/Y') }}</td>
                                    <td class="d-none d-lg-table-cell text-truncate" style="max-width: 200px;">
                                        {{ $leave->reason ?? 'Không có lý do' }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill {{ $leave->approved ? 'bg-success' : 'bg-warning text-dark' }}">
                                            {{ $leave->approved ? 'Đã duyệt' : 'Chưa duyệt' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('admin.doctor_leaves.edit', $leave->id) }}" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Chỉnh sửa">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer px-3 py-3 border-top d-flex justify-content-end">
                    {{ $doctorLeaves->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .gradient-card {
        border: none;
        border-radius: 10px;
        transition: transform 0.3s ease;
    }
    .gradient-card:hover {
        transform: translateY(-2px);
    }
    .bg-gradient-success {
        background: linear-gradient(135deg, #00cec9 0%, #55a3ff 100%);
    }
    .bg-gradient-info {
        background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
    }
    .bg-gradient-warning {
        background: linear-gradient(135deg, #fdcb6e 0%, #e17055 100%);
    }
    .bg-gradient-danger {
        background: linear-gradient(135deg, #fd79a8 0%, #e84393 100%);
    }
    .avatar {
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .bg-rgba-white {
        background-color: rgba(255, 255, 255, 0.2);
    }
</style>
@endpush

@push('scripts')
<script>
    // Function to approve leave (using SweetAlert2 for confirmation)
    function approveLeave(id) {
        Swal.fire({
            title: 'Xác nhận duyệt?',
            text: 'Bạn có chắc chắn muốn duyệt yêu cầu nghỉ này?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754', // Bootstrap success color
            cancelButtonColor: '#6c757d', // Bootstrap secondary color
            confirmButtonText: 'Duyệt',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/doctor_leaves/${id}/approve`; // Assuming an 'approve' route

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    // Function to delete leave (using SweetAlert2 for confirmation)
    function deleteLeave(id) {
        Swal.fire({
            title: 'Xác nhận xóa?',
            text: 'Bạn có chắc chắn muốn xóa yêu cầu nghỉ này? Hành động này không thể hoàn tác!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545', // Bootstrap danger color
            cancelButtonColor: '#6c757d', // Bootstrap secondary color
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/doctor_leaves/${id}`; // Adjust route if necessary

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    $(document).ready(function() {
        // Initialize Bootstrap 5 tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endpush
