@extends('admin.dashboard')
@section('title', 'Đơn thuốc đã xóa')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Danh sách đơn thuốc đã xóa</h4>
                    <a href="{{ route('admin.prescriptions.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Quay lại đơn thuốc
                    </a>
                </div>

                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Bệnh nhân</th>
                                    <th>Bác sĩ</th>
                                    <th>Ngày kê đơn</th>
                                    <th>Số thuốc</th>
                                    <th>Tổng SL</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($prescriptions as $prescription)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            {{ $prescription->medicalRecord->appointment->patient->full_name }}<br>
                                            <small class="text-muted">{{ $prescription->medicalRecord->appointment->patient->phone }}</small>
                                        </td>
                                        <td>
                                            {{ $prescription->medicalRecord->appointment->doctor->user->full_name }}<br>
                                            <small class="text-muted">{{ $prescription->medicalRecord->appointment->doctor->specialization }}</small>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($prescription->prescribed_at)->format('d/m/Y') }}</td>
                                        <td>{{ $prescription->prescriptionItems->count() }}</td>
                                        <td>{{ $prescription->prescriptionItems->sum('quantity') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.prescriptions.trashed-detail', $prescription->id) }}"
                                                    class="btn btn-info" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <form action="{{ route('admin.prescriptions.restore', $prescription->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Khôi phục đơn thuốc này?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success" style="width: 20px" title="Khôi phục">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Không có đơn thuốc nào đã bị xóa.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        {{ $prescriptions->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
