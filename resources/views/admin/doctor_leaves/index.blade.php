@extends('admin.dashboard')
@section('title', 'Quản lý ngày nghỉ bác sĩ')

@section('content')
<div class="container-fluid py-4 animate__animated animate__fadeIn">

    <!-- Tiêu đề + Tìm kiếm -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <h4 class="mb-3 mb-md-0 d-flex align-items-center">
                <i data-feather="calendar" class="me-2 text-primary"></i> Danh sách ngày nghỉ bác sĩ
            </h4>
<form action="{{ route('admin.doctor_leaves.index') }}" method="GET" class="d-flex flex-wrap gap-2 align-items-end" style="max-width: 900px;">
    <div>
        <label for="keyword" class="form-label mb-0 small">Tên bác sĩ</label>
        <input type="text" name="keyword" class="form-control" placeholder="Tìm bác sĩ..." value="{{ request('keyword') }}">
    </div>

    <div>
        <label for="start_date" class="form-label mb-0 small">Từ ngày</label>
        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
    </div>

    <div>
        <label for="end_date" class="form-label mb-0 small">Đến ngày</label>
        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
    </div>

    <div>
        <label for="approved" class="form-label mb-0 small">Trạng thái</label>
        <select name="approved" class="form-select">
            <option value="">-- Tất cả --</option>
            <option value="1" {{ request('approved') === '1' ? 'selected' : '' }}>Đã duyệt</option>
            <option value="0" {{ request('approved') === '0' ? 'selected' : '' }}>Chưa duyệt</option>
        </select>
    </div>

    <div>
        <label class="form-label mb-0 invisible">Lọc</label>
        <button class="btn btn-outline-primary d-flex align-items-center">
            <i data-feather="search" class="me-1"></i> Lọc
        </button>
    </div>
</form>

        </div>
    </div>

    <!-- Flash messages -->
    @foreach (['success', 'error', 'info'] as $msg)
        @if (session($msg))
            <div class="alert alert-{{ $msg == 'error' ? 'danger' : $msg }} alert-dismissible fade show" role="alert">
                {{ session($msg) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach

    <!-- Danh sách ngày nghỉ -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            @if($doctorLeaves->isEmpty())
                <div class="text-center text-muted py-5">
                    <i data-feather="info" class="mb-2"></i>
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
                                    <td class="d-none d-lg-table-cell text-truncate" style="max-width: 250px;">
                                        {{ $leave->reason ?? 'Không có lý do' }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill {{ $leave->approved ? 'bg-success' : 'bg-warning text-dark' }}">
                                            {{ $leave->approved ? 'Đã duyệt' : 'Chưa duyệt' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.doctor_leaves.edit', $leave->id) }}" class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-3 py-3 border-top d-flex justify-content-center">
                    {{ $doctorLeaves->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/feather-icons"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        feather.replace();
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (el) {
            new bootstrap.Tooltip(el);
        });

        @if(session('success'))
            showToast('success', '{{ session('success') }}');
        @elseif(session('error'))
            showToast('error', '{{ session('error') }}');
        @elseif(session('info'))
            showToast('info', '{{ session('info') }}');
        @endif
    });

    function showToast(type, message) {
        const iconMap = {
            success: 'check-circle',
            error: 'x-circle',
            info: 'info'
        };
        const bgMap = {
            success: 'bg-success',
            error: 'bg-danger',
            info: 'bg-info'
        };

        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white ${bgMap[type]} border-0 show`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i data-feather="${iconMap[type]}" class="me-2"></i>${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;

        const container = document.createElement('div');
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.appendChild(toast);
        document.body.appendChild(container);

        feather.replace();
        new bootstrap.Toast(toast, { delay: 4000 }).show();
    }
</script>
@endsection
