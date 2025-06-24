@extends('admin.dashboard')
@section('title', 'Chi tiết đơn thuốc đã xóa')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Chi tiết đơn thuốc đã xóa</h4>
                        <a href="{{ route('admin.prescriptions.trashed') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Quay lại danh sách
                        </a>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <h5 class="mb-3">Thông tin bệnh nhân</h5>
                        <ul class="list-group mb-4">
                            <li class="list-group-item"><strong>Họ tên:</strong>
                                {{ $prescription->medicalRecord->appointment->patient->full_name }}</li>
                            <li class="list-group-item"><strong>Số điện thoại:</strong>
                                {{ $prescription->medicalRecord->appointment->patient->phone }}</li>
                        </ul>

                        <h5 class="mb-3">Thông tin bác sĩ</h5>
                        <ul class="list-group mb-4">
                            <li class="list-group-item"><strong>Họ tên:</strong>
                                {{ $prescription->medicalRecord->appointment->doctor->user->full_name }}</li>
                            <li class="list-group-item"><strong>Chuyên khoa:</strong>
                                {{ $prescription->medicalRecord->appointment->doctor->specialization }}</li>
                            <li class="list-group-item"><strong>Khoa/Phòng ban:</strong>
                                {{ $prescription->medicalRecord->appointment->doctor->department->name ?? 'Không xác định' }}
                            </li>
                        </ul>

                        <h5 class="mb-3">Ngày kê đơn:
                            {{ \Carbon\Carbon::parse($prescription->prescribed_at)->format('d/m/Y') }}</h5>

                        <h5 class="mb-3">Danh sách thuốc</h5>
                        @if ($prescription->items->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Tên thuốc</th>
                                            <th>Số lượng</th>
                                            <th>Hướng dẫn sử dụng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($prescription->items as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $item->medicine->name }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>{{ $item->usage_instructions ?? 'Không có' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">Không có thuốc nào trong đơn.</p>
                        @endif

                        <h5 class="mt-4">Ghi chú</h5>
                        <p>{{ $prescription->notes ?? 'Không có ghi chú' }}</p>

                        <button type="button" class="btn btn-success" id="restorePrescriptionBtn"
                            data-id="{{ $prescription->id }}">
                            <i class="fas fa-undo"></i> Khôi phục đơn thuốc
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="restoreModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <i class="bx bx-refresh fs-1 text-success mb-2"></i>
                    <h4 class="modal-title mb-2">Xác nhận khôi phục</h4>
                    <p>Bạn có chắc chắn muốn khôi phục đơn thuốc này?</p>
                    <div class="d-flex justify-content-center gap-2 mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="button" id="confirmRestoreBtn" class="btn btn-success">Khôi phục</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        let prescriptionId = null;

        // Mở modal khi bấm nút
        document.getElementById('restorePrescriptionBtn').addEventListener('click', function() {
            prescriptionId = this.dataset.id;
            const modal = new bootstrap.Modal(document.getElementById('restoreModal'));
            modal.show();
        });

        // Xác nhận khôi phục
        document.getElementById('confirmRestoreBtn').addEventListener('click', function() {
            const url = `{{ route('admin.prescriptions.restore', ':id') }}`.replace(':id', prescriptionId);

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        bootstrap.Modal.getInstance(document.getElementById('restoreModal')).hide();
                        toastr.success(data.message);
                        setTimeout(() => {
                            window.location.href = "{{ route('admin.prescriptions.trashed') }}";
                        }, 1200);
                    } else {
                        toastr.error(data.message || 'Có lỗi xảy ra');
                    }
                })
                .catch(() => toastr.error('Không thể kết nối đến máy chủ.'));
        });
    </script>
@endpush
