@extends('admin.dashboard')
@section('title', 'Quản lý đơn thuốc')

@section('content')
    <div class="content-wrapper">
        <!-- Enhanced Header Section -->
        <div class="content-header row">
            <div class="content-header-left col-md-8 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-circle bg-primary mr-3">
                                <i class="bx bx-health text-white"></i>
                            </div>
                            <div>
                                <h2 class="content-header-title mb-0 text-primary font-weight-bold">Quản lý Đơn thuốc</h2>
                                <p class="text-muted mb-0">Quản lý và theo dõi tất cả đơn thuốc trong hệ thống</p>
                            </div>
                        </div>
                        <div class="breadcrumb-wrapper col-12">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0">
                                    <li class="">
                                        <a href="{{ route('admin.dashboard.index') }}" class="text-decoration-none">
                                            Trang chủ >
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active text-primary font-weight-semibold">
                                        Đơn thuốc
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right col-md-4 col-12 text-md-right">
                <div class="form-group breadcrum-right">
                    @can('create_prescriptions')
                        <a href="{{ route('admin.prescriptions.create') }}"
                            class="btn btn-gradient-primary btn-lg waves-effect waves-light shadow-lg text-white">
                            <i class="bx bx-plus mr-1"></i>Tạo đơn thuốc mới
                        </a>
                    @endcan
                    <a href="{{ route('admin.prescriptions.trashed') }}"
                        class="btn btn-gradient-danger btn-lg waves-effect waves-light shadow-lg text-white ml-2">
                        <i class="bx bx-trash mr-1"></i>Đã xóa
                    </a>
                </div>
            </div>
        </div>

        <div class="content-body">
            <!-- Enhanced Alert Messages -->
            {{-- @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-check-circle mr-2"></i>
                        <strong>Thành công! </strong> {{ session('success') }}
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-x-circle mr-2"></i>
                        <strong>Lỗi! </strong> {{ session('error') }}
                    </div>
                </div>
            @endif --}}

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card gradient-card bg-gradient-success">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white mr-2">
                                    <div class="avatar-content">
                                        <i class="bx bx-receipt font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $prescriptions->total() }}</h4>
                                    <small class="text-white">Tổng đơn thuốc</small>
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
                                        <i class="bx bx-time font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $todayCount }}</h4>
                                    <small class="text-white">Hôm nay</small>
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
                                        <i class="bx bx-calendar-week font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $weeklyCount }}</h4>
                                    <small class="text-white">Tuần này</small>
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
                                        <i class="bx bx-package font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">
                                        {{ $prescriptions->sum(function ($p) {return $p->prescriptionItems->sum('quantity');}) }}
                                    </h4>
                                    <small class="text-white">Tổng số lượng thuốc</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Card -->
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-primary text-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-list mr-2"></i>
                            <h4 class="card-title mb-0 text-white font-weight-bold">
                                Danh sách Đơn thuốc
                            </h4>
                        </div>
                        <div class="card-tools ml-3">
                            <span class="badge badge-light text-dark">
                                {{ $prescriptions->total() }} đơn thuốc
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <!-- Enhanced Filter Section -->
                    <div class="filter-section bg-light p-4 border-bottom">
                        <form id="search-form" method="GET" class="filter-form">
                            <div class="row align-items-end">
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-user mr-1 text-primary"></i>Tên bệnh nhân
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="patient_name" class="form-control"
                                            value="{{ request('patient_name') }}" placeholder="Nhập tên bệnh nhân">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-user-check mr-1 text-info"></i>Tên bác sĩ
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="doctor_name" class="form-control"
                                            value="{{ request('doctor_name') }}" placeholder="Nhập tên bác sĩ">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-calendar mr-1 text-success"></i>Từ ngày
                                    </label>
                                    <input type="date" name="date_from" class="form-control"
                                        value="{{ old('date_from', $from_input ?? request('date_from')) }}">
                                </div>
                                <div class="col-lg-2 col-md-4 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-calendar mr-1 text-success"></i>Đến ngày
                                    </label>
                                    <input type="date" name="date_to" class="form-control"
                                        value="{{ old('date_to', $to_input ?? request('date_to')) }}">
                                </div>
                                <div class="col-lg-2 col-md-4 mb-2">
                                    <div class="btn-group w-100" role="group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bx bx-search mr-1"></i>Tìm kiếm
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row align-items-end mt-2">
                                <div class="col-lg-4 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-capsule mr-1 text-warning"></i>Thuốc
                                    </label>
                                    <select name="medicine_id" class="form-control custom-select">
                                        <option value="">-- Tất cả thuốc --</option>
                                        @foreach ($medicines as $id => $name)
                                            <option value="{{ $id }}"
                                                {{ request('medicine_id') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-building mr-1 text-secondary"></i>Khoa/Phòng ban
                                    </label>
                                    <select name="department_id" class="form-control custom-select">
                                        <option value="">-- Tất cả khoa/phòng ban --</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}"
                                                {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-2">
                                    <div class="btn-group w-100" role="group">
                                        <a href="{{ route('admin.prescriptions.index') }}"
                                            class="btn btn-outline-secondary">
                                            <i class="bx bx-refresh mr-1"></i>Đặt lại
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Enhanced Table for Desktop -->
                    <div class="d-none d-md-block">
                        <div class="table-responsive">
                            <table class="table table-hover table-modern mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="border-top-0">STT</th>
                                        <th class="border-top-0">
                                            <i class="bx bx-user mr-1"></i>Bệnh nhân
                                        </th>
                                        <th class="border-top-0">
                                            <i class="bx bx-user-check mr-1"></i>Bác sĩ kê đơn
                                        </th>
                                        <th class="border-top-0">
                                            <i class="bx bx-calendar mr-1"></i>Ngày kê đơn
                                        </th>
                                        <th class="border-top-0">
                                            <i class="bx bx-package mr-1"></i>Thông tin thuốc
                                        </th>
                                        <th class="border-top-0 text-center">
                                            <i class="bx bx-cog mr-1"></i>Hành động
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($prescriptions as $prescription)
                                        <tr class="prescription-row" data-id="{{ $prescription->id }}">
                                            <td class="font-weight-bold text-primary">
                                                {{ ($prescriptions->currentPage() - 1) * $prescriptions->perPage() + $loop->iteration }}
                                            </td>
                                            <td>
                                                <div class="patient-info">
                                                    <h6 class="mb-0 font-weight-semibold text-dark">
                                                        {{ $prescription->medicalRecord->appointment->patient->full_name }}
                                                    </h6>
                                                    <small class="text-muted">
                                                        <i
                                                            class="bx bx-phone mr-1"></i>{{ $prescription->medicalRecord->appointment->patient->phone }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="doctor-info">
                                                    <h6 class="mb-0 font-weight-semibold text-dark">
                                                        {{ $prescription->medicalRecord->appointment->doctor->user->full_name }}
                                                    </h6>
                                                    <small class="text-muted">
                                                        <i
                                                            class="bx bx-badge mr-1"></i>{{ $prescription->medicalRecord->appointment->doctor->specialization }}
                                                    </small><br>
                                                    <small class="text-muted">
                                                        <i
                                                            class="bx bx-building mr-1"></i>{{ $prescription->medicalRecord->appointment->doctor->department->name ?? 'Không có' }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center text-success">
                                                    <i class="bx bx-calendar-check mr-1"></i>
                                                    <div>
                                                        <small
                                                            class="font-weight-semibold">{{ $prescription->formatted_date }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="medicine-info">
                                                    <span class="badge badge-primary badge-pill">
                                                        <i
                                                            class="bx bx-package mr-1"></i>{{ $prescription->prescriptionItems->count() }}
                                                        loại thuốc
                                                    </span>
                                                    <br>
                                                    <span class="badge badge-success badge-pill mt-1">
                                                        <i
                                                            class="bx bx-box mr-1"></i>{{ $prescription->prescriptionItems->sum('quantity') }}
                                                        tổng số lượng
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('admin.prescriptions.show', $prescription->id) }}"
                                                        class="btn btn-outline-info" data-toggle="tooltip"
                                                        title="Xem chi tiết">
                                                        <i class='bx bx-show-alt'></i>
                                                    </a>
                                                    <a href="{{ route('admin.prescriptions.edit', $prescription->id) }}"
                                                        class="btn btn-outline-warning" data-toggle="tooltip"
                                                        title="Chỉnh sửa">
                                                        <i class="bx bx-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger btn-sm"
                                                        onclick="deletePrescription({{ $prescription->id }}, '{{ $prescription->medicalRecord->appointment->patient->full_name }}')">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <div class="empty-state">
                                                    <i class="bx bx-receipt text-muted" style="font-size: 48px;"></i>
                                                    <h5 class="mt-3 text-muted">Không có đơn thuốc nào</h5>
                                                    <p class="text-muted">Chưa có đơn thuốc nào được tạo hoặc không tìm
                                                        thấy kết quả phù hợp.</p>
                                                    @can('create_prescriptions')
                                                        <a href="{{ route('admin.prescriptions.create') }}"
                                                            class="btn btn-primary">
                                                            <i class="bx bx-plus mr-1"></i>Tạo đơn thuốc đầu tiên
                                                        </a>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Enhanced Mobile Cards -->
                    <div class="d-block d-md-none" id="prescriptionAccordion">
                        @forelse($prescriptions as $prescription)
                            <div class="card mb-3 shadow-sm">
                                <div class="card-header bg-gradient-light"
                                    id="headingPrescription{{ $prescription->id }}">
                                    <h5 class="mb-0">
                                        <button
                                            class="btn btn-link text-decoration-none text-dark d-flex align-items-center p-0 w-100"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapsePrescription{{ $prescription->id }}"
                                            aria-expanded="false"
                                            aria-controls="collapsePrescription{{ $prescription->id }}">
                                            <i class="bx bx-plus-circle fa-fw me-2 text-primary"
                                                id="iconPrescription{{ $prescription->id }}"></i>
                                            <span class="text-start flex-grow-1">
                                                <strong class="text-primary">Đơn thuốc
                                                    #{{ ($prescriptions->currentPage() - 1) * $prescriptions->perPage() + $loop->iteration }}</strong>
                                                <span class="ms-2 text-muted">{{ $prescription->formatted_date }}</span>
                                            </span>
                                        </button>
                                    </h5>
                                </div>

                                <div id="collapsePrescription{{ $prescription->id }}" class="collapse"
                                    aria-labelledby="headingPrescription{{ $prescription->id }}"
                                    data-bs-parent="#prescriptionAccordion">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <div class="d-flex align-items-center">
                                                    <i class="bx bx-user text-primary mr-2"></i>
                                                    <div>
                                                        <strong>Bệnh nhân:</strong>
                                                        <div>
                                                            {{ $prescription->medicalRecord->appointment->patient->full_name }}
                                                        </div>
                                                        <small
                                                            class="text-muted">{{ $prescription->medicalRecord->appointment->patient->phone }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <div class="d-flex align-items-center">
                                                    <i class="bx bx-user-check text-info mr-2"></i>
                                                    <div>
                                                        <strong>Bác sĩ:</strong>
                                                        <div>
                                                            {{ $prescription->medicalRecord->appointment->doctor->user->full_name }}
                                                        </div>
                                                        <small
                                                            class="text-muted">{{ $prescription->medicalRecord->appointment->doctor->specialization }}</small><br>
                                                        <small
                                                            class="text-muted">{{ $prescription->medicalRecord->appointment->doctor->department->name ?? 'Không có' }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6 mb-3">
                                                <span class="badge badge-primary badge-pill">
                                                    {{ $prescription->prescriptionItems->count() }} loại thuốc
                                                </span>
                                            </div>
                                            <div class="col-6 mb-3">
                                                <span class="badge badge-success badge-pill">
                                                    {{ $prescription->prescriptionItems->sum('quantity') }} tổng số lượng
                                                </span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-around mt-3">
                                            <a href="{{ route('admin.prescriptions.show', $prescription->id) }}"
                                                class="btn btn-outline-info btn-sm">
                                                <i class='bx bx-show-alt'></i> Xem
                                            </a>
                                            <a href="{{ route('admin.prescriptions.edit', $prescription->id) }}"
                                                class="btn btn-outline-warning btn-sm">
                                                <i class="bx bx-edit"></i> Sửa
                                            </a>
                                            <button type="button" class="btn btn-outline-danger" data-toggle="tooltip"
                                                title="Xóa"
                                                onclick="deletePrescription({{ $prescription->id }}, '{{ $prescription->medicalRecord->appointment->patient->full_name }}')">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info text-center" role="alert">
                                <i class="bx bx-receipt text-muted" style="font-size: 48px;"></i>
                                <h5 class="mt-3 text-muted">Không có đơn thuốc nào</h5>
                                <p class="text-muted">Chưa có đơn thuốc nào được tạo hoặc không tìm thấy kết quả phù hợp.
                                </p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Enhanced Pagination -->
                    @if ($prescriptions->hasPages())
                        <div class="pagination-wrapper bg-light p-3 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="pagination-info">
                                    <small class="text-muted">
                                        Hiển thị {{ $prescriptions->firstItem() }} - {{ $prescriptions->lastItem() }}
                                        trong tổng số {{ $prescriptions->total() }} kết quả
                                    </small>
                                </div>
                                <div class="pagination-links">
                                    {{ $prescriptions->withQueryString()->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .icon-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-circle i {
            margin: 0px auto;
        }

        .badge-primary {
            background-color: #667eea;
            color: #fff;
        }

        .badge-success {
            background-color: #39DA8A;
            color: #fff;
        }

        .badge-info {
            background-color: #00CFDD;
            color: #fff;
        }

        .badge-warning {
            background-color: #FDAC41;
            color: #212529;
        }

        .badge-danger {
            background-color: #FF5B5C;
            color: #fff;
        }

        .badge-secondary {
            background-color: #6c757d;
            color: #fff;
        }

        .badge-pill {
            border-radius: 10rem;
            padding: 0.25em 0.6em;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-gradient-light {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .btn-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-gradient-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-gradient-danger {
            background: linear-gradient(135deg, #fd79a8 0%, #e84393 100%);
            border: none;
            box-shadow: 0 4px 15px rgba(253, 121, 168, 0.3);
        }

        .btn-gradient-danger:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(253, 121, 168, 0.4);
        }

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

        .table-modern {
            font-size: 0.9rem;
        }

        .table-modern td {
            vertical-align: middle;
            padding: 1rem 0.75rem;
        }

        .prescription-row:hover {
            background-color: rgba(102, 126, 234, 0.05);
        }

        .patient-info h6,
        .doctor-info h6 {
            color: #2c3e50;
        }

        .filter-section {
            border-left: 4px solid #667eea;
        }

        .form-control:focus,
        .custom-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background-color: white;
        }
    </style>
@endpush


@push('scripts')
    <script>
        let deleteId = null;

        const deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const triggerButton = event.relatedTarget;
            deleteId = triggerButton.getAttribute('data-id');
            const name = triggerButton.getAttribute('data-name');

            document.getElementById('deleteModalMessage').textContent =
                `Bạn có chắc chắn muốn xóa ${name}? Dữ liệu sẽ được lưu trong thùng rác và có thể khôi phục sau.`;
        });

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (deleteId) {
                const deleteUrl = `{{ route('admin.prescriptions.destroy', ':id') }}`.replace(':id', deleteId);

                fetch(deleteUrl, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.message) {
                            // Lưu thông báo vào localStorage để hiển thị sau reload
                            localStorage.setItem('flashSuccess', data.message);

                            const modalInstance = bootstrap.Modal.getInstance(deleteModal);
                            modalInstance.hide();

                            // Tải lại trang
                            window.location.href = "{{ route('admin.prescriptions.index') }}";
                        }
                    })
                    .catch(() => {});
            }
        });

        function deletePrescription(id, fullname = 'đơn thuốc') {
            Swal.fire({
                title: 'Xác nhận xóa',
                text: `Bạn có chắc chắn muốn xóa ${fullname}? Dữ liệu sẽ được lưu trong thùng rác và có thể khôi phục sau.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/prescriptions/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Xóa thành công',
                                text: data.message || 'Đã xóa đơn thuốc.'
                            }).then(() => {
                                // Tùy chọn: reload trang hoặc xóa phần tử khỏi DOM
                                location.reload();
                            });
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: 'Không thể xóa đơn thuốc. Vui lòng thử lại.'
                            });
                            console.error(error);
                        });
                }
            });
        }



        // Khi load lại trang, kiểm tra nếu có thông báo thành công thì hiển thị
        window.addEventListener('DOMContentLoaded', function() {
            const successMessage = localStorage.getItem('flashSuccess');
            if (successMessage) {
                toastr.success(successMessage, 'Thành công');
                localStorage.removeItem('flashSuccess');
            }

            // JavaScript for toggling plus/minus icon on accordion collapse/expand
            const collapseElements = document.querySelectorAll('.collapse');

            collapseElements.forEach(function(collapseEl) {
                collapseEl.addEventListener('show.bs.collapse', function() {
                    const icon = document.getElementById('icon' + collapseEl.id.replace('collapse',
                        'Prescription'));
                    if (icon) {
                        icon.classList.remove('fa-plus-circle');
                        icon.classList.add('fa-minus-circle');
                    }
                });

                collapseEl.addEventListener('hide.bs.collapse', function() {
                    const icon = document.getElementById('icon' + collapseEl.id.replace('collapse',
                        'Prescription'));
                    if (icon) {
                        icon.classList.remove('fa-minus-circle');
                        icon.classList.add('fa-plus-circle');
                    }
                });
            });
        });
    </script>
@endpush
