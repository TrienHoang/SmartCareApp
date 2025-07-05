@extends('doctor.dashboard')

@section('content')
<div class="container mt-4">
    <h4 class="text-primary font-weight-bold mb-3">🩺 Lịch sử khám bệnh</h4>

    <!-- Hiển thị thông báo lỗi nếu có -->
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Lỗi!</strong> {{ session('error') }}
        </div>
    @endif

    <!-- Bộ lọc -->
    <form method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-3 mb-2">
                <input type="text" name="patient_name" class="form-control"
                       placeholder="Tên bệnh nhân" value="{{ request('patient_name') }}">
            </div>
            <div class="col-md-3 mb-2">
                <input type="text" name="email" class="form-control"
                       placeholder="Email" value="{{ request('email') }}">
            </div>
            <div class="col-md-3 mb-2">
                <input type="text" name="phone" class="form-control"
                       placeholder="Số điện thoại" value="{{ request('phone') }}">
            </div>
            <div class="col-md-3 mb-2">
                <input type="text" name="service_name" class="form-control"
                       placeholder="Dịch vụ" value="{{ request('service_name') }}">
            </div>
            <div class="col-md-3 mb-2">
                <input type="date" name="from_date" class="form-control"
                       value="{{ request('from_date') }}">
            </div>
            <div class="col-md-3 mb-2">
                <input type="date" name="to_date" class="form-control"
                       value="{{ request('to_date') }}">
            </div>
            <div class="col-md-3 mb-2 d-flex gap-2">
                <button class="btn btn-primary w-100" type="submit">
                    <i class="bx bx-filter"></i> Lọc
                </button>
            </div>
            <div class="col-md-3 mb-2 d-flex">
                <a href="{{ route('doctor.history.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="bx bx-refresh"></i> Reset
                </a>
            </div>
        </div>
    </form>

    <!-- Bảng dữ liệu -->
    <div class="table-responsive shadow-sm rounded">
        <table class="table table-hover table-bordered mb-0">
            <thead class="thead-light">
                <tr>
                    <th>Bệnh nhân</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Dịch vụ</th>
                    <th>Ngày khám</th>
                    <th class="text-center">Chi tiết</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($appointments as $appointment)
                    <tr>
                        <td>{{ $appointment->patient->full_name ?? 'N/A' }}</td>
                        <td>{{ $appointment->patient->email ?? 'N/A' }}</td>
                        <td>{{ $appointment->patient->phone ?? 'N/A' }}</td>
                        <td>{{ $appointment->service->name ?? 'N/A' }}</td>
                        <td>{{ $appointment->appointment_time->format('d/m/Y H:i') }}</td>
                        <td class="text-center">
                            <a href="{{ route('doctor.history.show', $appointment->id) }}"
                               class="btn btn-sm btn-primary">
                                <i class="bx bx-show"></i> Chi tiết
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bx bx-calendar-x" style="font-size: 2rem;"></i><br>
                            Không có lịch sử khám bệnh phù hợp.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Phân trang -->
    <div class="mt-3 d-flex justify-content-between align-items-center">
        <div>
            <small class="text-muted">
                Hiển thị {{ $appointments->firstItem() }} - {{ $appointments->lastItem() }} /
                {{ $appointments->total() }}
            </small>
        </div>
        <div>
            {{ $appointments->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
<style>
    .table td, .table th {
        vertical-align: middle;
    }
    .gap-2 {
        gap: 0.5rem;
    }
</style>
@endpush
