@extends('admin.dashboard')

@section('content')
<div class="container-fluid py-4">
    <!-- Back Button -->


    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h1 class="h3 mb-2 text-dark fw-bold">{{ $post->title }}</h1>
                            <div class="d-flex align-items-center gap-3 text-muted">
                                <small class="d-flex align-items-center">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $post->created_at->format('d/m/Y') }}
                                </small>
                                <small class="d-flex align-items-center">
                                    <i class="fas fa-folder me-1"></i>
                                    {{ $post->serviceCategory->name ?? '(Không có)' }}
                                </small>
                            </div>
                        </div>
                        <div class="text-end">
                            @switch($post->status)
                                @case('published') 
                                    <span class="badge bg-success fs-6 px-3 py-2">
                                        <i class="fas fa-check-circle me-1"></i>
                                        Xuất bản
                                    </span> 
                                @break
                                @case('draft') 
                                    <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                                        <i class="fas fa-edit me-1"></i>
                                        Nháp
                                    </span> 
                                @break
                                @case('archived') 
                                    <span class="badge bg-secondary fs-6 px-3 py-2">
                                        <i class="fas fa-archive me-1"></i>
                                        Lưu trữ
                                    </span> 
                                @break
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Thumbnail Section -->
            @if($post->thumbnail)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-0">
                    <div class="position-relative overflow-hidden rounded">
                        <img src="{{ asset('storage/' . $post->thumbnail) }}" 
                             class="img-fluid w-100" 
                             style="max-height: 400px; object-fit: cover;"
                             alt="{{ $post->title }}">
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient-to-bottom from-transparent to-dark opacity-20"></div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Excerpt Section -->
            @if($post->excerpt)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2">
                                <i class="fas fa-quote-left"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="card-title text-muted mb-2 fw-semibold">Mô tả ngắn</h5>
                            <p class="card-text text-dark fs-6 lh-lg mb-0">{{ $post->excerpt }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Content Section -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3">
                    <h5 class="mb-0 fw-semibold text-dark d-flex align-items-center">
                        <i class="fas fa-file-alt text-primary me-2"></i>
                        Nội dung bài viết
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="post-content">
                        {!! $post->content !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Post Info Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="mb-0 fw-semibold text-dark">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Thông tin bài viết
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar text-muted me-2"></i>
                                <span class="text-muted">Ngày tạo</span>
                            </div>
                            <span class="fw-semibold">{{ $post->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-folder text-muted me-2"></i>
                                <span class="text-muted">Danh mục</span>
                            </div>
                            <span class="fw-semibold">{{ $post->serviceCategory->name ?? '(Không có)' }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-eye text-muted me-2"></i>
                                <span class="text-muted">Trạng thái</span>
                            </div>
                            @switch($post->status)
                                @case('published') 
                                    <span class="badge bg-success">Xuất bản</span> 
                                @break
                                @case('draft') 
                                    <span class="badge bg-warning text-dark">Nháp</span> 
                                @break
                                @case('archived') 
                                    <span class="badge bg-secondary">Lưu trữ</span> 
                                @break
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="mb-0 fw-semibold text-dark">
                        <i class="fas fa-tools text-primary me-2"></i>
                        Thao tác nhanh
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-edit me-2"></i>Chỉnh sửa
                        </a>
                        <a href="#" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-copy me-2"></i>Sao chép
                        </a>
                        <a href="#" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-trash me-2"></i>Xóa
                        </a>
                            <div class="row mb-3">
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm ">
                <i class="fas fa-arrow-left me-2"></i>
                Quay lại
            </a>
        </div>
    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.post-content {
    line-height: 1.8;
}

.post-content h1, .post-content h2, .post-content h3 {
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.post-content p {
    margin-bottom: 1.5rem;
}

.post-content img {
    max-width: 100%;
    height: auto;
    border-radius: 0.375rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.bg-gradient-to-bottom {
    background: linear-gradient(to bottom, transparent, rgba(0, 0, 0, 0.3));
}

.card {
    transition: all 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.badge {
    font-weight: 500;
}

.list-group-item {
    border-left: none;
    border-right: none;
}

.list-group-item:first-child {
    border-top: none;
}

.list-group-item:last-child {
    border-bottom: none;
}
</style>
@endsection