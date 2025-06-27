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

                        @if (session('success'))
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
                                                <small
                                                    class="text-muted">{{ $prescription->medicalRecord->appointment->patient->phone }}</small>
                                            </td>
                                            <td>
                                                {{ $prescription->medicalRecord->appointment->doctor->user->full_name }}<br>
                                                <small
                                                    class="text-muted">{{ $prescription->medicalRecord->appointment->doctor->specialization }}</small>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($prescription->prescribed_at)->format('d/m/Y') }}
                                            </td>
                                            <td>{{ $prescription->prescriptionItems->count() }}</td>
                                            <td>{{ $prescription->prescriptionItems->sum('quantity') }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.prescriptions.trashed-detail', $prescription->id) }}"
                                                        class="btn btn-info" title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                                        data-bs-target="#restoreModal" data-id="{{ $prescription->id }}"
                                                        data-name="Đơn thuốc #{{ $prescription->id }}" title="Khôi phục">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">Không có đơn thuốc nào đã bị
                                                xóa.</td>
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
    <div class="modal fade" id="restoreModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <i class="bx bx-refresh fs-1 text-success mb-2"></i>
                    <h4 class="modal-title mb-2">Xác nhận khôi phục</h4>
                    <p id="restoreModalMessage">Bạn có chắc chắn muốn khôi phục mục này?</p>
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
        // Hiển thị toastr nếu có flash từ localStorage
        document.addEventListener('DOMContentLoaded', () => {
            const msg = localStorage.getItem('flashSuccess');
            if (msg) {
                toastr.success(msg, 'Thành công');
                localStorage.removeItem('flashSuccess');
            }
        });

        let restoreId = null;
        const restoreModalEl = document.getElementById('restoreModal');

        restoreModalEl.addEventListener('show.bs.modal', (event) => {
            const btn = event.relatedTarget;
            restoreId = btn.getAttribute('data-id');
            const name = btn.getAttribute('data-name');
            document.getElementById('restoreModalMessage').textContent = `Bạn có chắc chắn muốn khôi phục ${name}?`;
        });

        document.getElementById('confirmRestoreBtn').addEventListener('click', () => {
            if (!restoreId) return;
            const url = `{{ route('admin.prescriptions.restore', ':id') }}`.replace(':id', restoreId);

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.message) {
                        localStorage.setItem('flashSuccess', data.message);
                        bootstrap.Modal.getInstance(restoreModalEl).hide();
                        window.location.href = "{{ route('admin.prescriptions.trashed') }}";
                    }
                })
                .catch(() => {});
        });
    </script>
@endpush
