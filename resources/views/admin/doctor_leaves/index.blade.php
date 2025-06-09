@extends('admin.dashboard')
@section('title', 'Danh sách ngày nghỉ của bác sĩ')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 breadcrumb-wrapper mb-4">
            <span class="text-muted fw-light">Bác sĩ /</span> Ngày nghỉ
        </h4>

        <div class="card">
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                <h5 class="mb-0">Danh sách ngày nghỉ của bác sĩ</h5>
                <form action="{{ route('admin.doctor_leaves.index') }}" method="get" class="d-flex" style="max-width: 300px;">
                    <input type="text" name="keyword" class="form-control me-2" placeholder="Nhập từ khóa..."
                        value="{{ request('keyword') }}">
                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                </form>
            </div>

            {{-- Hiển thị thông báo --}}
            @if (session('success'))
                <div class="alert alert-success mx-3 mt-3">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger mx-3 mt-3">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Bảng danh sách --}}
            <div class="table-responsive text-nowrap">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr class="text-center">
                            <th>ID</th>
                            <th>Bác sĩ</th>
                            <th>Ngày nghỉ</th>
                            <th>Lý do</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($doctorLeaves as $leave)
                            <tr class="text-center">
                                <td>{{ $leave->id }}</td>
                                <td>{{ $leave->doctor->user->full_name ?? 'Không có' }}</td>
                                <td>{{ $leave->leave_date }}</td>
                                <td>{{ $leave->reason }}</td>
                                <td>
                                    @if($leave->approved)
                                        <span class="badge bg-success">Đã duyệt</span>
                                    @else
                                        <span class="badge bg-secondary">Chưa duyệt</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.doctor_leaves.edit', $leave->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                                </td>
                            </tr>
                        @empty
                            <tr class="text-center">
                                <td colspan="6">Không có ngày nghỉ nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Phân trang --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $doctorLeaves->links() }}
            </div>
        </div>
    </div>
@endsection
