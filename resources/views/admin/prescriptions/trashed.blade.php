@extends('admin.dashboard')
@section('title', 'Đơn thuốc đã xóa')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-1 text-dark fw-bold">
                                    <i class="fas fa-trash-restore text-warning me-2"></i>
                                    Đơn thuốc đã xóa
                                </h4>
                                <p class="text-muted mb-0 small">Quản lý các đơn thuốc đã bị xóa khỏi hệ thống</p>
                            </div>
                            <div>
                                <a href="{{ route('admin.prescriptions.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Quay lại đơn thuốc
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show border-0 rounded-0 mb-0" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                            </div>
                        @endif

                        <!-- Thống kê nhanh -->
                        <div class="p-3 bg-light border-bottom">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-danger bg-opacity-10 p-2 rounded me-3">
                                            <i class="fas fa-trash text-danger"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $prescriptions->total() }}</h6>
                                            <small class="text-muted">Tổng đơn thuốc đã xóa</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-info bg-opacity-10 p-2 rounded me-3">
                                            <i class="fas fa-pills text-info"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $prescriptions->sum(function($p) { return $p->prescriptionItems->count(); }) }}</h6>
                                            <small class="text-muted">Tổng loại thuốc</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-warning bg-opacity-10 p-2 rounded me-3">
                                            <i class="fas fa-calculator text-warning"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $prescriptions->sum(function($p) { return $p->prescriptionItems->sum('quantity'); }) }}</h6>
                                            <small class="text-muted">Tổng số lượng</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success bg-opacity-10 p-2 rounded me-3">
                                            <i class="fas fa-undo text-success"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Khôi phục</h6>
                                            <small class="text-muted">Có thể khôi phục</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-3 py-3 border-0">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-hashtag text-muted me-2"></i>
                                                STT
                                            </div>
                                        </th>
                                        <th class="px-3 py-3 border-0">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user text-muted me-2"></i>
                                                Bệnh nhân
                                            </div>
                                        </th>
                                        <th class="px-3 py-3 border-0">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user-md text-muted me-2"></i>
                                                Bác sĩ
                                            </div>
                                        </th>
                                        <th class="px-3 py-3 border-0">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-calendar text-muted me-2"></i>
                                                Ngày kê đơn
                                            </div>
                                        </th>
                                        <th class="px-3 py-3 border-0 text-center">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <i class="fas fa-pills text-muted me-2"></i>
                                                Số thuốc
                                            </div>
                                        </th>
                                        <th class="px-3 py-3 border-0 text-center">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <i class="fas fa-calculator text-muted me-2"></i>
                                                Tổng SL
                                            </div>
                                        </th>
                                        <th class="px-3 py-3 border-0 text-center">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <i class="fas fa-cogs text-muted me-2"></i>
                                                Thao tác
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($prescriptions as $prescription)
                                        <tr class="border-bottom">
                                            <td class="px-3 py-4">
                                                <span class="badge bg-secondary">{{ $loop->iteration }}</span>
                                            </td>
                                            <td class="px-3 py-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                                        <i class="fas fa-user text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1 fw-semibold">{{ $prescription->medicalRecord->appointment->patient->full_name }}</h6>
                                                        <small class="text-muted">
                                                            <i class="fas fa-phone me-1"></i>
                                                            {{ $prescription->medicalRecord->appointment->patient->phone }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-3 py-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                                        <i class="fas fa-user-md text-success"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1 fw-semibold">{{ $prescription->medicalRecord->appointment->doctor->user->full_name }}</h6>
                                                        <small class="text-muted">
                                                            <i class="fas fa-stethoscope me-1"></i>
                                                            {{ $prescription->medicalRecord->appointment->doctor->specialization }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-3 py-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-info bg-opacity-10 rounded px-2 py-1">
                                                        <i class="fas fa-calendar-day text-info me-1"></i>
                                                        <span class="fw-medium">{{ \Carbon\Carbon::parse($prescription->prescribed_at)->format('d/m/Y') }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-3 py-4 text-center">
                                                <span class="badge bg-warning text-dark fw-semibold">{{ $prescription->prescriptionItems->count() }}</span>
                                            </td>
                                            <td class="px-3 py-4 text-center">
                                                <span class="badge bg-info fw-semibold">{{ $prescription->prescriptionItems->sum('quantity') }}</span>
                                            </td>
                                            <td class="px-3 py-4 text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.prescriptions.trashed-detail', $prescription->id) }}"
                                                        class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    <button type="button" class="btn btn-sm btn-outline-success" 
                                                        data-bs-toggle="modal" data-bs-target="#restoreModal" 
                                                        data-id="{{ $prescription->id }}"
                                                        data-name="Đơn thuốc #{{ $prescription->id }}" 
                                                        title="Khôi phục">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <div class="d-flex flex-column align-items-center">
                                                    <div class="bg-light rounded-circle p-3 mb-3">
                                                        <i class="fas fa-trash text-muted fa-2x"></i>
                                                    </div>
                                                    <h6 class="text-muted mb-2">Không có đơn thuốc nào đã bị xóa</h6>
                                                    <p class="text-muted small mb-0">Hiện tại chưa có đơn thuốc nào trong thùng rác</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($prescriptions->hasPages())
                            <div class="d-flex justify-content-between align-items-center p-3 bg-light border-top">
                                <div class="text-muted small">
                                    Hiển thị {{ $prescriptions->firstItem() }} - {{ $prescriptions->lastItem() }} 
                                    trong tổng số {{ $prescriptions->total() }} kết quả
                                </div>
                                <div>
                                    {{ $prescriptions->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal khôi phục -->
    <div class="modal fade" id="restoreModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-body text-center p-4">
                    <div class="bg-success bg-opacity-10 rounded-circle p-3 d-inline-flex mb-3">
                        <i class="fas fa-undo fa-2x text-success"></i>
                    </div>
                    <h4 class="modal-title mb-2 fw-bold">Xác nhận khôi phục</h4>
                    <p id="restoreModalMessage" class="text-muted mb-4">Bạn có chắc chắn muốn khôi phục mục này?</p>
                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Hủy
                        </button>
                        <button type="button" id="confirmRestoreBtn" class="btn btn-success px-4">
                            <i class="fas fa-undo me-1"></i> Khôi phục
                        </button>
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
                toastr.success(msg, 'Thành công', {
                    progressBar: true,
                    timeOut: 3000,
                    positionClass: 'toast-top-right'
                });
                localStorage.removeItem('flashSuccess');
            }
        });

        let restoreId = null;
        const restoreModalEl = document.getElementById('restoreModal');

        // Xử lý khi modal được mở
        restoreModalEl.addEventListener('show.bs.modal', (event) => {
            const btn = event.relatedTarget;
            restoreId = btn.getAttribute('data-id');
            const name = btn.getAttribute('data-name');
            document.getElementById('restoreModalMessage').textContent = `Bạn có chắc chắn muốn khôi phục ${name}?`;
        });

        // Xử lý nút xác nhận khôi phục
        document.getElementById('confirmRestoreBtn').addEventListener('click', () => {
            if (!restoreId) return;
            
            const btn = document.getElementById('confirmRestoreBtn');
            const originalText = btn.innerHTML;
            
            // Disable button và hiển thị loading
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Đang xử lý...';
            
            const url = `{{ route('admin.prescriptions.restore', ':id') }}`.replace(':id', restoreId);

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.message) {
                        localStorage.setItem('flashSuccess', data.message);
                        bootstrap.Modal.getInstance(restoreModalEl).hide();
                        
                        // Reload trang sau khi ẩn modal
                        setTimeout(() => {
                            window.location.reload();
                        }, 300);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Có lỗi xảy ra khi khôi phục. Vui lòng thử lại!', 'Lỗi');
                })
                .finally(() => {
                    // Restore button state
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                });
        });

        // Xử lý khi modal được ẩn
        restoreModalEl.addEventListener('hidden.bs.modal', () => {
            restoreId = null;
            document.getElementById('restoreModalMessage').textContent = 'Bạn có chắc chắn muốn khôi phục mục này?';
        });
    </script>
@endpush

@push('styles')
    <style>
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .btn-group .btn {
            border-radius: 0.375rem;
        }
        
        .btn-group .btn:not(:last-child) {
            margin-right: 0.25rem;
        }
        
        .modal-content {
            border-radius: 1rem;
        }
        
        .badge {
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .card {
            border-radius: 1rem;
        }
        
        .card-header {
            border-radius: 1rem 1rem 0 0;
        }
        
        .table th {
            font-weight: 600;
            font-size: 0.875rem;
            color: #495057;
        }
        
        .text-muted {
            color: #6c757d !important;
        }
        
        .fw-semibold {
            font-weight: 600;
        }
        
        .bg-opacity-10 {
            background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
        }
    </style>
@endpush