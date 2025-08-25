@extends('doctor.dashboard')
@section('title', 'Quản lý Kế hoạch điều trị')

@section('content')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-clipboard-list mr-2"></i>
                    Quản lý Kế hoạch Điều trị
                </h1>
                <p class="text-muted mb-0">Theo dõi và quản lý các kế hoạch điều trị cho bệnh nhân</p>
            </div>
            <a href="{{ route('doctor.treatment-plans.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i>
                Tạo Kế hoạch Mới
            </a>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Filter Section -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-filter mr-2"></i>
                    Bộ lọc Kế hoạch
                </h6>
                <button class="btn btn-sm btn-outline-primary" type="button" data-toggle="collapse" data-target="#filterCollapse">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </div>
            <div class="collapse show" id="filterCollapse">
                <div class="card-body">
                    <form method="GET" action="{{ route('doctor.treatment-plans.index') }}" id="filterForm">
                        <div class="row">
                            <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                    <label for="plan_title" class="form-label">
                                        <i class="fas fa-search mr-1"></i>
                                        Tìm theo tiêu đề
                                    </label>
                                    <input type="text" name="plan_title" id="plan_title" class="form-control"
                                        value="{{ request('plan_title') }}" placeholder="Nhập tiêu đề kế hoạch...">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                    <label for="patient_id" class="form-label">
                                        <i class="fas fa-user mr-1"></i>
                                        Bệnh nhân
                                    </label>
                                    <select name="patient_id" id="patient_id" class="form-control select2">
                                        <option value="">Tất cả bệnh nhân</option>
                                        @if (request('patient_id'))
                                            <option value="{{ request('patient_id') }}" selected>
                                                {{ \App\Models\User::find(request('patient_id'))?->full_name }}
                                            </option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                    <label for="status" class="form-label">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Trạng thái
                                    </label>
                                    <select name="status" id="status" class="form-control">
                                        @foreach ($statuses as $statusOption)
                                            <option value="{{ $statusOption }}"
                                                {{ request('status', 'all') == $statusOption ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_', ' ', $statusOption)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label for="start_date_filter" class="form-label">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        Ngày bắt đầu từ
                                    </label>
                                    <input type="date" name="start_date_filter" id="start_date_filter" class="form-control"
                                        value="{{ request('start_date_filter') }}">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label for="end_date_filter" class="form-label">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        Ngày kết thúc đến
                                    </label>
                                    <input type="date" name="end_date_filter" id="end_date_filter" class="form-control"
                                        value="{{ request('end_date_filter') }}">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('doctor.treatment-plans.index') }}" class="btn btn-outline-secondary mr-2"
                                id="resetFilterBtn">
                                <i class="fas fa-undo mr-1"></i>
                                Đặt lại
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter mr-1"></i>
                                Lọc kết quả
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Tổng kế hoạch
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $treatmentPlans->total() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Đang thực hiện
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $treatmentPlans->where('status', 'in_progress')->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-play-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Hoàn thành
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $treatmentPlans->where('status', 'completed')->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Tạm dừng
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $treatmentPlans->where('status', 'paused')->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-pause-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-table mr-2"></i>
                    Danh sách Kế hoạch Điều trị
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="treatmentPlansTable">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">ID</th>
                                <th width="20%">Tiêu đề</th>
                                <th width="15%">Bệnh nhân</th>
                                <th width="10%">Trạng thái</th>
                                <th width="12%">Ngày bắt đầu</th>
                                <th width="12%">Ngày kết thúc</th>
                                <th width="12%">Ngày tạo</th>
                                <th width="14%">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($treatmentPlans as $plan)
                                <tr>
                                    <td>
                                        <span class="text-muted">#{{ $plan->id }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $plan->plan_title }}</strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm mr-2">
                                                <div class="avatar-title bg-primary rounded-circle">
                                                    {{ strtoupper(substr($plan->patient->full_name ?? 'N', 0, 1)) }}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-weight-bold">{{ $plan->patient->full_name ?? 'N/A' }}</div>
                                                <small class="text-muted">{{ $plan->patient->email ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-pill {{ $plan->getBadgeClassForStatus($plan->status) }}">
                                            {{ ucfirst(str_replace('_', ' ', $plan->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar-alt text-muted mr-1"></i>
                                        {{ $plan->start_date?->format('d/m/Y') ?? 'N/A' }}
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar-alt text-muted mr-1"></i>
                                        {{ $plan->end_date?->format('d/m/Y') ?? 'N/A' }}
                                    </td>
                                    <td>
                                        <i class="fas fa-clock text-muted mr-1"></i>
                                        {{ $plan->created_at?->format('d/m/Y H:i') }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('doctor.treatment-plans.show', $plan) }}" 
                                               class="btn btn-info btn-sm" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('doctor.treatment-plans.edit', $plan) }}"
                                               class="btn btn-warning btn-sm" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm" 
                                                    onclick="confirmDelete({{ $plan->id }})" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        
                                        <!-- Hidden delete form -->
                                        <form id="delete-form-{{ $plan->id }}" 
                                              action="{{ route('doctor.treatment-plans.destroy', $plan) }}" 
                                              method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="empty-state">
                                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">Không tìm thấy kế hoạch điều trị nào</h5>
                                            <p class="text-muted">Hãy thử điều chỉnh bộ lọc hoặc tạo kế hoạch mới.</p>
                                            <a href="{{ route('doctor.treatment-plans.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus mr-2"></i>
                                                Tạo Kế hoạch Đầu tiên
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <small class="text-muted">
                            Hiển thị {{ $treatmentPlans->firstItem() ?? 0 }} - {{ $treatmentPlans->lastItem() ?? 0 }} 
                            trong tổng số {{ $treatmentPlans->total() }} kết quả
                        </small>
                    </div>
                    <div>
                        {{ $treatmentPlans->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="deleteModalLabel">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Xác nhận xóa
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa kế hoạch điều trị này không?</p>
                    <p class="text-muted">
                        <i class="fas fa-info-circle mr-1"></i>
                        Hành động này sẽ xóa kế hoạch và tất cả các mục liên quan. Không thể hoàn tác!
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" onclick="executeDelete()">
                        <i class="fas fa-trash mr-2"></i>
                        Xóa kế hoạch
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .avatar-sm {
            width: 32px;
            height: 32px;
        }
        
        .avatar-title {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            font-weight: 600;
        }
        
        .empty-state {
            padding: 2rem;
        }
        
        .card {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .table th {
            border-top: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            color: #5a5c69;
        }
        
        .table td {
            vertical-align: middle;
        }
        
        .badge-pill {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
        }
        
        .btn-group .btn {
            margin-right: 0.25rem;
        }
        
        .btn-group .btn:last-child {
            margin-right: 0;
        }
        
        .border-left-primary {
            border-left: 0.25rem solid #4e73df !important;
        }
        
        .border-left-success {
            border-left: 0.25rem solid #1cc88a !important;
        }
        
        .border-left-info {
            border-left: 0.25rem solid #36b9cc !important;
        }
        
        .border-left-warning {
            border-left: 0.25rem solid #f6c23e !important;
        }
        
        .form-label {
            font-weight: 600;
            color: #5a5c69;
            margin-bottom: 0.5rem;
        }
        
        .select2-container--default .select2-selection--single {
            height: calc(1.5em + 0.75rem + 2px);
            padding: 0.375rem 0.75rem;
            border: 1px solid #d1d3e2;
        }
        
        .table-responsive {
            border-radius: 0.35rem;
        }
        
        .pagination {
            margin-bottom: 0;
        }
        
        @media (max-width: 768px) {
            .btn-group {
                flex-direction: column;
            }
            
            .btn-group .btn {
                margin-bottom: 0.25rem;
                margin-right: 0;
            }
            
            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        let deleteId = null;
        
        function confirmDelete(id) {
            deleteId = id;
            $('#deleteModal').modal('show');
        }
        
        function executeDelete() {
            if (deleteId) {
                document.getElementById('delete-form-' + deleteId).submit();
            }
        }
        
        $(document).ready(function() {
            // Initialize Select2 for patient search
            $('#patient_id').select2({
                placeholder: "Tìm kiếm bệnh nhân...",
                allowClear: true,
                ajax: {
                    url: "{{ route('doctor.treatment-plans.searchPatient') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            query: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {
                        return {
                            results: data.map(function(patient) {
                                return {
                                    id: patient.id,
                                    text: patient.full_name + ' (' + patient.email + ')'
                                };
                            }),
                        };
                    },
                    cache: true
                },
                minimumInputLength: 2
            });

            // Initialize Flatpickr for date inputs
            const startDatePicker = flatpickr("#start_date_filter", {
                dateFormat: "Y-m-d",
                maxDate: "#end_date_filter",
                allowInput: true,
                locale: "vn",
                onChange: function(selectedDates, dateStr, instance) {
                    if (!dateStr) {
                        $('#filterForm').submit();
                    }
                }
            });

            const endDatePicker = flatpickr("#end_date_filter", {
                dateFormat: "Y-m-d",
                minDate: "#start_date_filter",
                allowInput: true,
                locale: "vn",
                onChange: function(selectedDates, dateStr, instance) {
                    if (!dateStr) {
                        $('#filterForm').submit();
                    }
                }
            });

            // Reset filter button
            $('#resetFilterBtn').on('click', function() {
                const form = $('#filterForm')[0];
                if (form) {
                    startDatePicker.clear();
                    endDatePicker.clear();
                    $('#patient_id').val(null).trigger('change');
                }
            });

            // Auto-submit form when status changes
            $('#status').on('change', function() {
                $('#filterForm').submit();
            });

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();
            
            // Add loading state to buttons
            $('.btn[type="submit"]').on('click', function() {
                const btn = $(this);
                const originalText = btn.html();
                btn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Đang xử lý...');
                btn.prop('disabled', true);
                
                setTimeout(function() {
                    btn.html(originalText);
                    btn.prop('disabled', false);
                }, 3000);
            });
        });
    </script>
@endpush