@extends('admin.dashboard')

@section('content')

<div class="container mt-4">
    <h1 class="text-center text-primary fw-bold mb-4">Lịch sử thanh toán</h1>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('admin.payment_histories.index') }}" class="row g-3 justify-content-center mb-4">
        <div class="col-md-3">
            <input type="text" name="patient_name" class="form-control" placeholder="Tên bệnh nhân" value="{{ request('patient_name') }}">
        </div>
        <div class="col-md-2">
            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
        </div>
        <div class="col-md-2">
            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
        </div>
        <div class="col-md-2">
            <select name="service_id" class="form-select">
                <option value="">Dịch vụ</option>
                @foreach($services as $service)
                    <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                        {{ $service->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="doctor_id" class="form-select">
                <option value="">Bác sĩ</option>
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                        {{ $doctor->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-1 d-grid">
            <button type="submit" class="btn btn-primary">Lọc</button>
        </div>
    </form>

    <!-- Table or Empty -->
    @if($histories->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-nowrap shadow-sm">
                <thead class="table-primary">
                    <tr>
                        <th>Mã hóa đơn</th>
                        <th>Tên bệnh nhân</th>
                        <th>Dịch vụ</th>
                        <th>Bác sĩ</th>
                        <th>Ngày thanh toán</th>
                        <th>Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($histories as $history)
                    <tr>
                        <td>{{ $history->payment->id }}</td>
                        <td>{{ optional($history->payment->appointment->patient)->name ?? 'Không có' }}</td>
                        <td>{{ optional($history->payment->appointment->service)->name ?? 'Không có' }}</td>
                        <td>{{ optional($history->payment->appointment->doctor->user)->full_name ?? 'N/A' }}</td>
                        <td>{{ $history->payment_date ? \Carbon\Carbon::parse($history->payment_date)->format('d/m/Y H:i') : 'Chưa thanh toán' }}</td>
                        <td>
                            <a href="{{ route('admin.payment_histories.show', $history->id) }}" class="btn btn-sm btn-outline-primary">Xem</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {{ $histories->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="alert alert-warning text-center">
            Không tìm thấy kết quả phù hợp.
        </div>
    @endif
</div>

@endsection
