@extends('admin.dashboard')
@section('title', 'Quản lý Kế hoạch Điều trị')

@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-8 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-circle bg-primary mr-3">
                                <i class="bx bx-task text-white"></i> <!-- biểu tượng kế hoạch -->
                            </div>

                            <div>
                                <h2 class="content-header-title mb-0 text-primary font-weight-bold">Quản lý Kế hoạch Điều trị
                                </h2>
                                <p class="text-muted mb-0">Quản lý và theo dõi các kế hoạch điều trị của bệnh nhân</p>
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
                                        Kế hoạch Điều trị
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            @foreach (['success', 'error'] as $msg)
                @if (session($msg))
                    <div class="alert alert-{{ $msg == 'error' ? 'danger' : $msg }} alert-dismissible fade show border-0 shadow-sm"
                        role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-{{ $msg == 'success' ? 'check-circle' : 'x-circle' }} mr-2"></i>
                            <strong>{{ $msg == 'success' ? 'Thành công!' : 'Lỗi!' }} </strong> {{ session($msg) }}
                        </div>
                    </div>
                @endif
            @endforeach
            <div class="card-header text-end m-3">
                <a href="{{ route('admin.treatment-plans.trash') }}" class="btn btn-outline-danger">
                    <i class="fas fa-trash"></i> Thùng Rác Kế Hoạch Điều Trị
                </a>
            </div>
            <div class="row mb-4">



                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card gradient-card bg-gradient-success">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white mr-2">
                                    <div class="avatar-content">
                                        <i class="bx bx-list-ol font-medium-5"></i> {{-- Tổng số kế hoạch --}}
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $totalPlans }}</h4>
                                    <small class="text-white">Tổng số</small>
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
                                        <i class="bx bx-check-double font-medium-5"></i> {{-- Kế hoạch hoàn thành --}}
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $completedPlans }}</h4>
                                    <small class="text-white">Hoàn thành</small>
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
                                        <i class="bx bx-loader-alt font-medium-5"></i> {{-- Kế hoạch đang tiến hành --}}
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $inProgressPlans }}</h4>
                                    <small class="text-white">Đang tiến hành</small>
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
                                        <i class="bx bx-pause font-medium-5"></i> {{-- Kế hoạch tạm dừng/hủy bỏ --}}
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $pausedPlans + $cancelledPlans }}</h4>
                                    <small class="text-white">Tạm dừng/Hủy bỏ</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-primary text-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-list-ul mr-2"></i> {{-- Biểu tượng danh sách --}}
                            <h4 class="card-title mb-0 text-white font-weight-bold">Danh sách Kế hoạch Điều trị</h4>
                        </div>
                        <div class="card-tools">
                            <span class="badge badge-light">{{ $plans->total() }} kế hoạch</span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="filter-section bg-light p-4 border-bottom">
                        <form action="{{ route('admin.treatment-plans.index') }}" method="GET" class="filter-form">
                            <div class="row align-items-end">
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                       <i class="bx bx-user text-primary mr-1"></i>Bác sĩ
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="doctor" class="form-control"
                                            placeholder="Tên bác sĩ..." value="{{ request('doctor') }}">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-user mr-1 text-info"></i>Bệnh nhân
                                    </label>
                                    <input type="text" name="patient" class="form-control" placeholder="Tên bệnh nhân..."
                                        value="{{ request('patient') }}">
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-check-circle mr-1 text-success"></i>Trạng thái
                                    </label>
                                    <select name="status" class="form-control custom-select">
                                        <option value="">Tất cả trạng thái</option>

                                        <option value="hoan_thanh"
                                            {{ request('status') == 'hoan_thanh' ? 'selected' : '' }}>Hoàn thành</option>
                                        <option value="chua_tien_hanh"
                                            {{ request('status') == 'chua_tien_hanh' ? 'selected' : '' }}>Chưa tiến hành
                                        </option>
                                        <option value="dang_tien_hanh"
                                            {{ request('status') == 'dang_tien_hanh' ? 'selected' : '' }}>Đang tiến hành
                                        </option>
                                        <option value="tam_dung" {{ request('status') == 'tam_dung' ? 'selected' : '' }}>
                                            Tạm dừng</option>
                                        <option value="huy_bo" {{ request('status') == 'huy_bo' ? 'selected' : '' }}>Hủy
                                            bỏ
                                        </option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <div class="btn-group w-100" role="group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bx bx-filter mr-1"></i>Lọc
                                        </button>
                                        <a href="{{ route('admin.treatment-plans.index') }}"
                                            class="btn btn-outline-secondary">
                                            <i class="bx bx-refresh-cw mr-1"></i>Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-modern mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-top-0">#ID</th>
                                    <th class="border-top-0">
                                        <i class="mr-1"></i>Title
                                    </th>
                                    <th class="border-top-0">
                                        <i class="mr-1"></i>Bệnh nhân
                                    </th>
                                    <th class="border-top-0">
                                        <i class="mr-1"></i>Bác sĩ
                                    </th>

                                    <th class="border-top-0">
                                        <i class="mr-1"></i>Ngày bắt đầu
                                    </th>
                                    <th class="border-top-0">
                                        <i class="mr-1"></i>Ngày kết thúc
                                    </th>
                                    <th class="border-top-0">
                                        <i class="mr-1"></i>Trạng thái
                                    </th>
                                    <th class="border-top-0 text-center">
                                        <i class="mr-1"></i>Hành động
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($plans as $plan)
                                    <tr class="plan-row" data-id="{{ $plan->id }}">
                                        <td class="font-weight-bold text-primary">
                                            {{ $loop->iteration + ($plans->currentPage() - 1) * $plans->perPage() }}
                                        </td>
                                        <td>
                                            <span class="text-dark">{{ Str::limit($plan->plan_title, limit: 50) }}</span>
                                        </td>
                                        <td>
                                            <div class="patient-info">
                                                <h6 class="mb-0 font-weight-semibold text-primary">
                                                    {{ $plan->patient->full_name ?? 'N/A' }}</h6>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="doctor-info">
                                                <h6 class="mb-0 font-weight-semibold text-info">
                                                    {{ $plan->doctor->full_name ?? 'N/A' }}</h6>
                                            </div>
                                        </td>

                                        {{-- Sửa lỗi hiển thị màu chữ ngày --}}
                                        <td>
                                            <span
                                                class="badge badge-light text-dark">{{ \Carbon\Carbon::parse($plan->start_date)->format('d/m/Y') }}</span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge badge-light text-dark">{{ \Carbon\Carbon::parse($plan->end_date)->format('d/m/Y') }}</span>
                                        </td>
                                        {{-- Sửa lỗi hiển thị trạng thái và biểu tượng --}}
                                        <td>
                                            @php
                                                $statusText = '';
                                                $statusClass = '';
                                                $statusIcon = '';
                                                switch ($plan->status) {
                                                    case 'hoan_thanh':
                                                        $statusText = 'Hoàn thành';
                                                        $statusClass = 'success';
                                                        $statusIcon = 'bx-check-double'; // Hoặc bx-check-circle
                                                        break;
                                                    case 'dang_tien_hanh':
                                                        $statusText = 'Đang tiến hành';
                                                        $statusClass = 'info';
                                                        $statusIcon = 'bx-loader-alt';
                                                        break;
                                                    case 'tam_dung':
                                                        $statusText = 'Tạm dừng';
                                                        $statusClass = 'warning';
                                                        $statusIcon = 'bx-pause';
                                                        break;
                                                    case 'huy_bo':
                                                        $statusText = 'Hủy bỏ';
                                                        $statusClass = 'danger';
                                                        $statusIcon = 'bx-x-circle';
                                                        break;
                                                    case 'chua_tien_hanh':
                                                        $statusText = 'Chưa tiến hành';
                                                        $statusClass = 'secondary';
                                                        // $statusIcon = 'bx-x-circle';
                                                        break;
                                                    default:
                                                        $statusText = '';
                                                        $statusClass = 'secondary';
                                                        $statusIcon = 'bx-question-mark';
                                                        break;
                                                }
                                            @endphp
                                            <span class="badge badge-{{ $statusClass }} badge-pill">
                                                <i class="bx {{ $statusIcon }} mr-1"></i>
                                                {{ $statusText }}
                                            </span>
                                        </td>

                                        <td>
                                            <a href="{{ route('admin.treatment-plans.show', $plan->id) }}"
                                                class="btn btn-outline-info">
                                                <i class="bx bx-show-alt"></i>
                                            </a>
                                            <a href="{{ route('admin.treatment-plans.edit', $plan->id) }}"
                                                class="btn btn-outline-warning"> <i class="bx bx-edit"></i></a>

                                            {{-- Chỉ hiển thị nút Xóa nếu chưa hoàn thành --}}
                                            @if ($plan->status !== 'hoan_thanh' && $plan->status !== 'dang_tien_hanh')
                                                <button type="button" class="btn btn-outline-danger"
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                    data-id="{{ $plan->id }}" data-name="{{ $plan->plan_title }}">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            @endif

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="bx bx-file-blank text-muted" style="font-size: 48px;"></i>
                                                <h5 class="mt-3 text-muted">Không có kế hoạch điều trị nào</h5>
                                                <p class="text-muted">Chưa có kế hoạch nào được tạo hoặc không tìm thấy kết
                                                    quả phù hợp.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($plans->hasPages())
                        <div class="pagination-wrapper bg-light p-3 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="pagination-info">
                                    <small class="text-muted">
                                        Hiển thị {{ $plans->firstItem() }} - {{ $plans->lastItem() }}
                                        trong tổng số {{ $plans->total() }} kết quả
                                    </small>
                                </div>
                                <div class="pagination-links">
                                    {{ $plans->appends(request()->query())->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal (Add this if you don't have a global one) --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <i class="bx bx-error-circle fs-1 text-warning mb-2"></i>
                    <h4 class="modal-title mb-2">Xác nhận xóa mềm</h4>
                    <p id="deleteModalMessage">
                        Bạn có chắc chắn muốn xóa mục này? Dữ liệu sẽ được lưu trong thùng rác và có thể khôi phục sau.
                    </p>
                    <div class="d-flex justify-content-center gap-2 mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Xóa</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

{{-- @push('scripts')
    <script>
        function deletePlan(id) {
            // Correctly set the action URL using Laravel's route helper in JavaScript
            // This assumes your route name is 'admin.treatment-plans.destroy'
            $('#deletePlanForm').attr('action', '{{ route('admin.treatment-plans.destroy', ':id') }}'.replace(':id', id));
            $('#deletePlanModal').modal('show');
        }

        // Handle "Select All" checkbox
        $('#select-all').on('change', function() {
            $('.plan-checkbox').prop('checked', $(this).prop('checked'));
        });

        // If any individual checkbox is unchecked, uncheck "Select All"
        $('.plan-checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select-all').prop('checked', false);
            }
        });

        // Initialize tooltips
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
@endpush --}}
@push('scripts')
    <script>
        let deleteId = null;
        const deleteModal = document.getElementById('deleteModal');

        deleteModal.addEventListener('show.bs.modal', function(event) {
            const triggerButton = event.relatedTarget;
            deleteId = triggerButton.getAttribute('data-id');
            const name = triggerButton.getAttribute('data-name');

            document.getElementById('deleteModalMessage').textContent =
                `Bạn có chắc chắn muốn xóa kế hoạch điều trị "${name}"? Dữ liệu sẽ được lưu trong thùng rác và có thể khôi phục sau.`;
        });

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (deleteId) {
                const deleteUrl = `{{ route('admin.treatment-plans.destroy', ':id') }}`.replace(':id', deleteId);

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
                            localStorage.setItem('flashSuccess', data.message);
                            const modalInstance = bootstrap.Modal.getInstance(deleteModal);
                            modalInstance.hide();
                            window.location.href = "{{ route('admin.treatment-plans.index') }}";
                        }
                    })
                    .catch(() => {});
            }
        });

        window.addEventListener('DOMContentLoaded', function() {
            const successMessage = localStorage.getItem('flashSuccess');
            if (successMessage) {
                toastr.success(successMessage, 'Thành công');
                localStorage.removeItem('flashSuccess');
            }
        });
    </script>
@endpush



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

        .badge-primary {
            background-color: #667eea;
            color: #fff;
        }

        .badge-pill {
            border-radius: 10rem;
            padding: 0.25em 0.6em;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

        .plan-row:hover {
            /* Đã đổi từ user-row sang plan-row */
            background-color: rgba(102, 126, 234, 0.05);
        }

        .patient-info h6,
        .doctor-info h6 {
            /* Đã thêm doctor-info */
            color: #2c3e50;
        }

        .filter-section {
            border-left: 4px solid #667eea;
        }

        .form-control:focus,
        .custom-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .input-group-text {
            border-right: none;
        }

        .form-control.border-left-0 {
            border-left: none;
        }

        .empty-state {
            padding: 2rem;
        }

        .pagination-wrapper {
            background: linear-gradient(90deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .btn-group-sm .btn {
            border-radius: 4px;
            margin-right: 2px;
        }

        .btn-group-sm .btn:last-child {
            margin-right: 0;
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

        @media (max-width: 768px) {
            .filter-form .row>div {
                margin-bottom: 1rem;
            }

            .btn-group-sm {
                flex-direction: column;
            }

            .btn-group-sm .btn {
                margin-bottom: 2px;
                margin-right: 0;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Chức năng chọn tất cả các ô kiểm
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.plan-checkbox'); /* Đã đổi từ user-checkbox */
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Hàm xóa kế hoạch điều trị
        function deletePlan(id) {
            /* Đã đổi từ deleteUser */
            Swal.fire({
                title: 'Xác nhận xóa',
                text: 'Bạn có chắc chắn muốn xóa kế hoạch điều trị này? Hành động này không thể hoàn tác!',
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
                    form.action = `/admin/treatment-plans/${id}`; /* Đã đổi route */

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

        // Khởi tạo tooltips và các chức năng khác
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();

            // Tự động ẩn thông báo sau 5 giây
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
    </script>
@endpush
