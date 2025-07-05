@extends('admin.dashboard')
@section('title', 'Danh sách voucher')

@section('content')
<div class="content-wrapper">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
        <div class="d-flex align-items-center gap-3">
            <span class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                <i class="bx bx-gift fs-3"></i>
            </span>
            <h2 class="mb-0 fw-bold text-primary">🎁 Danh sách Voucher</h2>
        </div>
        <a href="{{ route('admin.vouchers.create') }}" class="btn btn-gradient-primary btn-lg d-flex align-items-center gap-2 shadow-sm text-white">
            <i class="bx bx-plus"></i> Thêm mới
        </a>
    </div>
    @if(session('message'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        <i class="bx bx-check-circle me-1"></i>
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
    </div>
@endif
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 col-12">
            <div class="card gradient-card bg-gradient-success">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="avatar bg-rgba-white mr-2">
                            <div class="avatar-content">
                                <i class="bx bx-gift font-medium-5"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-white mb-0">{{ $stats['total'] ?? $promotions->total() }}</h4>
                            <small class="text-white">Tổng voucher</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="card gradient-card bg-gradient-info">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="avatar bg-rgba-white mr-2">
                            <div class="avatar-content">
                                <i class="bx bx-check-circle font-medium-5"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-white mb-0">{{ $stats['active'] ?? $promotions->where('valid_until', '>', now())->count() }}</h4>
                            <small class="text-white">Còn hiệu lực</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="card gradient-card bg-gradient-warning">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="avatar bg-rgba-white mr-2">
                            <div class="avatar-content">
                                <i class="bx bx-timer font-medium-5"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-white mb-0">{{ $stats['used'] ?? 0 }}</h4>
                            <small class="text-white">Đã sử dụng</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="card gradient-card bg-gradient-danger">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="avatar bg-rgba-white mr-2">
                            <div class="avatar-content">
                                <i class="bx bx-block font-medium-5"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-white mb-0">{{ $stats['expired'] ?? $promotions->where('valid_until', '<', now())->count() }}</h4>
                            <small class="text-white">Hết hạn</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label font-weight-semibold"><i class="bx bx-search mr-1 text-primary"></i>Mã voucher</label>
                    <input type="text" name="code" class="form-control" placeholder="Tìm theo mã voucher..." value="{{ request('code') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label font-weight-semibold"><i class="bx bx-sort mr-1 text-info"></i>Phần trăm giảm giá</label>
                    <div class="row g-2">
                        <div class="col-6">
                            <input type="number" name="discount_percentage_min" class="form-control" placeholder="Tối thiểu" value="{{ request('discount_percentage_min') }}" min="1">
                        </div>
                        <div class="col-6">
                            <input type="number" name="discount_percentage_max" class="form-control" placeholder="Tối đa" value="{{ request('discount_percentage_max') }}" min="1">
                        </div>
                    </div>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-search"></i> Tìm kiếm
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card border-0 shadow-lg">
        <div class="card-header bg-gradient-primary text-white border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="bx bx-list mr-2"></i>
                    <h4 class="card-title mb-0 text-white font-weight-bold">Danh sách Voucher</h4>
                </div>
                <div class="card-tools">
                    <span class="badge badge-light">{{ $promotions->total() }} voucher</span>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-modern mb-0">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>ID</th>
                            <th>Mã</th>
                            <th>Giảm giá</th>
                            <th>Ngày bắt đầu</th>
                            <th>Ngày kết thúc</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($promotions as $promotion)
                            <tr class="align-middle text-center">
                                <td class="fw-bold text-primary">#{{ $promotion->id }}</td>
                                <td class="fw-bold text-primary">{{ $promotion->code }}</td>
                                <td>
                                    <span class="badge bg-success">{{ $promotion->discount_percentage }}%</span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($promotion->valid_from)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($promotion->valid_until)->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.vouchers.show', $promotion->id) }}" class="btn btn-outline-info" data-toggle="tooltip" title="Xem">
                                            <i class="bx bx-show-alt"></i>
                                        </a>
                                        <a href="{{ route('admin.vouchers.edit', $promotion->id) }}" class="btn btn-outline-warning" data-toggle="tooltip" title="Sửa">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                    </div>
                                    <form action="{{ route('admin.vouchers.destroy', $promotion->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" data-toggle="tooltip" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa voucher này không?')">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <div class="empty-state">
                                        <i class="bx bx-gift text-muted" style="font-size: 48px;"></i>
                                        <h5 class="mt-3 text-muted">Không tìm thấy voucher nào.</h5>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            @if ($promotions->hasPages())
                <div class="pagination-wrapper bg-light p-3 border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="pagination-info">
                            <small class="text-muted">
                                Hiển thị {{ $promotions->firstItem() }} - {{ $promotions->lastItem() }}
                                trong tổng số {{ $promotions->total() }} voucher
                            </small>
                        </div>
                        <div class="pagination-links">
                            {{ $promotions->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .icon-circle i {
        margin: 0px auto
    }
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .btn-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    .btn-gradient-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }
    .gradient-card {
        border: none;
        border-radius: 10px;
        transition: transform 0.3s ease;
    }
    .gradient-card:hover {
        transform: translateY(-2px);
    }
    .table-modern {
        font-size: 0.95rem;
    }
    .table-modern td {
        vertical-align: middle;
        padding: 1rem 0.75rem;
    }
    .empty-state {
        padding: 2rem;
    }
    .pagination-wrapper {
        background: linear-gradient(90deg, #f8f9fa 0%, #e9ecef 100%);
    }
    .btn-group-sm .btn {
        border-radius: 4px;
        margin-right: 2px;
    }
    .btn-group-sm .btn:last-child {
        margin-right: 0;
    }
    @media (max-width: 768px) {
        .btn-group-sm {
            flex-direction: column;
        }
        .btn-group-sm .btn {
            margin-bottom: 2px;
            margin-right: 0;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush
