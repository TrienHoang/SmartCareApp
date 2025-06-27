@extends('admin.dashboard')

@section('title', 'Quản lý Bác sĩ')

@section('content')
<div class="container-fluid py-4 animate__animated animate__fadeIn">
    <!-- Enhanced Header Section -->
    <div class="content-header row">
        <div class="content-header-left col-md-8 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-circle bg-primary me-3 ">
                            <i class="bx bx-plus-medical text-white "></i> {{-- Icon for Doctors --}}
                        </div>
                        <div>
                            <h2 class="content-header-title mb-0 text-primary fw-bold">Quản lý Bác sĩ</h2>
                            <p class="text-muted mb-0">Quản lý và theo dõi thông tin các bác sĩ</p>
                        </div>
                    </div>
                    <div class="breadcrumb-wrapper col-12">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent p-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.dashboard.index') }}" class="text-decoration-none">Trang chủ</a>
                                </li>
                                <li class="breadcrumb-item active text-primary fw-semibold">Bác sĩ</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-header-right col-md-4 col-12 text-md-end">
            <div class="form-group breadcrum-right">
                <a href="{{ route('admin.doctors.create') }}"
                    class="btn btn-success btn-lg waves-effect waves-light shadow-lg text-white">
                    <i class="bx bx-plus me-2"></i> Thêm bác sĩ mới
                </a>
            </div>
        </div>
    </div>

    {{-- Notification Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Filter Form --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.doctors.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4 col-lg-3">
                    <label for="department_id" class="form-label fw-semibold">Phòng ban</label>
                    <select name="department_id" id="department_id" class="form-select">
                        <option value="">-- Tất cả phòng ban --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 col-lg-3">
                    <label for="specialization" class="form-label fw-semibold">Chuyên môn</label>
                    <input type="text" name="specialization" id="specialization" class="form-control"
                           placeholder="Nhập chuyên môn..." value="{{ request('specialization') }}">
                </div>

                <div class="col-md-4 col-lg-6 d-flex justify-content-start">
                    <button type="submit" class="btn btn-primary me-2"><i class="bx bx-filter"></i> Lọc</button>
                    <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary"><i class="bx bx-reset"></i> Đặt lại</a>
                </div>
            </form>
        </div>
    </div>


    {{-- Doctors List Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bx bx-list-ul me-2"></i> Danh sách Bác sĩ</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped align-middle text-center mb-0">
                    <thead class="table-light text-dark">
                        <tr>
                            <th>ID</th>
                            <th>Họ Tên</th>
                            <th>Chuyên Môn</th>
                            <th>Phòng Ban</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($doctors as $doctor)
                            <tr>
                                <td>{{ $doctor->id }}</td>
                                <td>{{ $doctor->user->full_name ?? 'Không có' }}</td>
                                <td>{{ $doctor->specialization }}</td>
                                <td>{{ $doctor->department->name ?? 'Chưa có' }}</td>
                                <td>
                                    <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn btn-warning btn-sm me-1" data-bs-toggle="tooltip" title="Chỉnh sửa">
                                        <i class="bx bx-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteDoctor({{ $doctor->id }})" data-bs-toggle="tooltip" title="Xóa">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Không có bác sĩ nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Pagination --}}
        @if(method_exists($doctors, 'links'))
            <div class="card-footer d-flex justify-content-end">
                {{ $doctors->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Function to delete a doctor using SweetAlert2
    function deleteDoctor(id) {
        Swal.fire({
            title: 'Xác nhận xóa?',
            text: 'Bạn có chắc chắn muốn xóa bác sĩ này? Hành động này không thể hoàn tác!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/doctors/${id}`; // Adjust route if necessary

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
