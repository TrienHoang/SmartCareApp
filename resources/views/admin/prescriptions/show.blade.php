@extends('admin.dashboard')
@section('title', 'Chi tiết Đơn thuốc')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Chi tiết Đơn thuốc #{{ $prescription->id }}</h4>
        </div>
        <div class="card-body">
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label fw-bold">Bệnh nhân:</label>
                <div class="col-sm-10">
                    <p class="form-control-plaintext">{{ $prescription->medicalRecord->appointment->patient->full_name ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label fw-bold">Ngày kê:</label>
                <div class="col-sm-10">
                    <p class="form-control-plaintext">{{ $prescription->formatted_date }}</p>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label fw-bold">Ghi chú:</label>
                <div class="col-sm-10">
                    <p class="form-control-plaintext">{{ $prescription->notes ?? 'Không có ghi chú' }}</p>
                </div>
            </div>

            <h5 class="fw-bold mt-4">Danh sách thuốc được kê</h5>
            <table class="table table-bordered mt-2">
                <thead class="table-light">
                    <tr>
                        <th>Tên thuốc</th>
                        <th>Số lượng</th>
                        <th>Cách dùng</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($prescription->items as $item)
                        <tr>
                            <td>{{ $item->medicine->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->usage_instructions }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">Không có thuốc nào được kê.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.prescriptions.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle"></i> Quay lại
            </a>
        </div>
    </div>
</div>
@endsection
