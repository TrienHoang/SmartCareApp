@extends('admin.dashboard')
@section('title', 'Quản lý Tài liệu y tế')

@push('styles')
<style>
    .stats-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        overflow: hidden;
        position: relative;
    }
    
    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(30px, -30px);
    }
    
    .stats-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }
    
    .stats-card.info {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    .stats-card.success {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }
    
    .stats-card.warning {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }
    
    .filter-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: none;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    .form-control, .form-select {
        border-radius: 12px;
        border: 2px solid #e9ecef;
        padding: 12px 16px;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .btn-search {
        background: linear-gradient(45deg, #667eea, #764ba2);
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-search:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    .files-table {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: none;
    }
    
    .table th {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: none;
        padding: 1.5rem 1rem;
        font-weight: 600;
        color: #495057;
    }
    
    .table td {
        padding: 1.25rem 1rem;
        border-top: 1px solid #f8f9fa;
        vertical-align: middle;
    }
    
    .table tbody tr {
        transition: all 0.3s ease;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fb;
        transform: scale(1.01);
    }
    
    .action-btn {
        border-radius: 8px;
        padding: 8px 12px;
        margin: 0 2px;
        border: none;
        font-size: 0.875rem;
        transition: all 0.3s ease;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
    }
    
    .btn-view {
        background: linear-gradient(45deg, #4facfe, #00f2fe);
        color: white;
    }
    
    .btn-download {
        background: linear-gradient(45deg, #43e97b, #38f9d7);
        color: white;
    }
    
    .btn-delete {
        background: linear-gradient(45deg, #fa709a, #fee140);
        color: white;
    }
    
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 20px;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: float 6s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }
    
    .upload-zone {
        border: 3px dashed #667eea;
        border-radius: 20px;
        padding: 3rem;
        text-align: center;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .upload-zone:hover {
        border-color: #764ba2;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        transform: translateY(-5px);
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .badge-category {
        background: linear-gradient(45deg, #667eea, #764ba2);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .user-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(45deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.875rem;
    }
    
    .file-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: linear-gradient(45deg, #4facfe, #00f2fe);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
    }
    
    .appointment-info {
        background: #f8f9fa;
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 0.875rem;
    }
    
    .pagination-modern {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-top: 2rem;
    }
</style>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2">
                        <i class="fas fa-file-medical me-3"></i>
                        Quản lý Tài liệu Y tế
                    </h1>
                    <p class="mb-0 opacity-75">Theo dõi và quản lý tất cả tài liệu y tế trong hệ thống</p>
                </div>
                <div>
                    <a href="{{ route('admin.files.trash') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-trash-alt me-2"></i>
                        Thùng rác
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-white-50 mb-2">Tổng số file</div>
                            <div class="h2 mb-0 fw-bold">{{ number_format($totalFiles) }}</div>
                            <div class="small mt-2">
                                <i class="fas fa-arrow-up me-1"></i>
                                <span>12% so với tháng trước</span>
                            </div>
                        </div>
                        <div class="text-white-50">
                            <i class="fas fa-file-medical fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card info p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-white-50 mb-2">Total Size</div>
                            <div class="h2 mb-0 fw-bold">{{ number_format($totalSize / 1024 / 1024, 1) }} GB</div>
                            <div class="small mt-2">
                                <i class="fas fa-database me-1"></i>
                                <span>Dung lượng sử dụng</span>
                            </div>
                        </div>
                        <div class="text-white-50">
                            <i class="fas fa-hdd fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card success p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-white-50 mb-2">Hôm nay</div>
                            <div class="h2 mb-0 fw-bold">{{ $files->where('uploaded_at', '>=', today())->count() }}</div>
                            <div class="small mt-2">
                                <i class="fas fa-plus me-1"></i>
                                <span>Files mới</span>
                            </div>
                        </div>
                        <div class="text-white-50">
                            <i class="fas fa-calendar-day fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card warning p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-white-50 mb-2">Tuần này</div>
                            <div class="h2 mb-0 fw-bold">{{ $files->where('uploaded_at', '>=', now()->startOfWeek())->count() }}</div>
                            <div class="small mt-2">
                                <i class="fas fa-chart-line me-1"></i>
                                <span>Tăng trưởng</span>
                            </div>
                        </div>
                        <div class="text-white-50">
                            <i class="fas fa-chart-bar fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Filters -->
        <div class="filter-card">
            <h5 class="mb-4">
                <i class="fas fa-filter me-2 text-primary"></i>
                Bộ lọc nâng cao
            </h5>
            
            <form method="GET" action="{{ route('admin.files.index') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label fw-semibold">Tìm kiếm</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" name="keyword" class="form-control border-start-0" 
                                   placeholder="Tên file, mô tả..." value="{{ request('keyword') }}">
                        </div>
                    </div>
                    
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label fw-semibold">Người tải lên</label>
                        <select name="uploader_type" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="doctor" {{ request('uploader_type') == 'doctor' ? 'selected' : '' }}>
                                👨‍⚕️ Bác sĩ
                            </option>
                            <option value="patient" {{ request('uploader_type') == 'patient' ? 'selected' : '' }}>
                                🏥 Bệnh nhân
                            </option>
                        </select>
                    </div>
                    
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label fw-semibold">Từ ngày</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label fw-semibold">Đến ngày</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label fw-semibold">Danh mục</label>
                        <select name="file_category" class="form-select">
                            <option value="">Tất cả danh mục</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}" {{ request('file_category') == $category ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('-', ' ', $category)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-lg-1 col-md-6 d-flex align-items-end">
                        <button type="submit" class="btn btn-search w-100">
                            <i class="fas fa-search me-2"></i>
                            Lọc
                        </button>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-12">
                        <a href="{{ route('admin.files.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-2"></i>
                            Xóa bộ lọc
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Files Table -->
        <div class="files-table">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="60">#</th>
                            <th width="300">Thông tin file</th>
                            <th width="200">Người tải lên</th>
                            <th width="150">Danh mục</th>
                            <th width="150">Thời gian</th>
                            <th width="200">Lịch hẹn</th>
                            <th width="200">Ghi chú</th>
                            <th width="150" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($files as $file)
                            <tr>
                                <td class="fw-semibold text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="file-icon me-3">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $file->file_name }}</div>
                                            <div class="small text-muted">
                                                <i class="fas fa-weight me-1"></i>
                                                {{ number_format($file->file_size / 1024, 1) }} KB
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar">
                                            {{ substr($file->user?->full_name ?? 'N', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $file->user?->full_name ?? 'Không xác định' }}</div>
                                            <div class="small text-muted">ID: {{ $file->user_id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($file->file_category)
                                        <span class="badge-category">{{ $file->file_category }}</span>
                                    @else
                                        <span class="text-muted">Không có</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $file->uploaded_at?->format('d/m/Y') ?? 'Chưa rõ' }}</div>
                                    <div class="small text-muted">{{ $file->uploaded_at?->format('H:i') ?? '' }}</div>
                                </td>
                                <td>
                                    <div class="appointment-info">
                                        <div class="fw-semibold">#{{ $file->appointment_id }}</div>
                                        @if ($file->appointment)
                                            <div class="small text-muted">
                                                {{ $file->appointment->appointment_time->format('d/m/Y H:i') }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 150px;" title="{{ $file->note }}">
                                        {{ $file->note ?? '-' }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.files.show', $file->id) }}" target="_blank"
                                           class="action-btn btn-view" title="Xem file">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.files.download', $file->id) }}" download
                                           class="action-btn btn-download" title="Tải xuống">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <button type="button" class="action-btn btn-delete" 
                                                onclick="deleteFile({{ $file->id }})" title="Xóa file">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <i class="fas fa-file-medical"></i>
                                        <h5 class="mt-3">Không có tài liệu nào</h5>
                                        <p>Chưa có tài liệu y tế nào được tải lên hệ thống.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if ($files->hasPages())
            <div class="pagination-modern">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="pagination-info">
                        <span class="text-muted">
                            Hiển thị <strong>{{ $files->firstItem() }}</strong> - <strong>{{ $files->lastItem() }}</strong>
                            trong tổng số <strong>{{ number_format($files->total()) }}</strong> kết quả
                        </span>
                    </div>
                    <div class="pagination-links">
                        {{ $files->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        Xác nhận xóa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa tài liệu này không? Hành động này không thể hoàn tác.</p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">
                        <i class="fas fa-trash-alt me-2"></i>
                        Xóa
                    </button>
                </div>
            </div>
        </div>
    </div>

    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
<script>
    let deleteFileId = null;
    
    function deleteFile(fileId) {
        deleteFileId = fileId;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
    
    document.getElementById('confirmDelete').addEventListener('click', function() {
        if (deleteFileId) {
            const form = document.getElementById('deleteForm');
            form.action = `{{ route('admin.files.destroy', ':id') }}`.replace(':id', deleteFileId);
            form.submit();
        }
    });
    
    // Auto-submit form on filter change
    document.querySelectorAll('#filterForm select').forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });
    
    // Enhanced table row hover effects
    document.querySelectorAll('.table tbody tr').forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f8f9fb';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
</script>
@endpush