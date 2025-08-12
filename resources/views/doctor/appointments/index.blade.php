@extends('doctor.dashboard')

@section('title', 'Lịch khám của tôi')

@section('content')
    <div class="container py-4"> {{-- Thêm padding top/bottom cho container chính --}}
        <div class="mb-5 text-center"> {{-- Căn giữa tiêu đề chính --}}
            <h2 class="text-primary fw-bold display-4 mb-2">Lịch khám của bạn</h2> {{-- Tiêu đề lớn hơn, nổi bật hơn --}}
            <p class="text-muted fs-5">Dễ dàng quản lý các lịch hẹn đã được đặt.</p> {{-- Mô tả rõ ràng hơn, font lớn hơn --}}
        </div>

        {{-- Thống kê lịch khám --}}
        <div class="row g-4 mb-5"> {{-- Sử dụng g-4 cho khoảng cách giữa các cột --}}
            <div class="col-lg-3 col-md-6 col-12">
                <div
                    class="card bg-warning text-white shadow-lg border-0 rounded-4 h-100 d-flex flex-column justify-content-between">
                    {{-- Thêm shadow, border-0, rounded lớn hơn, đảm bảo chiều cao đồng nhất --}}
                    <div class="card-body py-4"> {{-- Thêm padding --}}
                        <h4 class="mb-2 display-6 fw-bold">{{ $appointments_pending ?? 0 }}</h4> {{-- Số lớn hơn, đậm hơn --}}
                        <p class="mb-0 fs-5">Chờ xác nhận</p> {{-- Font lớn hơn --}}
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div
                    class="card bg-info text-white shadow-lg border-0 rounded-4 h-100 d-flex flex-column justify-content-between">
                    <div class="card-body py-4">
                        <h4 class="mb-2 display-6 fw-bold">{{ $appointments_confirmed ?? 0 }}</h4>
                        <p class="mb-0 fs-5">Đã xác nhận</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div
                    class="card bg-success text-white shadow-lg border-0 rounded-4 h-100 d-flex flex-column justify-content-between">
                    <div class="card-body py-4">
                        <h4 class="mb-2 display-6 fw-bold">{{ $appointments_completed ?? 0 }}</h4>
                        <p class="mb-0 fs-5">Hoàn tất</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12"> {{-- Thêm cột tổng số lịch khám --}}
                <div
                    class="card bg-primary text-white shadow-lg border-0 rounded-4 h-100 d-flex flex-column justify-content-between">
                    <div class="card-body py-4">
                        <h4 class="mb-2 display-6 fw-bold">
                            {{ ($appointments_pending ?? 0) + ($appointments_confirmed ?? 0) + ($appointments_completed ?? 0) }}
                        </h4>
                        <p class="mb-0 fs-5">Tổng số lịch khám</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Thông báo --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                {{-- Thêm dismissible --}}
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                {{-- Thêm dismissible --}}
                <i class="fas fa-times-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <hr class="my-5"> {{-- Đường kẻ phân cách rõ ràng hơn --}}

        {{-- Bộ lọc --}}
        <div class="card shadow-lg mb-5 rounded-4 border-0"> {{-- Bo góc lớn hơn, không viền --}}
            <div class="card-header bg-primary text-white p-4 rounded-top-4"> {{-- Padding lớn hơn, bo góc trên --}}
                <h4 class="mb-0 fs-4"><i class="fas fa-filter me-3"></i>Bộ Lọc Lịch Khám</h4> {{-- Icon lớn hơn, font size lớn hơn --}}
            </div>
            <div class="card-body p-4 p-md-5"> {{-- Padding lớn hơn trên các màn hình lớn hơn --}}
                <form action="{{ route('doctor.appointments.index') }}" method="GET">
                    <div class="row g-4 align-items-end"> {{-- Khoảng cách lớn hơn giữa các cột --}}
                        <div class="col-md-5 col-lg-4">
                            <label for="patient_name" class="form-label fw-bold mb-2">Tên bệnh nhân</label>
                            {{-- Thêm margin-bottom cho label --}}
                            <input type="text" name="patient_name" id="patient_name"
                                value="{{ request('patient_name') }}" class="form-control form-control-lg rounded-3"
                                {{-- Bo góc input --}} placeholder="Nhập tên bệnh nhân...">
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <label for="status" class="form-label fw-bold mb-2">Trạng thái</label>
                            <select name="status" id="status" class="form-select form-select-lg rounded-3">
                                {{-- Bo góc select --}}
                                <option value="">Tất cả trạng thái</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận
                                </option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác
                                    nhận</option>
                                <option value="checked_in" {{ request('status') == 'checked_in' ? 'selected' : '' }}>Đã check in</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn tất
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3 col-lg-4 d-grid gap-3"> {{-- Thêm gap giữa các nút --}}
                            <button type="submit" class="btn btn-primary btn-lg rounded-3"> {{-- Bo góc nút --}}
                                <i class="fas fa-search me-2"></i>Lọc
                            </button>
                            <a href="{{ route('doctor.appointments.index') }}"
                                class="btn btn-outline-secondary btn-lg rounded-3"> {{-- Bo góc nút --}}
                                <i class="fas fa-redo me-2"></i>Đặt lại
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <hr class="my-5">

        {{-- Danh sách lịch khám --}}
        @forelse($appointments as $appointment)
            <div class="card mb-4 shadow-lg border-0 rounded-4 appointment-card"> {{-- Shadow lớn hơn, bo góc lớn hơn, không viền --}}
                <div class="card-body p-4 p-md-5"> {{-- Padding lớn hơn --}}
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="text-primary fw-bold mb-3 fs-4"> {{-- Font size lớn hơn, margin lớn hơn --}}
                                <i class="fas fa-stethoscope me-3"></i>Dịch vụ: {{ $appointment->service->name ?? 'N/A' }}
                            </h4>
                            <p class="mb-2 text-dark fs-6"><strong><i class="fas fa-user-injured me-3"></i>Bệnh
                                    nhân:</strong>
                                {{ $appointment->patient->full_name ?? 'Ẩn danh' }}</p>
                            <p class="mb-2 text-dark fs-6"><strong><i class="fas fa-calendar-alt me-3"></i>Thời
                                    gian:</strong>
                                {{ optional($appointment->appointment_time)->format('d/m/Y H:i') }}</p>
                            @if ($appointment->reason)
                                <p class="mb-0 text-dark fs-6"><strong><i class="fas fa-clipboard-question me-3"></i>Lý
                                        do:</strong>
                                    {{ $appointment->reason }}</p>
                            @endif
                            <p class="mb-0 text-dark fs-6"><strong><i class="fas fa-clock me-3"></i>Trạng thái:</strong>
                                {{ ucfirst($appointment->status) }}</p>
                        </div>
                        <div class="col-md-4 text-md-end mt-4 mt-md-0"> {{-- Margin top lớn hơn trên mobile --}}
                            <a href="{{ route('doctor.appointments.show', $appointment->id) }}"
                                class="btn btn-primary btn-lg shadow-sm rounded-pill px-4"> {{-- Nút bo tròn, padding ngang lớn hơn --}}
                                <i class="fas fa-eye me-2"></i>Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info text-center py-5 rounded-4 shadow-lg"> {{-- Padding lớn hơn, bo góc, shadow --}}
                <p class="mb-0 fs-5"><i class="fas fa-info-circle me-2"></i>Không có lịch hẹn nào được tìm thấy. Hãy thử
                    điều chỉnh bộ lọc của bạn!</p>
            </div>
        @endforelse

        {{-- Phân trang --}}
        <div class="mt-5 d-flex justify-content-center">
            {{ $appointments->withQueryString()->links() }}
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Các style tùy chỉnh */
        .appointment-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .appointment-card:hover {
            transform: translateY(-8px);
            /* Nhấc lên cao hơn khi hover */
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.2) !important;
            /* Shadow lớn hơn */
        }

        /* Tùy chỉnh phân trang Bootstrap */
        .pagination .page-item .page-link {
            border-radius: .5rem !important;
            /* Bo góc nhẹ cho các nút phân trang */
            margin: 0 .25rem;
            /* Khoảng cách giữa các nút */
            transition: all 0.3s ease;
        }

        .pagination .page-item.active .page-link {
            background-color: var(--bs-primary);
            /* Màu nền primary */
            border-color: var(--bs-primary);
            /* Màu viền primary */
            color: white;
            /* Chữ trắng */
            box-shadow: 0 .25rem .5rem rgba(var(--bs-primary-rgb), .25);
            /* Shadow nhẹ */
        }

        .pagination .page-item .page-link:hover {
            background-color: var(--bs-primary-rgb, .1);
            /* Nền hơi xanh khi hover */
            color: var(--bs-primary);
            /* Chữ xanh khi hover */
            border-color: var(--bs-primary);
        }
    </style>
@endpush
