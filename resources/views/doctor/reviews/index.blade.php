@extends('doctor.dashboard')



@section('content')
    <div class="container mt-4">
        <div class="mb-4">
            <h2 class="text-primary font-weight-bold">Đánh giá từ bệnh nhân</h2>
            <p class="text-muted">Xem và quản lý đánh giá liên quan đến bạn.</p>
        </div>

        <!-- Alert -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <strong>Thành công!</strong> {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <strong>Lỗi!</strong> {{ session('error') }}
            </div>
        @endif

        <!-- Filter -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body bg-light">
                <form action="{{ route('doctor.reviews.index') }}" method="GET">
                    <div class="row align-items-end">
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-primary font-weight-semibold">
                                <i class="bx bx-user mr-1"></i> Tên bệnh nhân
                            </label>
                            <input type="text" name="patient_name" class="form-control border-left-primary"
                                value="{{ request('patient_name') }}" placeholder="Nhập tên bệnh nhân...">
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label text-warning font-weight-semibold">
                                <i class="bx bx-star mr-1"></i> Số sao
                            </label>
                            <select name="rating" class="form-control custom-select">
                                <option value="">Tất cả</option>
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                        {{ $i }} sao</option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label text-success font-weight-semibold">
                                <i class="bx bx-show mr-1"></i> Trạng thái
                            </label>
                            <select name="is_visible" class="form-control custom-select">
                                <option value="">Tất cả</option>
                                <option value="1" {{ request('is_visible') === '1' ? 'selected' : '' }}>Hiển thị
                                </option>
                                <option value="0" {{ request('is_visible') === '0' ? 'selected' : '' }}>Đã ẩn</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary w-100 mr-2">
                                <i class="bx bx-filter mr-1"></i> Lọc
                            </button>
                            <a href="{{ route('doctor.reviews.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="bx bx-refresh mr-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table -->
        @if ($reviews->isEmpty())
            <div class="alert alert-info shadow-sm">Chưa có đánh giá nào.</div>
        @else
            <div class="table-responsive shadow-sm rounded">
                <table class="table table-hover table-bordered mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Bệnh nhân</th>
                            <th>Dịch vụ</th>
                            <th>Số sao</th>
                            <th>Bình luận</th>
                            <th>Thời gian</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reviews as $review)
                            <tr>
                                <td class="font-weight-bold text-primary">#{{ $review->id }}</td>
                                <td>{{ $review->patient->full_name ?? '---' }}</td>
                                <td>{{ $review->service->name ?? '---' }}</td>
                                <td>
                                    <span class="badge badge-warning">
                                        <i class="bx bx-star"></i> {{ $review->rating }}/5
                                    </span>
                                </td>
                                <td>{{ Str::limit($review->comment, 60) }}</td>
                                <td><small class="text-muted">{{ $review->created_at->format('d/m/Y H:i') }}</small></td>
                                <td>
                                    @if ($review->is_visible)
                                        <span class="badge badge-success">Hiển thị</span>
                                    @else
                                        <span class="badge badge-secondary">Đã ẩn</span>
                                    @endif
                                </td>
                                <td class="text-center">

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3 d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">
                        Hiển thị {{ $reviews->firstItem() }} - {{ $reviews->lastItem() }} trên {{ $reviews->total() }}
                        đánh giá
                    </small>
                </div>
                <div>
                    {{ $reviews->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        .badge-success {
            background-color: #39DA8A;
            color: #fff;
        }

        .badge-warning {
            background-color: #FDAC41;
            color: #212529;
        }

        .badge-secondary {
            background-color: #6c757d;
            color: #fff;
        }

        .badge {
            font-size: 0.85rem;
            padding: 0.4em 0.7em;
            border-radius: 10rem;
        }

        .form-label {
            font-weight: 600;
        }

        .border-left-primary {
            border-left: 3px solid #7367f0 !important;
        }

        .gap-2 {
            gap: 0.5rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(function() {
            $('[data-toggle="tooltip"]').tooltip();
            setTimeout(() => $('.alert').fadeOut('slow'), 4000);
        });
    </script>
@endpush
