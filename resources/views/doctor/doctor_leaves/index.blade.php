@extends('doctor.dashboard')

@section('title', 'Lịch nghỉ của tôi')

@section('content')
<div class="container-fluid py-4 animate__animated animate__fadeIn">
    {{-- Header + Breadcrumb --}}
    <div class="content-header row">
        <div class="content-header-left col-md-8 col-12 mb-2">
            <div class="d-flex align-items-center mb-3">
                <div class="icon-circle bg-warning me-3">
                    <i class="bx bx-calendar text-white"></i>
                </div>
                <div>
                    <h2 class="content-header-title mb-0 text-warning fw-bold">Lịch nghỉ của tôi</h2>
                    <p class="text-muted mb-0">Quản lý và theo dõi lịch nghỉ phép cá nhân</p>
                </div>
            </div>
        </div>
        <div class="content-header-right col-md-4 col-12 text-md-end">
            <a href="{{ route('doctor.leaves.create') }}"
               class="btn btn-success btn-lg waves-effect waves-light shadow-lg text-white">
                <i class="bx bx-plus me-1"></i> Đăng ký lịch nghỉ mới
            </a>
        </div>
    </div>

    {{-- Thông báo --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Danh sách lịch nghỉ --}}
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bx bx-list-ul me-2"></i> Danh sách lịch nghỉ</h5>
        </div>

        <div class="card-body p-0">
            @if ($leaves->isEmpty())
                <div class="alert alert-info m-4">Bạn chưa đăng ký lịch nghỉ nào.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped align-middle text-center mb-0">
                        <thead class="table-light text-dark">
                            <tr>
                                <th>#</th>
                                <th>Ngày bắt đầu</th>
                                <th>Ngày kết thúc</th>
                                <th>Lý do</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaves as $index => $leave)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($leave->end_date)->format('d/m/Y') }}</td>
                                    <td class="text-start px-3">{{ \Illuminate\Support\Str::limit($leave->reason, 80) }}</td>
                                    <td>
                                        @if ($leave->approved)
                                            <span class="badge bg-success">Đã duyệt</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Chưa duyệt</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('doctor.leaves.show', $leave->id) }}"
                                               class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Xem chi tiết">
                                                <i class="bx bx-show"></i>
                                            </a>
                                            @if (!$leave->approved)
                                                <a href="{{ route('doctor.leaves.edit', $leave->id) }}"
                                                   class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Chỉnh sửa">
                                                    <i class="bx bx-edit-alt"></i>
                                                </a>
                                                <form action="{{ route('doctor.leaves.destroy', $leave->id) }}" method="POST"
                                                      style="display: inline-block;"
                                                      onsubmit="return confirm('Bạn có chắc chắn muốn xóa lịch nghỉ này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Xóa">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Pagination nếu cần --}}
        @if(method_exists($leaves, 'links'))
            <div class="card-footer d-flex justify-content-end">
                {{ $leaves->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Tooltip Bootstrap 5
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endpush
