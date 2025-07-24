@extends('admin.dashboard')
@section('title', 'Chi tiết đơn thuốc đã xóa')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-1 text-dark fw-bold">
                                    <i class="fas fa-file-medical-alt text-danger me-2"></i>
                                    Chi tiết đơn thuốc đã xóa
                                </h4>
                                <p class="text-muted mb-0 small">
                                    Mã đơn thuốc: <span class="fw-semibold">#{{ $prescription->id }}</span>
                                </p>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.prescriptions.trashed') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
                                </a>
                                <button type="button" class="btn btn-success" id="restorePrescriptionBtn"
                                    data-id="{{ $prescription->id }}">
                                    <i class="fas fa-undo me-1"></i> Khôi phục
                                </button>
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

                        <!-- Thông tin tổng quan -->
                        <div class="p-4 bg-light border-bottom">
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="text-center p-3 bg-white rounded shadow-sm">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 d-inline-flex mb-2">
                                            <i class="fas fa-calendar-alt text-primary fa-lg"></i>
                                        </div>
                                        <h6 class="mb-1 fw-semibold">Ngày kê đơn</h6>
                                        <p class="text-muted mb-0">{{ \Carbon\Carbon::parse($prescription->prescribed_at)->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center p-3 bg-white rounded shadow-sm">
                                        <div class="bg-info bg-opacity-10 rounded-circle p-3 d-inline-flex mb-2">
                                            <i class="fas fa-pills text-info fa-lg"></i>
                                        </div>
                                        <h6 class="mb-1 fw-semibold">Tổng số thuốc</h6>
                                        <p class="text-muted mb-0">{{ $prescription->prescriptionItems->count() }} loại</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center p-3 bg-white rounded shadow-sm">
                                        <div class="bg-warning bg-opacity-10 rounded-circle p-3 d-inline-flex mb-2">
                                            <i class="fas fa-calculator text-warning fa-lg"></i>
                                        </div>
                                        <h6 class="mb-1 fw-semibold">Tổng số lượng</h6>
                                        <p class="text-muted mb-0">{{ $prescription->prescriptionItems->sum('quantity') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-4">
                            <div class="row g-4">
                                <!-- Thông tin bệnh nhân -->
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 bg-light">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                                    <i class="fas fa-user text-primary"></i>
                                                </div>
                                                <h5 class="mb-0 fw-bold">Thông tin bệnh nhân</h5>
                                            </div>
                                            <div class="space-y-3">
                                                <div class="d-flex align-items-center p-3 bg-white rounded">
                                                    <i class="fas fa-user text-muted me-3"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Họ và tên</small>
                                                        <span class="fw-semibold">{{ $prescription->medicalRecord->appointment->patient->full_name }}</span>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center p-3 bg-white rounded">
                                                    <i class="fas fa-phone text-muted me-3"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Số điện thoại</small>
                                                        <span class="fw-semibold">{{ $prescription->medicalRecord->appointment->patient->phone ?? 'Không có' }}</span>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center p-3 bg-white rounded">
                                                    <i class="fas fa-envelope text-muted me-3"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Email</small>
                                                        <span class="fw-semibold">{{ $prescription->medicalRecord->appointment->patient->email ?? 'Không có' }}</span>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center p-3 bg-white rounded">
                                                    <i class="fas fa-calendar text-muted me-3"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Ngày sinh</small>
                                                        <span class="fw-semibold">
                                                            {{ $prescription->medicalRecord->appointment->patient->date_of_birth ? \Carbon\Carbon::parse($prescription->medicalRecord->appointment->patient->date_of_birth)->format('d/m/Y') : 'Không có' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Thông tin bác sĩ -->
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 bg-light">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                                    <i class="fas fa-user-md text-success"></i>
                                                </div>
                                                <h5 class="mb-0 fw-bold">Thông tin bác sĩ</h5>
                                            </div>
                                            <div class="space-y-3">
                                                <div class="d-flex align-items-center p-3 bg-white rounded">
                                                    <i class="fas fa-user-md text-muted me-3"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Họ và tên</small>
                                                        <span class="fw-semibold">{{ $prescription->medicalRecord->appointment->doctor->user->full_name }}</span>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center p-3 bg-white rounded">
                                                    <i class="fas fa-stethoscope text-muted me-3"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Chuyên khoa</small>
                                                        <span class="fw-semibold">{{ $prescription->medicalRecord->appointment->doctor->specialization ?? 'Không xác định' }}</span>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center p-3 bg-white rounded">
                                                    <i class="fas fa-building text-muted me-3"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Khoa/Phòng ban</small>
                                                        <span class="fw-semibold">{{ $prescription->medicalRecord->appointment->doctor->department->name ?? 'Không xác định' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Thông tin cuộc hẹn -->
                            <div class="card mt-4 border-0 bg-light">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="fas fa-calendar-check text-info"></i>
                                        </div>
                                        <h5 class="mb-0 fw-bold">Thông tin cuộc hẹn</h5>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center p-3 bg-white rounded">
                                                <i class="fas fa-clock text-muted me-3"></i>
                                                <div>
                                                    <small class="text-muted d-block">Thời gian hẹn</small>
                                                    <span class="fw-semibold">{{ \Carbon\Carbon::parse($prescription->medicalRecord->appointment->appointment_time)->format('d/m/Y H:i') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center p-3 bg-white rounded">
                                                <i class="fas fa-info-circle text-muted me-3"></i>
                                                <div>
                                                    <small class="text-muted d-block">Trạng thái</small>
                                                    <span class="badge bg-{{ $prescription->medicalRecord->appointment->status == 'completed' ? 'success' : ($prescription->medicalRecord->appointment->status == 'pending' ? 'warning' : 'secondary') }}">
                                                        {{ ucfirst($prescription->medicalRecord->appointment->status) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Thông tin bệnh án -->
                            <div class="card mt-4 border-0 bg-light">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="fas fa-notes-medical text-warning"></i>
                                        </div>
                                        <h5 class="mb-0 fw-bold">Thông tin bệnh án</h5>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="p-3 bg-white rounded">
                                                <h6 class="mb-2 fw-semibold">Triệu chứng</h6>
                                                <p class="text-muted mb-0">{{ $prescription->medicalRecord->symptoms ?? 'Không có thông tin' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="p-3 bg-white rounded">
                                                <h6 class="mb-2 fw-semibold">Chẩn đoán</h6>
                                                <p class="text-muted mb-0">{{ $prescription->medicalRecord->diagnosis ?? 'Không có thông tin' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="p-3 bg-white rounded">
                                                <h6 class="mb-2 fw-semibold">Phương pháp điều trị</h6>
                                                <p class="text-muted mb-0">{{ $prescription->medicalRecord->treatment ?? 'Không có thông tin' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Danh sách thuốc -->
                            <div class="card mt-4 border-0 bg-light">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="fas fa-pills text-info"></i>
                                        </div>
                                        <h5 class="mb-0 fw-bold">Danh sách thuốc</h5>
                                    </div>

                                    @if ($prescription->prescriptionItems->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover bg-white rounded overflow-hidden shadow-sm">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="border-0 px-4 py-3">
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-hashtag text-muted me-2"></i>
                                                                STT
                                                            </div>
                                                        </th>
                                                        <th class="border-0 px-4 py-3">
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-capsules text-muted me-2"></i>
                                                                Tên thuốc
                                                            </div>
                                                        </th>
                                                        <th class="border-0 px-4 py-3 text-center">
                                                            <div class="d-flex align-items-center justify-content-center">
                                                                <i class="fas fa-calculator text-muted me-2"></i>
                                                                Số lượng
                                                            </div>
                                                        </th>
                                                        <th class="border-0 px-4 py-3">
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-info-circle text-muted me-2"></i>
                                                                Hướng dẫn sử dụng
                                                            </div>
                                                        </th>
                                                        <th class="border-0 px-4 py-3">
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-dollar-sign text-muted me-2"></i>
                                                                Giá
                                                            </div>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($prescription->prescriptionItems as $index => $item)
                                                        <tr class="border-bottom">
                                                            <td class="px-4 py-3">
                                                                <span class="badge bg-secondary">{{ $index + 1 }}</span>
                                                            </td>
                                                            <td class="px-4 py-3">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                                                        <i class="fa-solid fa-pill text-primary"></i>
                                                                    </div>
                                                                    <div>
                                                                        <h6 class="mb-0 fw-semibold">{{ $item->medicine->name }}</h6>
                                                                        <small class="text-muted">{{ $item->medicine->unit ?? 'Không xác định' }}</small>
                                                                        @if($item->medicine->dosage)
                                                                            <br><small class="text-info">Liều dùng: {{ $item->medicine->dosage }}</small>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="px-4 py-3 text-center">
                                                                <span class="badge bg-info fw-semibold">{{ $item->quantity }}</span>
                                                            </td>
                                                            <td class="px-4 py-3">
                                                                <div class="bg-light rounded p-2">
                                                                    <small class="text-muted">
                                                                        {{ $item->usage_instructions ?? 'Không có hướng dẫn' }}
                                                                    </small>
                                                                </div>
                                                            </td>
                                                            <td class="px-4 py-3">
                                                                <span class="fw-semibold text-success">
                                                                    {{ number_format($item->medicine->price ?? 0, 0, ',', '.') }}đ
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot class="table-light">
                                                    <tr>
                                                        <th colspan="4" class="px-4 py-3 text-end">Tổng giá trị đơn thuốc:</th>
                                                        <th class="px-4 py-3">
                                                            <span class="fw-bold text-success fs-5">
                                                                {{ number_format($prescription->prescriptionItems->sum(function($item) { return $item->quantity * ($item->medicine->price ?? 0); }), 0, ',', '.') }}đ
                                                            </span>
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-5">
                                            <div class="bg-light rounded-circle p-3 d-inline-flex mb-3">
                                                <i class="fas fa-pills text-muted fa-2x"></i>
                                            </div>
                                            <h6 class="text-muted mb-2">Không có thuốc nào trong đơn</h6>
                                            <p class="text-muted small mb-0">Đơn thuốc này chưa có thuốc nào được kê</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Ghi chú -->
                            <div class="card mt-4 border-0 bg-light">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="fas fa-sticky-note text-warning"></i>
                                        </div>
                                        <h5 class="mb-0 fw-bold">Ghi chú của đơn thuốc</h5>
                                    </div>
                                    <div class="bg-white rounded p-3">
                                        <p class="mb-0 text-muted">
                                            <i class="fas fa-quote-left me-2"></i>
                                            {{ $prescription->notes ?? 'Không có ghi chú đặc biệt' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Thông tin xóa -->
                            <div class="alert alert-warning mt-4 border-0 rounded-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle me-3"></i>
                                    <div>
                                        <h6 class="mb-1 fw-semibold">Đơn thuốc đã bị xóa</h6>
                                        <p class="mb-0 small">
                                            Đơn thuốc này đã được xóa khỏi hệ thống vào ngày {{ \Carbon\Carbon::parse($prescription->deleted_at)->format('d/m/Y H:i') }}. 
                                            Bạn có thể khôi phục để sử dụng lại.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                    <p class="text-muted mb-4">Bạn có chắc chắn muốn khôi phục đơn thuốc này?</p>
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
        let prescriptionId = null;
        const restoreModal = document.getElementById('restoreModal');

        // Mở modal khi bấm nút khôi phục
        document.getElementById('restorePrescriptionBtn').addEventListener('click', function() {
            prescriptionId = this.dataset.id;
            const modal = new bootstrap.Modal(restoreModal);
            modal.show();
        });

        // Xử lý xác nhận khôi phục
        document.getElementById('confirmRestoreBtn').addEventListener('click', function() {
            if (!prescriptionId) return;

            const btn = this;
            const originalText = btn.innerHTML;
            
            // Disable button và hiển thị loading
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Đang khôi phục...';
            
            const url = `{{ route('admin.prescriptions.restore', ':id') }}`.replace(':id', prescriptionId);

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
                    if (data.status === 'success' || data.message) {
                        bootstrap.Modal.getInstance(restoreModal).hide();
                        
                        // Hiển thị thông báo thành công
                        toastr.success(data.message || 'Khôi phục đơn thuốc thành công!', 'Thành công', {
                            progressBar: true,
                            timeOut: 2000,
                            positionClass: 'toast-top-right'
                        });
                        
                        // Chuyển về trang danh sách sau 1.5 giây
                        setTimeout(() => {
                            window.location.href = "{{ route('admin.prescriptions.trashed') }}";
                        }, 1500);
                    } else {
                        toastr.error(data.message || 'Có lỗi xảy ra khi khôi phục', 'Lỗi');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Không thể kết nối đến máy chủ. Vui lòng thử lại!', 'Lỗi');
                })
                .finally(() => {
                    // Khôi phục trạng thái button
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                });
        });

        // Reset modal khi đóng
        restoreModal.addEventListener('hidden.bs.modal', function() {
            prescriptionId = null;
            document.getElementById('confirmRestoreBtn').disabled = false;
            document.getElementById('confirmRestoreBtn').innerHTML = '<i class="fas fa-undo me-1"></i> Khôi phục';
        });
    </script>
@endpush

@push('styles')
    <style>
        .space-y-3 > * + * {
            margin-top: 1rem;
        }
        
        .card {
            border-radius: 1rem;
        }
        
        .card-header {
            border-radius: 1rem 1rem 0 0;
        }
        
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .modal-content {
            border-radius: 1rem;
        }
        
        .badge {
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .fw-semibold {
            font-weight: 600;
        }
        
        .bg-opacity-10 {
            background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
        }
        
        .shadow-sm {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        }
        
        .text-muted {
            color: #6c757d !important;
        }
        
        .alert {
            border-left: 4px solid #ffc107;
        }
        
        .table th {
            font-weight: 600;
            font-size: 0.875rem;
            color: #495057;
        }
        
        .overflow-hidden {
            overflow: hidden !important;
        }
        
        .table tfoot th {
            border-top: 2px solid #dee2e6;
            font-weight: 700;
        }
    </style>
@endpush