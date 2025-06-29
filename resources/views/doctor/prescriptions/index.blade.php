@extends('doctor.dashboard')
@section('title', 'Quản lý đơn thuốc')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Tìm kiếm đơn thuốc</h3>
            <a href="{{ route('doctor.prescriptions.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tạo đơn thuốc
            </a>
        </div>
        <div class="card-body">
            <form method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="patient_name" class="form-control" placeholder="Tên bệnh nhân" value="{{ request('patient_name') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Tìm
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Danh sách đơn thuốc</h3>
        </div>
        <div class="card-body">
            <script>
                @if (session('success'))
                    toastr.success("{{ session('success') }}");
                @endif
                @if (session('error'))
                    toastr.error("{{ session('error') }}");
                @endif
            </script>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Bệnh nhân</th>
                            <th>Ngày kê</th>
                            <th>Số loại thuốc</th>
                            <th>Tổng số lượng</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prescriptions as $prescription)
                            <tr>
                                <td>{{ ($prescriptions->currentPage() - 1) * $prescriptions->perPage() + $loop->iteration }}</td>
                                <td>{{ $prescription->medicalRecord->appointment->patient->full_name }}</td>
                                <td>{{ $prescription->prescribed_at->format('d/m/Y') }}</td>
                                <td>{{ $prescription->prescriptionItems->count() }}</td>
                                <td>{{ $prescription->prescriptionItems->sum('quantity') }}</td>
                                <td>
                                    <a href="{{ route('doctor.prescriptions.show', $prescription->id) }}" class="btn btn-sm btn-info">
                                        <i class="bx bx-show-alt"></i>
                                    </a>
                                    <a href="{{ route('doctor.prescriptions.edit', $prescription->id) }}" class="btn btn-sm btn-primary" target="_blank }}">
                                        <i class="bx bx-edit"></i>
                                    </a>
                                    <a href="{{ route('doctor.prescriptions.exportPdf', $prescription->id) }}" class="btn btn-sm btn-secondary" target="_blank">
                                        <i class="bx bx-printer"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Không có đơn thuốc nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $prescriptions->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
