@extends('admin.dashboard')
@section('title', 'Danh sách Đơn thuốc')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 breadcrumb-wrapper mb-4">
            <span class="text-muted fw-light">Prescriptions /</span> Danh sách Đơn thuốc
        </h4>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Danh sách đơn thuốc</h5>
            </div>

            @if (session('success'))
                <div class="alert alert-success m-3">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger m-3">
                    {{ session('error') }}
                </div>
            @endif

            <div class="table-responsive text-nowrap">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>STT</th>
                            <th>Mã hồ sơ Y tế</th>
                            <th>Bệnh nhân</th>
                            <th>Ngày kê đơn</th>
                            <th>Ghi chú</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($prescriptions as $prescription)
                            <tr>
                                <td>{{ $loop->iteration + ($prescriptions->currentPage() - 1) * $prescriptions->perPage() }}</td>
                                <td>{{ $prescription->medicalRecord->code ?? 'N/A' }}</td>
                                <td>{{ $prescription->medicalRecord->appointment->patient->full_name ?? 'N/A' }}</td>
                                <td>{{ $prescription->formatted_date }}</td>
                                <td>{{ $prescription->notes ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.prescriptions.show', $prescription->id) }}" class="btn btn-info btn-sm">
                                        <i class="bx bx-show"></i> Xem
                                    </a>
                                    {{-- <a href="{{ route('prescriptions.print', $prescription->id) }}" target="_blank" class="btn btn-secondary btn-sm">
                                        <i class="bx bx-printer"></i> In
                                    </a> --}}
                                    {{-- Thêm nút sửa nếu cần --}}
                                    <a href="{{ route('admin.prescriptions.edit', $prescription->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer d-flex justify-content-end">
                {{ $prescriptions->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
