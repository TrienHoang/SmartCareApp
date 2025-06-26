@extends('admin.dashboard')

@section('title', 'Quản lý Đơn hàng')

@section('content')
    <div class="content-wrapper">
        <!-- Enhanced Header Section -->
        <div class="content-header row">
            <div class="content-header-left col-md-8 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-circle bg-primary mr-3">
                                <i class="bx bx-receipt text-white"></i>
                            </div>
                            <div>
                                <h2 class="content-header-title mb-0 text-primary font-weight-bold">Quản lý Đơn hàng</h2>
                                <p class="text-muted mb-0">Quản lý và theo dõi tất cả đơn hàng trong hệ thống</p>
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
                                        Đơn hàng
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="content-body">
            <!-- Enhanced Alert Messages -->
            @if (session('success'))
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
            @endif

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card gradient-card bg-gradient-success">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white mr-2">
                                    <div class="avatar-content">
                                        <i class="bx bx-check-circle font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $orders->where('status', 'completed')->count() }}</h4>
                                    <small class="text-white">Hoàn tất</small>
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
                                        <i class="bx bx-credit-card font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $orders->where('status', 'paid')->count() }}</h4>
                                    <small class="text-white">Đã thanh toán</small>
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
                                        <i class="bx bx-clock font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $orders->where('status', 'pending')->count() }}</h4>
                                    <small class="text-white">Chờ xác nhận</small>
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
                                        <i class="bx bx-x-circle font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $orders->where('status', 'cancelled')->count() }}</h4>
                                    <small class="text-white">Đã hủy</small>
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
                            <h4 class="card-title mb-0 text-white font-weight-bold">Danh sách Đơn hàng</h4>
                        </div>
                        <div class="card-tools">
                            <span class="badge badge-light">{{ $orders->total() }} đơn hàng</span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <!-- Enhanced Filter Section -->
                    <div class="filter-section bg-light p-4 border-bottom">
                        <form action="{{ route('orders.index') }}" method="GET" class="filter-form">
                            <div class="row align-items-end">
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-calendar mr-1 text-primary"></i>Từ ngày
                                    </label>
                                    <input type="date" name="start_date" class="form-control"
                                        value="{{ request('start_date') }}">
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-calendar mr-1 text-info"></i>Đến ngày
                                    </label>
                                    <input type="date" name="end_date" class="form-control"
                                        value="{{ request('end_date') }}">
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-activity mr-1 text-success"></i>Trạng thái
                                    </label>
                                    <select name="status" class="form-control custom-select">
                                        <option value="">-- Tất cả trạng thái --</option>
                                        @php
                                            $statusLabels = [
                                                'pending' => 'Chờ xác nhận',
                                                'paid' => 'Đã thanh toán',
                                                'completed' => 'Hoàn tất',
                                                'cancelled' => 'Đã hủy',
                                            ];
                                        @endphp
                                        @foreach ($statusLabels as $key => $label)
                                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <div class="btn-group w-100" role="group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bx bx-filter mr-1"></i>Lọc
                                        </button>
                                        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                                            <i class="bx bx-refresh mr-1"></i>Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Enhanced Table -->
                    <div class="table-responsive">
                        <table class="table table-hover table-modern mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-top-0">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="select-all">
                                            <label class="custom-control-label" for="select-all"></label>
                                        </div>
                                    </th>
                                    <th class="border-top-0">#ID</th>
                                    <th class="border-top-0">
                                        <i class="mr-1"></i>Người đặt
                                    </th>
                                    <th class="border-top-0">
                                        <i class="mr-1"></i>Tổng tiền
                                    </th>
                                    <th class="border-top-0">
                                        <i class="mr-1"></i>Trạng thái
                                    </th>
                                    <th class="border-top-0">
                                        <i class="mr-1"></i>Thời gian
                                    </th>
                                   
                                    <th class="border-top-0 text-center">
                                        <i class="mr-1"></i>Hành động
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                    <tr class="order-row" data-id="{{ $order->id }}">
                                        <td>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input order-checkbox"
                                                    id="order-{{ $order->id }}" value="{{ $order->id }}">
                                                <label class="custom-control-label" for="order-{{ $order->id }}"></label>
                                            </div>
                                        </td>
                                        <td class="font-weight-bold text-primary">#{{ $order->id }}</td>
                                        <td>
                                            <div class="user-info">
                                                <h6 class="mb-0 font-weight-semibold">
                                                    {{ $order->user->full_name ?? 'Ẩn danh' }}
                                                </h6>
                                                @if($order->user && $order->user->email)
                                                    <small class="text-muted">{{ $order->user->email }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="font-weight-bold text-success">
                                                {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}₫
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $statusConfig = [
                                                    'pending' => ['class' => 'warning', 'icon' => 'clock'],
                                                    'paid' => ['class' => 'info', 'icon' => 'credit-card'],
                                                    'completed' => ['class' => 'success', 'icon' => 'check-circle'],
                                                    'cancelled' => ['class' => 'danger', 'icon' => 'x-circle'],
                                                ];
                                                $config = $statusConfig[$order->status] ?? ['class' => 'secondary', 'icon' => 'help-circle'];
                                            @endphp
                                            <span class="badge badge-{{ $config['class'] }} badge-pill">
                                                <i class="bx bx-{{ $config['icon'] }} mr-1"></i>
                                                {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="time-info">
                                                <div class="d-flex align-items-center text-muted">
                                                    <i class="bx bx-time mr-1"></i>
                                                    <div>
                                                        <small class="font-weight-semibold">Đặt hàng</small><br>
                                                        <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('orders.show', $order) }}"
                                                class="btn btn-outline-info btn-sm" data-toggle="tooltip" title="Xem chi tiết">
                                                <i class="bx bx-show-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="bx bx-receipt text-muted" style="font-size: 48px;"></i>
                                                <h5 class="mt-3 text-muted">Không có đơn hàng nào</h5>
                                                <p class="text-muted">Chưa có đơn hàng nào được tạo hoặc không tìm thấy kết quả phù hợp.</p>
                                                <a href="{{ route('orders.create') ?? '#' }}" class="btn btn-primary">
                                                    <i class="bx bx-plus mr-1"></i>Tạo đơn hàng đầu tiên
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Enhanced Pagination -->
                    @if ($orders->hasPages())
                        <div class="pagination-wrapper bg-light p-3 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="pagination-info">
                                    <small class="text-muted">
                                        Hiển thị {{ $orders->firstItem() }} - {{ $orders->lastItem() }}
                                        trong tổng số {{ $orders->total() }} kết quả
                                    </small>
                                </div>
                                <div class="pagination-links">
                                    {{ $orders->links('pagination::bootstrap-4') }}
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
    <!-- Custom Styles -->
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
            margin: 0px auto
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

        .order-row:hover {
            background-color: rgba(102, 126, 234, 0.05);
        }

        .user-info h6 {
            color: #2c3e50;
        }

        .time-info small {
            line-height: 1.2;
        }

        .filter-section {
            border-left: 4px solid #667eea;
        }

        .form-control:focus,
        .custom-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
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
        // Select all checkboxes functionality
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.order-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Confirm order function
        function confirmOrder(id) {
            Swal.fire({
                title: 'Xác nhận đơn hàng',
                text: 'Bạn có chắc chắn muốn xác nhận đơn hàng này?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Xác nhận',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create and submit form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/orders/${id}/confirm`;

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

        // Cancel order function
        function cancelOrder(id) {
            Swal.fire({
                title: 'Hủy đơn hàng',
                text: 'Bạn có chắc chắn muốn hủy đơn hàng này?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Hủy đơn',
                cancelButtonText: 'Không'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create and submit form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/orders/${id}/cancel`;

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

        // Initialize tooltips
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
    </script>
@endpush