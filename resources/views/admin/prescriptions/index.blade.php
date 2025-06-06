@extends('admin.dashboard')
@section('title', 'Danh sách Đơn thuốc')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 breadcrumb-wrapper mb-4">
            <span class="text-muted fw-light">Prescriptions /</span> Danh sách Đơn thuốc
        </h4>

        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Danh sách Đơn thuốc</h5>
            </div>

            @if (session('success'))
                <div class="alert alert-success m-3">
                    <i class="bx bx-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger m-3">
                    <i class="bx bx-error-circle"></i> {{ session('error') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light text-center">
                        <tr>
                            <th>STT</th>
                            <th>Mã đơn thuốc</th>
                            <th>Tên bệnh nhân</th>
                            <th>Ngày kê đơn</th>
                            <th>Bác sĩ kê</th>
                            <th>Tên thuốc</th>
                            <th>Liều dùng</th>
                            <th>Ghi chú</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @forelse ($prescriptions as $prescription)
                            <tr>
                                <td>{{ $loop->iteration + ($prescriptions->currentPage() - 1) * $prescriptions->perPage() }}
                                </td>
                                <td>{{ $prescription->medicalRecord->code ?? 'N/A' }}</td>
                                <td>{{ $prescription->medicalRecord->appointment->patient->full_name ?? 'N/A' }}</td>
                                <td>{{ $prescription->formatted_date ?? '-' }}</td>
                                <td>
                                    {{ $prescription->medicalRecord->appointment->doctor->user->full_name ?? 'N/A' }}
                                </td>
                                <td>
                                    @foreach ($prescription->prescriptionItems as $item)
                                        <div>{{ $item->medicine->name ?? 'N/A' }}</div>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($prescription->prescriptionItems as $item)
                                        <div>{{ $item->usage_instructions ?? 'N/A' }}</div>
                                    @endforeach
                                </td>
                                <td>{{ $prescription->notes ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.prescriptions.show', $prescription->id) }}"
                                        class="btn btn-sm btn-info me-1">
                                        <i class="bx bx-show"></i> Xem
                                    </a>
                                    <a href="{{ route('admin.prescriptions.edit', $prescription->id) }}"
                                        class="btn btn-sm btn-warning">
                                        <i class="bx bx-edit-alt"></i> Sửa
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-muted">Không có đơn thuốc nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer d-flex justify-content-end">
                {{ $prescriptions->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
