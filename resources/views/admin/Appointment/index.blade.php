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
            <div>
                <button class="btn btn-primary" onclick="window.location.reload()">
                    <i class="bx bx-refresh"></i> Làm mới
                </button>
            </div>
        </div>

        <!-- Thống kê nhanh -->
        <div class="stats-card">
            <h5 class="mb-3">Thống kê tổng quan</h5>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">{{ $stats['total'] }}</div>
                    <div>Tổng lịch hẹn</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $stats['today'] }}</div>
                    <div>Hôm nay</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $stats['pending'] }}</div>
                    <div>Chờ xác nhận</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $stats['confirmed'] }}</div>
                    <div>Đã xác nhận</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $stats['completed'] }}</div>
                    <div>Hoàn thành</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $stats['cancelled'] }}</div>
                    <div>Đã hủy</div>
                </div>
            </div>
        </div>

        <!-- Bộ lọc -->
        <div class="filter-section">
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
                    <label class="form-label">Thanh toán</label>
                    <select class="form-control" name="payment_status">
                        <option value="">Tất cả</option>
                        <option value="completed" {{ request('payment_status') == 'completed' ? 'selected' : '' }}>Hoàn tất
                        </option>
                        <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Chưa thanh
                            toán</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Từ ngày</label>
                    <input type="date" class="form-control" name="date_from"
                        value="{{ old('date_from', $from_input ?? request('date_from')) }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Đến ngày</label>
                    <input type="date" class="form-control" name="date_to"
                        value="{{ old('date_to', $to_input ?? request('date_to')) }}">
                </div>

                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-search"></i> Tìm kiếm
                    </button>
                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">
                        <i class="bx bx-reset"></i> Xóa bộ lọc
                    </a>
                </div>
            </form>
        </div>

        <script>
            @if (session('success'))
                toastr.success("{{ session('success') }}", "Thành công");
            @endif

            @if (session('error'))
                toastr.error("{{ session('error') }}", "Lỗi");
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
                                <td>{{ $appointments->firstItem() + $key }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <strong>{{ $appointment->patient->full_name ?? 'N/A' }}</strong>
                                        <small class="text-muted">{{ $appointment->patient->phone ?? '' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span>{{ $appointment->doctor->user->full_name ?? 'N/A' }}</span>
                                        <small class="text-muted">{{ $appointment->doctor->specialization ?? '' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span>{{ $appointment->doctor->room->name ?? 'N/A' }}</span>
                                        <small
                                            class="text-muted">{{ $appointment->doctor->department->name ?? '' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span>{{ $appointment->formatted_time }}</span>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }} (Dự kiến)
                                        </small>
                                    </div>
                                </td>
                                <td>
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
                                <td>
                                    @if (
                                        ($appointment->payment && $appointment->payment->status === 'paid') ||
                                            ($appointment->order && $appointment->order->status === 'completed'))
                                        <span class="badge bg-success">Hoàn tất</span>
                                    @else
                                        <span class="badge bg-danger">Chưa thanh toán</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <a href="{{ route('admin.appointments.show', $appointment->id) }}"
                                            class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                            <i class="bx bx-show"></i>
                                        </a>

                                        @if ($appointment->status != 'completed' && $appointment->status != 'cancelled')
                                            <a href="{{ route('admin.appointments.edit', $appointment->id) }}"
                                                class="btn btn-sm btn-outline-warning" title="Chỉnh sửa">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                        @endif
                                        @if (
                                            $appointment->status !== 'completed' &&
                                                !($appointment->order && $appointment->order->status === 'completed') &&
                                                $appointment->payment &&
                                                $appointment->payment->status !== 'paid')
                                            <form action="{{ route('admin.appointments.pay', $appointment->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-success">Thanh toán</button>
                                            </form>
                                        @endif

                                        @if ($appointment->status === 'confirmed')
                                            <button class="btn btn-sm btn-outline-success"
                                                onclick="updateStatus({{ $appointment->id }}, 'completed')"
                                                title="Hoàn thành">
                                                <i class="bx bx-check-double"></i>
                                            </button>
                                        @endif

                                        @if (optional($appointment->payment)->status !== 'paid')
                                            <form action="{{ route('admin.appointments.pay', $appointment->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-success">Thanh toán</button>
                                            </form>
                                        @endif


                                        @if (in_array($appointment->status, ['pending', 'confirmed']))
                                            <button class="btn btn-sm btn-outline-danger"
                                                onclick="showCancelModal({{ $appointment->id }})" title="Hủy lịch hẹn">
                                                <i class="bx bx-x-circle"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
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
                            Hiển thị {{ $appointments->firstItem() }} - {{ $appointments->lastItem() }}
                            trong tổng số {{ $appointments->total() }} bản ghi
                        </div>
                        {{ $appointments->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
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

    {{-- Modal hủy lịch hẹn --}}
    <div class="modal fade" id="cancelModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="cancelForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Xác nhận hủy lịch hẹn</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Bạn có chắc chắn muốn <strong>hủy lịch hẹn</strong> này không?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Không</button>
                        <button type="submit" class="btn btn-danger">Xác nhận hủy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/Appointment/index.js') }}"></script>


@endsection
