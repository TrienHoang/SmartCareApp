@extends('admin.dashboard')

@section('title', 'Quản lý Đánh giá')

@section('content')
    <div class="content-wrapper">
        <!-- Header Section -->
        <div class="content-header row">
            <div class="content-header-left col-md-8 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-circle bg-primary mr-3">
                                <i class="bx bx-star text-white"></i>
                            </div>
                            <div>
                                <h2 class="content-header-title mb-0 text-primary font-weight-bold">Quản lý Đánh giá</h2>
                                <p class="text-muted mb-0">Quản lý và theo dõi tất cả đánh giá trong hệ thống</p>
                            </div>
                        </div>
                        <div class="breadcrumb-wrapper col-12">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0">
                                    <li>
                                        <a href="{{ route('admin.dashboard.index') }}" class="text-decoration-none">
                                            Trang chủ >
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active text-primary font-weight-semibold">
                                        Đánh giá
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right col-md-4 col-12 text-md-right">
                <div class="form-group breadcrum-right">
                    {{-- <a href="#" class="btn btn-gradient-primary btn-lg waves-effect waves-light shadow-lg text-white">
                        Thêm đánh giá mới
                    </a> --}}
                </div>
            </div>
        </div>

        <div class="content-body">
            <!-- Alert Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-check-circle mr-2"></i>
                        <strong>Thành công! </strong> {{ session('success') }}
                    </div>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-x-circle mr-2"></i>
                        <strong>Lỗi! </strong> {{ session('error') }}
                    </div>
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
                                        <i class="bx bx-like font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $statusCounts['visible'] ?? 0 }}</h4>
                                    <small class="text-white">Hiển thị</small>
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
                                        <i class="bx bx-hide font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $statusCounts['hidden'] ?? 0 }}</h4>
                                    <small class="text-white">Đã ẩn</small>
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
                                        <i class="bx bx-star font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $statusCounts['total'] ?? 0 }}</h4>
                                    <small class="text-white">Tổng đánh giá</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Thêm card thống kê số lượng đánh giá theo số sao -->

            </div>
            <!-- Kết thúc thêm card -->
        </div>

        <!-- Main Content Card -->
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-gradient-primary text-white border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-list mr-2"></i>
                        <h4 class="card-title mb-0 text-white font-weight-bold">Danh sách Đánh giá</h4>
                    </div>
                    <div class="card-tools">
                        <span class="badge badge-light">{{ $reviews->total() }} đánh giá</span>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <!-- Filter Section -->
                <div class="filter-section bg-light p-4 border-bottom">
                    <form action="{{ route('admin.reviews.index') }}" method="GET" class="filter-form">
                        <div class="row align-items-end">
                            <div class="col-lg-3 col-md-6 mb-2">
                                <label class="form-label font-weight-semibold">
                                    <i class="bx bx-user mr-1 text-primary"></i>Tên bệnh nhân
                                </label>
                                <input type="text" name="patient_name" class="form-control border-left-0"
                                    placeholder="Nhập tên bệnh nhân..." value="{{ request('patient_name') }}">
                            </div>
                            <div class="col-lg-3 col-md-6 mb-2">
                                <label class="form-label font-weight-semibold">
                                    <i class="bx bx-user-voice mr-1 text-info"></i>Tên bác sĩ
                                </label>
                                <input type="text" name="doctor_name" class="form-control border-left-0"
                                    placeholder="Nhập tên bác sĩ..." value="{{ request('doctor_name') }}">
                            </div>
                            <div class="col-lg-2 col-md-4 mb-2">
                                <label class="form-label font-weight-semibold">
                                    <i class="bx bx-star mr-1 text-warning"></i>Số sao
                                </label>
                                <select name="rating" class="form-control custom-select">
                                    <option value="">Tất cả</option>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}"
                                            {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} sao
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-4 mb-2">
                                <label class="form-label font-weight-semibold">
                                    <i class="bx bx-show mr-1 text-success"></i>Trạng thái hiển thị
                                </label>
                                <select name="is_visible" class="form-control custom-select">
                                    <option value="">Tất cả</option>
                                    <option value="1" {{ request('is_visible') === '1' ? 'selected' : '' }}>Hiển
                                        thị</option>
                                    <option value="0" {{ request('is_visible') === '0' ? 'selected' : '' }}>Ẩn
                                    </option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-4 mb-2 d-flex align-items-end">
                                <div class="btn-group w-100" role="group">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bx bx-filter mr-1"></i>Lọc
                                    </button>
                                    <a href="{{ route('admin.reviews.index') }}"
                                        class="btn btn-outline-secondary btn-sm">
                                        <i class="bx bx-refresh-cw mr-1"></i>Reset
                                    </a>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover table-modern mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Bệnh nhân</th>
                                <th>Bác sĩ</th>
                                <th>Dịch vụ</th>
                                <th>Nội dung</th>
                                <th>Số sao</th>
                                <th>Hiển thị</th>
                                <th>Thời gian</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reviews as $review)
                                <tr>
                                    <td class="font-weight-bold text-primary">#{{ $review->id }}</td>
                                    <td>{{ $review->patient->full_name ?? '-' }}</td>
                                    <td>{{ $review->doctor->user->full_name ?? '-' }}</td>
                                    <td>{{ $review->service->name ?? '-' }}</td>
                                    <td>{{ Str::limit(strip_tags($review->content), 60) }}</td>
                                    <td>
                                        <span class="badge badge-warning">
                                            <i class="bx bx-star"></i> {{ $review->rating }}/5
                                        </span>
                                    </td>
                                    <td>
                                        @if ($review->is_visible)
                                            <span class="badge badge-success">Hiển thị</span>
                                        @else
                                            <span class="badge badge-secondary">Ẩn</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $review->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.reviews.show', $review) }}"
                                                class="btn btn-outline-info" data-toggle="tooltip" title="Xem chi tiết">
                                                <i class='bx bx-show-alt'></i>
                                            </a>
                                            <form action="{{ route('admin.reviews.toggle', $review) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-outline-{{ $review->is_visible ? 'secondary' : 'success' }}"
                                                    data-toggle="tooltip"
                                                    title="{{ $review->is_visible ? 'Ẩn' : 'Hiển thị' }}">
                                                    <i class="bx {{ $review->is_visible ? 'bx-hide' : 'bx-show' }}"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="bx bx-star-off text-muted" style="font-size: 48px;"></i>
                                            <h5 class="mt-3 text-muted">Không có đánh giá nào</h5>
                                            <p class="text-muted">Chưa có đánh giá nào được tạo hoặc không tìm thấy kết
                                                quả phù hợp.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($reviews->hasPages())
                    <div class="pagination-wrapper bg-light p-3 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="pagination-info">
                                <small class="text-muted">
                                    Hiển thị {{ $reviews->firstItem() }} - {{ $reviews->lastItem() }}
                                    trong tổng số {{ $reviews->total() }} kết quả
                                </small>
                            </div>
                            <div class="pagination-links">
                                {{ $reviews->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
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

        .badge-success {
            background-color: #39DA8A;
            color: #fff;
        }

        .badge-info {
            background-color: #00CFDD;
            color: #fff;
        }

        .badge-warning {
            background-color: #FDAC41;
            color: #212529;
        }

        .badge-danger {
            background-color: #FF5B5C;
            color: #fff;
        }

        .badge-secondary {
            background-color: #6c757d;
            color: #fff;
        }

        .badge-pill {
            border-radius: 10rem;
            padding: 0.25em 0.6em;
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

        .bg-gradient-success {
            background: linear-gradient(135deg, #00cec9 0%, #55a3ff 100%);
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #fdcb6e 0%, #e17055 100%);
        }

        .bg-gradient-danger {
            background: linear-gradient(135deg, #fd79a8 0%, #e84393 100%);
        }

        .table-modern {
            font-size: 0.9rem;
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

        .avatar {
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bg-rgba-white {
            background-color: rgba(255, 255, 255, 0.2);
        }

        @media (max-width: 768px) {
            .filter-form .row>div {
                margin-bottom: 1rem;
            }

            .btn-group-sm {
                flex-direction: column;
            }

            .btn-group-sm .btn {
                margin-bottom: 2px;
                margin-right: 0;
            }

            .filter-form .btn-group .btn {
                padding: 0.4rem 0.75rem;
                font-size: 0.85rem;
                line-height: 1.2;
            }

        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
    </script>
@endpush
