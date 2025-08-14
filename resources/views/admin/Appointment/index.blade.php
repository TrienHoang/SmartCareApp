@extends('admin.dashboard')
@section('title', 'Quản lý Lịch hẹn Khám')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/Appointment/index.css') }}">
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="py-3 breadcrumb-wrapper mb-0">
                <span class="text-muted fw-light">Quản lý /</span> Lịch hẹn khám
            </h4>
            <button class="btn btn-primary" onclick="window.location.reload()">
                <i class="bx bx-refresh"></i> Làm mới
            </button>
        </div>

        <!-- Thống kê nhanh -->
        <div class="stats-card mb-4">
            <h5 class="mb-3">Thống kê tổng quan</h5>
            <div class="stats-grid">
                <div class="stat-item total">
                    <i class="bx bx-send icon"></i>
                    <div class="stat-number">{{ $stats['total'] }}</div>
                    <div>Tổng lịch hẹn</div>
                </div>
                <div class="stat-item today">
                    <i class="bx bx-calendar icon"></i>
                    <div class="stat-number">{{ $stats['today'] }}</div>
                    <div>Hôm nay</div>
                </div>
                <div class="stat-item pending">
                    <i class="bx bx-loader icon bx-spin"></i>
                    <div class="stat-number">{{ $stats['pending'] }}</div>
                    <div>Chờ xác nhận</div>
                </div>
                <div class="stat-item confirmed">
                    <i class="bx bx-check-circle icon"></i>
                    <div class="stat-number">{{ $stats['confirmed'] }}</div>
                    <div>Đã xác nhận</div>
                </div>
                <div class="stat-item completed">
                    <i class="bx bx-check-double icon"></i>
                    <div class="stat-number">{{ $stats['completed'] }}</div>
                    <div>Hoàn thành</div>
                </div>
                <div class="stat-item cancelled">
                    <i class="bx bx-x-circle icon"></i>
                    <div class="stat-number">{{ $stats['cancelled'] }}</div>
                    <div>Đã hủy</div>
                </div>
            </div>
        </div>

        <!-- Bộ lọc -->
        <div class="filter-section mb-4">
            <form method="GET" action="{{ route('admin.appointments.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Tìm kiếm bệnh nhân</label>
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                        placeholder="Tên hoặc số điện thoại...">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Trạng thái</label>
                    <select class="form-control" name="status">
                        <option value="">Tất cả</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận
                        </option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành
                        </option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Bác sĩ</label>
                    <select class="form-control" name="doctor_id">
                        <option value="">Tất cả bác sĩ</option>
                        @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->id }}"
                                {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->user->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Khoa</label>
                    <select class="form-control" name="department_id">
                        <option value="">Tất cả khoa</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}"
                                {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Dịch vụ</label>
                    <select class="form-control" name="service_id">
                        <option value="">Tất cả dịch vụ</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}"
                                {{ request('service_id') == $service->id ? 'selected' : '' }}>
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Từ ngày</label>
                    <input type="date" class="form-control" name="date_from"
                        value="{{ old('date_from', request('date_from')) }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Đến ngày</label>
                    <input type="date" class="form-control" name="date_to"
                        value="{{ old('date_to', request('date_to')) }}">
                </div>

                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-search"></i> Tìm kiếm
                    </button>
                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">
                        <i class="bx bx-reset"></i> Xóa bộ lọc
                    </a>
                    <a href="{{ route('admin.appointments.create') }}" class="btn btn-success">
                        <i class="bx bx-plus"></i> Thêm lịch hẹn
                    </a>
                </div>
            </form>
        </div>

        <!-- Thông báo -->
        <script>
            @if (session('success'))
                toastr.success("{{ session('success') }}", "Thành công");
            @endif

            @if (session('error'))
                toastr.error("{{ session('error') }}", "Lỗi");
            @endif

            @if (session('date_swapped'))
                toastr.info("Ngày bắt đầu và ngày kết thúc đã được tự động hoán đổi vì ngày bắt đầu lớn hơn ngày kết thúc.",
                    "Thông báo");
            @endif
        </script>

        <!-- Bảng danh sách -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bx bx-calendar-check"></i>
                    Danh sách lịch hẹn ({{ $appointments->total() }} bản ghi)
                </h5>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm" onchange="changePagination(this.value)" style="width: auto;">
                        <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15/trang</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25/trang</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50/trang</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>STT</th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'patient.full_name', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                    class="text-white text-decoration-none">
                                    Bệnh nhân
                                    @if (request('sort_by') == 'patient.full_name')
                                        <i
                                            class="bx bx-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}-arrow-alt"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Bác sĩ</th>
                            <th>Phòng/Khoa</th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'appointment_time', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                    class="text-white text-decoration-none">
                                    Thời gian
                                    @if (request('sort_by') == 'appointment_time')
                                        <i
                                            class="bx bx-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}-arrow-alt"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Trạng thái</th>
                            <th>Thanh toán</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($appointments as $key => $appointment)
                            <tr>
                                <td data-label="STT">{{ $appointments->firstItem() + $key }}</td>
                                <td data-label="Bệnh nhân">
                                    <div class="d-flex flex-column">
                                        <strong>{{ $appointment->patient->full_name ?? 'N/A' }}</strong>
                                        <small class="text-muted">{{ $appointment->patient->phone ?? '' }}</small>
                                    </div>
                                </td>
                                <td data-label="Bác sĩ">
                                    <div class="d-flex flex-column">
                                        <span>{{ $appointment->doctor->user->full_name ?? 'N/A' }}</span>
                                        <small class="text-muted">{{ $appointment->service->name ?? '' }}</small>
                                    </div>
                                </td>
                                <td data-label="Phòng/Khoa">
                                    <div class="d-flex flex-column">
                                        <span>{{ $appointment->doctor->room->name ?? 'N/A' }}</span>
                                        <small
                                            class="text-muted">{{ $appointment->doctor->department->name ?? '' }}</small>
                                    </div>
                                </td>
                                <td data-label="Thời gian">
                                    <div class="d-flex flex-column">
                                        <span>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d/m/Y H:i') }}</span>
                                        <small class="text-muted">
                                            {{ $appointment->end_time ? \Carbon\Carbon::parse($appointment->end_time)->format('H:i') : 'N/A' }}
                                            (Dự kiến)
                                        </small>
                                    </div>
                                </td>
                                <td data-label="Trạng thái">
                                    @php
                                        $statusConfig = [
                                            'pending' => [
                                                'color' => 'warning',
                                                'text' => 'Chờ xác nhận',
                                                'icon' => 'bx-time',
                                            ],
                                            'confirmed' => [
                                                'color' => 'info',
                                                'text' => 'Đã xác nhận',
                                                'icon' => 'bx-check',
                                            ],
                                            'completed' => [
                                                'color' => 'success',
                                                'text' => 'Hoàn thành',
                                                'icon' => 'bx-check-double',
                                            ],
                                            'cancelled' => ['color' => 'danger', 'text' => 'Đã hủy', 'icon' => 'bx-x'],
                                        ];
                                        $config = $statusConfig[$appointment->status] ?? [
                                            'color' => 'secondary',
                                            'text' => $appointment->status,
                                            'icon' => 'bx-help',
                                        ];
                                    @endphp

                                    <span class="badge bg-{{ $config['color'] }} status-badge">
                                        <i class="bx {{ $config['icon'] }}"></i> {{ $config['text'] }}
                                    </span>
                                </td>
                                <td data-label="Thanh toán">
                                    @php
                                        $payment = optional($appointment->payment);
                                    @endphp

                                    @if ($payment && $payment->refund_status === 'completed')
                                        <span class="badge bg-success">
                                            <i class="bx bx-check-circle me-1"></i>
                                            Đã hoàn tiền
                                        </span>
                                    @else
                                        @switch($payment->status)
                                            @case('paid')
                                                <span class="badge bg-success">
                                                    <i class="bx bx-check-circle me-1"></i>
                                                    Đã thanh toán
                                                </span>
                                            @break

                                            @case('overpaid')
                                                <span class="badge bg-info text-dark">
                                                    <i class="bx bx-money me-1"></i>
                                                    Thanh toán dư
                                                </span>
                                            @break

                                            @case('unpaid')
                                                <span class="badge bg-danger">
                                                    <i class="bx bx-x-circle me-1"></i>
                                                    Chưa thanh toán
                                                </span>
                                            @break

                                            @default
                                                <span class="badge bg-danger">
                                                    <i class="bx bx-x-circle me-1"></i>
                                                    Chưa thanh toán
                                                </span>
                                        @endswitch

                                        @if ($payment && $payment->refund_status && $payment->refund_status !== 'completed')
                                            @php
                                                $refundConfig = [
                                                    'none' => ['text' => 'Chưa hoàn tiền', 'color' => 'secondary'],
                                                    'pending' => ['text' => 'Đang hoàn tiền', 'color' => 'warning'],
                                                    'failed' => ['text' => 'Hoàn tiền lỗi', 'color' => 'danger'],
                                                ];
                                                $refund = $refundConfig[$payment->refund_status] ?? [
                                                    'text' => 'Không rõ',
                                                    'color' => 'dark',
                                                ];
                                            @endphp
                                            <br>
                                            <span class="badge bg-{{ $refund['color'] }}">
                                                <i class="bx bx-undo me-1"></i>
                                                {{ $refund['text'] }}
                                            </span>
                                        @endif
                                    @endif
                                </td>
                                <td data-label="Thao tác">
                                    <div class="table-actions d-flex gap-1">
                                        <!-- Xem chi tiết -->
                                        <a href="{{ route('admin.appointments.show', $appointment->id) }}"
                                            class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                            <i class="bx bx-show"></i>
                                        </a>

                                        <!-- Chỉnh sửa -->
                                        @if (in_array($appointment->status, ['pending', 'confirmed']))
                                            <a href="{{ route('admin.appointments.edit', $appointment->id) }}"
                                                class="btn btn-sm btn-outline-warning" title="Chỉnh sửa">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                        @endif

                                        <!-- Hoàn thành -->
                                        @if ($appointment->status === 'confirmed' && optional($appointment->payment)->status === 'paid')
                                            <button class="btn btn-sm btn-outline-success"
                                                onclick="updateStatus({{ $appointment->id }}, 'completed')"
                                                title="Hoàn thành">
                                                <i class="bx bx-check-double"></i>
                                            </button>
                                        @endif

                                        <!-- Thanh toán -->
                                        @if (in_array($appointment->status, ['pending', 'confirmed']) && optional($appointment->payment)->status !== 'paid')
                                            @if ($appointment->status === 'confirmed')
                                                <form action="{{ route('admin.appointments.pay', $appointment->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    <button class="btn btn-sm btn-outline-success" title="Thanh toán">
                                                        <i class="bx bx-money"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button class="btn btn-sm btn-outline-secondary" disabled
                                                    title="Vui lòng xác nhận trước khi thanh toán">
                                                    <i class="bx bx-money"></i>
                                                </button>
                                            @endif
                                        @endif

                                        <!-- Hủy lịch hẹn -->
                                        @if (in_array($appointment->status, ['pending', 'confirmed']))
                                            <button class="btn btn-sm btn-outline-danger"
                                                onclick="showCancelModal({{ $appointment->id }})" title="Hủy lịch hẹn">
                                                <i class="bx bx-x-circle"></i>
                                            </button>
                                        @endif

                                        <!-- Hoàn tiền -->
                                        @if (
                                            $appointment->status === 'cancelled' &&
                                                in_array(optional($appointment->payment)->status, ['paid', 'overpaid']) &&
                                                $appointment->payment->refund_status !== 'completed')
                                            <form action="{{ route('admin.appointments.refund', $appointment->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Bạn chắc chắn muốn hoàn tiền? Hành động này không thể hoàn tác.')">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-danger" title="Hoàn tiền">
                                                    <i class="bx bx-undo"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bx bx-calendar-x" style="font-size: 3rem;"></i>
                                            <div class="mt-2">Không tìm thấy lịch hẹn nào</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($appointments->hasPages())
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                Hiển thị {{ $appointments->firstItem() }} - {{ $appointments->lastItem() }} trong tổng số
                                {{ $appointments->total() }} bản ghi
                            </div>
                            {{ $appointments->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Modal cập nhật trạng thái -->
            <div class="modal fade" id="statusModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Cập nhật trạng thái lịch hẹn</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form id="statusForm" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="modal-body">
                                <input type="hidden" name="current_status" id="currentStatusInput" value="">
                                <div class="mb-3">
                                    <label class="form-label">Trạng thái mới</label>
                                    <select class="form-control" name="status" id="statusSelect" required>
                                        <option value="pending">Chờ xác nhận</option>
                                        <option value="confirmed">Đã xác nhận</option>
                                        <option value="completed">Hoàn thành</option>
                                        <option value="cancelled">Đã hủy</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ghi chú (tùy chọn)</label>
                                    <textarea class="form-control" name="note" rows="3"
                                        placeholder="Nhập ghi chú về việc thay đổi trạng thái..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal hủy lịch hẹn -->
            <div class="modal fade" id="cancelModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="cancelForm" method="POST" action="">
                            @csrf
                            @method('PATCH') <!-- Add this to match the route method -->
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">Xác nhận hủy lịch hẹn</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Bạn có chắc chắn muốn <strong>hủy lịch hẹn</strong> này không?</p>
                                <div class="mb-3">
                                    <label class="form-label">Lý do hủy (tùy chọn)</label>
                                    <textarea class="form-control" name="note" rows="3" placeholder="Nhập lý do hủy lịch hẹn..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Không</button>
                                <button type="submit" class="btn btn-danger">Xác nhận hủy</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script src="{{ asset('js/Appointment/index.js') }}"></script>
            <script>
                function changePagination(perPage) {
                    const url = new URL(window.location);
                    url.searchParams.set('per_page', perPage);
                    window.location.href = url.toString();
                }

                function updateStatus(appointmentId, status) {
                    const form = document.getElementById('statusForm');
                    form.action = `{{ url('admin/appointments') }}/${appointmentId}/update-status`;
                    document.getElementById('currentStatusInput').value = status;
                    document.getElementById('statusSelect').value = status;
                    new bootstrap.Modal(document.getElementById('statusModal')).show();
                }

                function showCancelModal(appointmentId) {
                    const form = document.getElementById('cancelForm');
                    form.action = `{{ url('admin/appointments') }}/${appointmentId}/cancel`;
                    new bootstrap.Modal(document.getElementById('cancelModal')).show();
                }
            </script>
        @endpush
    @endsection
