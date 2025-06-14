@extends('admin.dashboard')
@section('title', 'Danh sách ngày nghỉ của bác sĩ')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    {{-- Breadcrumb --}}
    <div class="mb-4">
        <h4 class="fw-bold">
            <span class="text-muted fw-light">Quản lý bác sĩ /</span> Ngày nghỉ
        </h4>
    </div>

    {{-- Card chứa danh sách --}}
    <div class="card shadow-sm">
        <div class="card-body">

            {{-- Tiêu đề và tìm kiếm --}}
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
                <h5 class="card-title mb-2 mb-md-0">Danh sách ngày nghỉ của bác sĩ</h5>
                <form action="{{ route('admin.doctor_leaves.index') }}" method="GET" class="d-flex w-100 w-md-auto" style="max-width: 300px;">
                    <input type="text" name="keyword" class="form-control me-2" placeholder="Tìm bác sĩ..."
                        value="{{ request('keyword') }}">
                    <button class="btn btn-outline-primary" type="submit">Tìm</button>
                </form>
            </div>

            {{-- Thông báo flash --}}
            @foreach (['success', 'error', 'info'] as $msg)
                @if (session($msg))
                    <div class="alert alert-{{ $msg == 'error' ? 'danger' : $msg }} alert-dismissible fade show" role="alert">
                        {{ session($msg) }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            @endforeach

            {{-- Bảng danh sách --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tên bác sĩ</th>
                            <th class="text-center">Bắt đầu</th>
                            <th class="text-center">Kết thúc</th>
                            <th class="text-center d-none d-lg-table-cell">Ngày tạo</th>
                            <th class="d-none d-lg-table-cell">Lý do</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($doctorLeaves as $leave)
                            <tr>
                                <td>{{ $leave->doctor->user->full_name ?? 'Không rõ' }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($leave->start_date)->format('d/m/Y') }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($leave->end_date)->format('d/m/Y') }}</td>
                                <td class="text-center d-none d-lg-table-cell">
                                    {{ \Carbon\Carbon::parse($leave->created_at)->format('H:i d/m/Y') }}
                                </td>
                                <td class="d-none d-lg-table-cell text-truncate" style="max-width: 250px;">
                                    {{ $leave->reason ?? 'Không có lý do' }}
                                </td>
                                <td class="text-center">
                                    <span class="badge rounded-pill {{ $leave->approved ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ $leave->approved ? 'Đã duyệt' : 'Chưa duyệt' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.doctor_leaves.edit', $leave->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bx bx-edit-alt"></i> Sửa
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Không có dữ liệu ngày nghỉ nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Phân trang --}}
            <div class="mt-4 d-flex justify-content-center">
                {{ $doctorLeaves->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
