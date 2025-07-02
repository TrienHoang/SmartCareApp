@extends('doctor.dashboard')

@section('title', 'Thùng rác File đã xóa')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="icon-shape bg-gradient-danger text-white rounded-circle me-3"
                                    style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #dc3545, #c82333);">
                                    <i class="fas fa-trash-alt fa-lg"></i>
                                </div>


                                <div>
                                    <h3 class="mb-0 text-dark">Thùng rác</h3>
                                    <p class="text-muted mb-0">Quản lý các file đã xóa</p>
                                </div>
                            </div>
                            <div class="text-end">
                                <a href="{{ route('doctor.files.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Alert -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle me-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Main Content -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="mb-0 text-dark">
                                <i class="fas fa-file-alt me-2"></i>File đã xóa
                            </h5>
                            @if (!$files->isEmpty())
                                <span class="badge bg-danger">{{ $files->total() }} file</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if ($files->isEmpty())
                            <!-- Empty State -->
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="fas fa-trash-alt text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                                </div>
                                <h4 class="text-muted mb-2">Thùng rác trống</h4>
                                <p class="text-muted mb-0">Không có file nào trong thùng rác.</p>
                                <a href="{{ route('doctor.files.index') }}" class="btn btn-primary mt-3">
                                    <i class="fas fa-plus me-2"></i>Quản lý file
                                </a>
                            </div>
                        @else
                            <!-- Files Table -->
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="border-0 text-dark fw-bold ps-4">
                                                <i class="fas fa-file me-2"></i>Tên File
                                            </th>
                                            <th class="border-0 text-dark fw-bold">
                                                <i class="fas fa-tag me-2"></i>Danh mục
                                            </th>
                                            <th class="border-0 text-dark fw-bold">
                                                <i class="fas fa-calendar me-2"></i>Ngày xóa
                                            </th>
                                            <th class="border-0 text-dark fw-bold text-center">
                                                <i class="fas fa-cogs me-2"></i>Hành động
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($files as $file)
                                            <tr class="align-middle">
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center">
                                                        <div class="file-icon me-3">
                                                            @php
                                                                $extension = pathinfo(
                                                                    $file->file_name,
                                                                    PATHINFO_EXTENSION,
                                                                );
                                                                $iconClass = match (strtolower($extension)) {
                                                                    'pdf' => 'fas fa-file-pdf text-danger',
                                                                    'doc', 'docx' => 'fas fa-file-word text-primary',
                                                                    'xls', 'xlsx' => 'fas fa-file-excel text-success',
                                                                    'jpg',
                                                                    'jpeg',
                                                                    'png',
                                                                    'gif'
                                                                        => 'fas fa-file-image text-info',
                                                                    default => 'fas fa-file text-muted',
                                                                };
                                                            @endphp
                                                            <i class="{{ $iconClass }} fa-lg"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 text-dark">{{ $file->file_name }}</h6>
                                                            <small
                                                                class="text-muted">{{ strtoupper($extension ?? 'FILE') }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary rounded-pill">
                                                        {{ $file->file_category }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div>
                                                        <span
                                                            class="text-dark">{{ \Carbon\Carbon::parse($file->deleted_at)->format('d/m/Y') }}</span>
                                                        <br>
                                                        <small
                                                            class="text-muted">{{ \Carbon\Carbon::parse($file->deleted_at)->format('H:i') }}</small>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        <!-- Restore Button -->
                                                        <form action="{{ route('doctor.files.restore', $file->id) }}"
                                                            method="POST" class="d-inline restore-form">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="button" class="btn btn-success btn-sm me-2"
                                                                title="Khôi phục file" data-bs-toggle="modal"
                                                                data-bs-target="#restoreModal"
                                                                data-file-name="{{ $file->file_name }}"
                                                                data-file-id="{{ $file->id }}">
                                                                <i class="fas fa-undo me-1"></i>Khôi phục
                                                            </button>
                                                        </form>

                                                        <!-- Force Delete Button -->
                                                        <form action="{{ route('doctor.files.forceDelete', $file->id) }}"
                                                            method="POST" class="d-inline delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-danger btn-sm"
                                                                title="Xóa vĩnh viễn" data-bs-toggle="modal"
                                                                data-bs-target="#deleteModal"
                                                                data-file-name="{{ $file->file_name }}"
                                                                data-file-id="{{ $file->id }}">
                                                                <i class="fas fa-trash me-1"></i>Xóa vĩnh viễn
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            @if ($files->hasPages())
                                <div class="card-footer bg-white border-top-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="text-muted">
                                            Hiển thị {{ $files->firstItem() }}-{{ $files->lastItem() }}
                                            trong tổng số {{ $files->total() }} file
                                        </div>
                                        <div>
                                            {{ $files->withQueryString()->links('pagination::bootstrap-5') }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Actions (if needed) -->
        @if (!$files->isEmpty())
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-0 text-dark">
                                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                        Lưu ý quan trọng
                                    </h6>
                                    <small class="text-muted">
                                        Các file trong thùng rác sẽ được tự động xóa vĩnh viễn sau 30 ngày
                                    </small>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#emptyTrashModal" title="Xóa tất cả file trong thùng rác">
                                        <i class="fas fa-trash-alt me-2"></i>Làm trống thùng rác
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Custom Styles -->
    <style>
        .card {
            transition: all 0.3s ease;
            border-radius: 12px;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .table tbody tr {
            transition: background-color 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .btn {
            transition: all 0.2s ease;
            border-radius: 8px;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .file-icon {
            width: 40px;
            text-align: center;
        }

        .alert {
            border: none;
            border-radius: 10px;
        }

        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            border-radius: 15px 15px 0 0;
        }

        .modal-footer {
            border-radius: 0 0 15px 15px;
        }

        .icon-circle {
            transition: all 0.3s ease;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <!-- Custom JavaScript -->
    <script>
        // Global variables for modals
        let currentRestoreForm = null;
        let currentDeleteForm = null;
        let isProcessing = false;

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize modals
            const restoreModal = document.getElementById('restoreModal');
            const deleteModal = document.getElementById('deleteModal');
            const emptyTrashModal = document.getElementById('emptyTrashModal');

            const checkboxes = [
                document.getElementById('confirmEmptyCheck1'),
                document.getElementById('confirmEmptyCheck2'),
                document.getElementById('confirmEmptyCheck3')
            ];
            const deleteBtn = document.getElementById('confirmEmptyTrash');

            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    const allChecked = checkboxes.every(c => c.checked);
                    deleteBtn.disabled = !allChecked;
                });
            });

            // Restore Modal Handler
            if (restoreModal) {
                restoreModal.addEventListener('show.bs.modal', function(event) {
                    if (isProcessing) return;

                    const button = event.relatedTarget;
                    const fileName = button.getAttribute('data-file-name');

                    document.getElementById('restoreFileName').textContent = fileName;
                    currentRestoreForm = button.closest('form');
                });

                // Confirm restore button
                const confirmRestoreBtn = document.getElementById('confirmRestore');
                if (confirmRestoreBtn) {
                    confirmRestoreBtn.addEventListener('click', function() {
                        if (currentRestoreForm && !isProcessing) {
                            isProcessing = true;
                            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang khôi phục...';
                            this.disabled = true;
                            currentRestoreForm.submit();
                        }
                    });
                }
            }

            // Delete Modal Handler
            if (deleteModal) {
                deleteModal.addEventListener('show.bs.modal', function(event) {
                    if (isProcessing) return;

                    const button = event.relatedTarget;
                    const fileName = button.getAttribute('data-file-name');

                    document.getElementById('deleteFileName').textContent = fileName;
                    currentDeleteForm = button.closest('form');

                    // Reset states
                    const checkbox = document.getElementById('confirmDeleteCheck');
                    const confirmBtn = document.getElementById('confirmDelete');

                    if (checkbox) checkbox.checked = false;
                    if (confirmBtn) confirmBtn.disabled = true;
                });

                // Delete confirmation checkbox
                const deleteCheckbox = document.getElementById('confirmDeleteCheck');
                if (deleteCheckbox) {
                    deleteCheckbox.addEventListener('change', function() {
                        const confirmBtn = document.getElementById('confirmDelete');
                        if (confirmBtn) {
                            confirmBtn.disabled = !this.checked;
                        }
                    });
                }

                // Confirm delete button
                const confirmDeleteBtn = document.getElementById('confirmDelete');
                if (confirmDeleteBtn) {
                    confirmDeleteBtn.addEventListener('click', function() {
                        const checkbox = document.getElementById('confirmDeleteCheck');
                        if (currentDeleteForm && checkbox && checkbox.checked && !isProcessing) {
                            isProcessing = true;
                            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang xóa...';
                            this.disabled = true;
                            currentDeleteForm.submit();
                        }
                    });
                }
            }

            // Empty Trash Modal Handler
            if (emptyTrashModal) {
                const emptyCheckboxes = ['confirmEmptyCheck1', 'confirmEmptyCheck2', 'confirmEmptyCheck3'];
                const confirmEmptyBtn = document.getElementById('confirmEmptyTrash');

                // Handle checkboxes
                emptyCheckboxes.forEach(checkboxId => {
                    const checkbox = document.getElementById(checkboxId);
                    if (checkbox) {
                        checkbox.addEventListener('change', function() {
                            if (!confirmEmptyBtn) return;

                            const allChecked = emptyCheckboxes.every(id => {
                                const cb = document.getElementById(id);
                                return cb && cb.checked;
                            });
                            confirmEmptyBtn.disabled = !allChecked;
                        });
                    }
                });

                // Confirm empty trash button
                if (confirmEmptyBtn) {
                    confirmEmptyBtn.addEventListener('click', function() {
                        const allChecked = emptyCheckboxes.every(id => {
                            const cb = document.getElementById(id);
                            return cb && cb.checked;
                        });

                        if (allChecked && !isProcessing) {
                            isProcessing = true;
                            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang xóa...';
                            this.disabled = true;

                            const form = document.getElementById('forceDeleteAllForm');
                            if (form) {
                                form.submit();
                            }
                        }
                    });
                }
            }

            // Reset modal states when hidden
            [restoreModal, deleteModal, emptyTrashModal].forEach(modal => {
                if (modal) {
                    modal.addEventListener('hidden.bs.modal', function() {
                        // Reset processing state
                        isProcessing = false;

                        // Reset forms
                        currentRestoreForm = null;
                        currentDeleteForm = null;

                        // Reset all checkboxes in this modal
                        const checkboxes = this.querySelectorAll('input[type="checkbox"]');
                        checkboxes.forEach(cb => cb.checked = false);

                        // Reset all buttons in this modal
                        const buttons = this.querySelectorAll('button');
                        buttons.forEach(btn => {
                            if (btn.classList.contains('btn-success')) {
                                btn.innerHTML =
                                    '<i class="fas fa-undo me-2"></i>Khôi phục ngay';
                                btn.disabled = false;
                            } else if (btn.classList.contains('btn-danger')) {
                                if (btn.id === 'confirmDelete') {
                                    btn.innerHTML =
                                        '<i class="fas fa-trash-alt me-2"></i>Xóa vĩnh viễn';
                                    btn.disabled = true;
                                } else if (btn.id === 'confirmEmptyTrash') {
                                    btn.innerHTML =
                                        '<i class="fas fa-trash-alt me-2"></i>Xóa tất cả vĩnh viễn';
                                    btn.disabled = true;
                                }
                            }
                        });
                    });
                }
            });

            // Auto-hide alerts
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    if (alert && alert.classList.contains('show')) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                }, 5000);
            });
        });

        // Prevent multiple clicks during processing
        document.addEventListener('click', function(e) {
            if (isProcessing && (e.target.matches('[data-bs-toggle="modal"]') || e.target.closest(
                    '[data-bs-toggle="modal"]'))) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    </script>
@endsection

@push('modals')
    <!-- Restore Confirmation Modal -->
    <div class="modal fade" id="restoreModal" tabindex="-1" aria-labelledby="restoreModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-success text-white border-0">
                    <h5 class="modal-title" id="restoreModalLabel">
                        <i class="fas fa-undo me-2"></i>Xác nhận khôi phục
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <div class="icon-circle bg-opacity-10 mx-auto mb-3"
                            style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                            <i class="fas fa-undo text-success" style="font-size: 2rem;"></i>
                        </div>
                        <h5 class="text-dark mb-2">Khôi phục file</h5>
                        <p class="text-muted mb-0">
                            Bạn có chắc chắn muốn khôi phục file
                            <strong class="text-dark" id="restoreFileName"></strong>
                            về danh sách chính không?
                        </p>
                    </div>
                    <div class="alert alert-info border-0" style="background-color: #e3f2fd;">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle text-info me-2"></i>
                            <small class="mb-0">File sẽ được khôi phục về vị trí ban đầu và có
                                thể được truy cập bình thường.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Hủy bỏ
                    </button>
                    <button type="button" class="btn btn-success" id="confirmRestore">
                        <i class="fas fa-undo me-2"></i>Khôi phục ngay
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger text-white border-0">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Xác nhận xóa vĩnh viễn
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <div class="icon-circle bg-opacity-10 mx-auto mb-3"
                            style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                            <i class="fas fa-trash-alt text-danger" style="font-size: 2rem;"></i>
                        </div>
                        <h5 class="text-dark mb-2">Xóa vĩnh viễn</h5>
                        <p class="text-muted mb-0">
                            Bạn có chắc chắn muốn xóa vĩnh viễn file
                            <strong class="text-danger" id="deleteFileName"></strong> không?
                        </p>
                    </div>
                    <div class="alert alert-danger border-0" style="background-color: #ffebee;">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-exclamation-triangle text-danger me-2 mt-1"></i>
                            <div>
                                <strong class="text-danger">CẢNH BÁO:</strong>
                                <ul class="mb-0 mt-1 ps-3">
                                    <li><small>File sẽ bị xóa hoàn toàn khỏi hệ thống</small>
                                    </li>
                                    <li><small>Hành động này <strong>KHÔNG THỂ HOÀN
                                                TÁC</strong></small></li>
                                    <li><small>Tất cả dữ liệu liên quan sẽ bị mất vĩnh
                                            viễn</small></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" id="confirmDeleteCheck">
                        <label class="form-check-label text-muted" for="confirmDeleteCheck">
                            <small>Tôi hiểu rằng hành động này không thể hoàn tác</small>
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Hủy bỏ
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDelete" disabled>
                        <i class="fas fa-trash-alt me-2"></i>Xóa vĩnh viễn
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Empty Trash Confirmation Modal -->
    <div class="modal fade" id="emptyTrashModal" tabindex="-1" aria-labelledby="emptyTrashModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-warning text-dark border-0">
                    <h5 class="modal-title" id="emptyTrashModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Làm trống thùng rác
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="icon-circle bg-opacity-10 mx-auto mb-3"
                            style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                            <i class="fas fa-trash-alt text-warning" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="text-dark mb-2">Xóa tất cả file trong thùng rác</h4>
                        <p class="text-muted">
                            Bạn sắp xóa vĩnh viễn <strong class="text-danger">{{ $files->total() }} file</strong>
                            có trong thùng rác.
                        </p>
                    </div>

                    <div class="alert alert-danger border-0 mb-4" style="background-color: #ffebee;">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-skull-crossbones text-danger me-3 mt-1" style="font-size: 1.5rem;"></i>
                            <div>
                                <h6 class="text-danger mb-2">⚠️ NGUY HIỂM - ĐỌC KỸ TRƯỚC KHI
                                    TIẾP TỤC</h6>
                                <ul class="mb-0 text-danger">
                                    <li><strong>TẤT CẢ {{ $files->total() }} file sẽ bị xóa vĩnh
                                            viễn</strong></li>
                                    <li><strong>Không thể khôi phục sau khi xóa</strong></li>
                                    <li><strong>Tất cả dữ liệu sẽ mất hoàn toàn</strong></li>
                                    <li><strong>Hành động này ảnh hưởng đến toàn bộ thùng
                                            rác</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <i class="fas fa-lightbulb text-success mb-2" style="font-size: 2rem;"></i>
                                    <h6 class="text-success">Gợi ý thay thế</h6>
                                    <p class="small text-muted mb-0">
                                        Hãy xem xét khôi phục những file quan trọng trước khi
                                        xóa
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-info">
                                <div class="card-body text-center">
                                    <i class="fas fa-clock text-info mb-2" style="font-size: 2rem;"></i>
                                    <h6 class="text-info">Tự động xóa</h6>
                                    <p class="small text-muted mb-0">
                                        File sẽ tự động xóa sau 30 ngày nên bạn có thể chờ
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="confirmEmptyCheck1">
                            <label class="form-check-label text-muted" for="confirmEmptyCheck1">
                                <small>Tôi đã đọc và hiểu các cảnh báo ở trên</small>
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="confirmEmptyCheck2">
                            <label class="form-check-label text-muted" for="confirmEmptyCheck2">
                                <small>Tôi xác nhận muốn xóa vĩnh viễn TẤT CẢ file trong thùng
                                    rác</small>
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="confirmEmptyCheck3">
                            <label class="form-check-label text-muted" for="confirmEmptyCheck3">
                                <small>Tôi hiểu rằng hành động này KHÔNG THỂ HOÀN TÁC</small>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Hủy bỏ
                    </button>
                    <form action="{{ route('doctor.files.forceDelete', 'all') }}" method="POST" id="forceDeleteAllForm">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" id="confirmEmptyTrash" disabled>
                            <i class="fas fa-trash-alt me-2"></i>Xóa tất cả vĩnh viễn
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endpush
