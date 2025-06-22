@extends('admin.dashboard')
@section('title', 'Quản lý lịch làm việc bác sĩ')

@section('content')
<div class="container-fluid py-4">

    <!-- Card tiêu đề + tìm kiếm + thêm mới -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white">
            <div class="row align-items-center">
                <!-- Bên trái: Tiêu đề -->
                <div class="col-md-6">
                    <h5 class="mb-0">
                        <i data-feather="calendar" class="me-2"></i> Danh sách lịch làm việc
                    </h5>
                </div>
                <!-- Bên phải: Form tìm kiếm + Thêm lịch -->
                <div class="col-md-6">
                    <form action="{{ route('admin.schedules.index') }}" method="get" class="d-flex justify-content-end align-items-center gap-2">
                        <input type="text" name="keyword" class="form-control form-control-sm w-50" placeholder="Tìm theo tên bác sĩ..." value="{{ $keyword ?? '' }}">
                        <button type="submit" class="btn btn-sm btn-outline-primary">
                            <i data-feather="search"></i> Tìm
                        </button>
                        <a href="{{ route('admin.schedules.create') }}" class="btn btn-sm btn-primary">
                            <i data-feather="plus-circle"></i> Thêm lịch
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng dữ liệu -->
    <div class="card shadow-sm">
        <div class="card-body">
            @if($schedules->isEmpty())
                <div class="alert alert-info text-center">
                    <i data-feather="info"></i> Không có lịch làm việc nào.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th>#</th>
                                <th>Bác sĩ</th>
                                <th>Ngày</th>
                                <th>Thứ</th>
                                <th>Giờ bắt đầu</th>
                                <th>Giờ kết thúc</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schedules as $schedule)
                                <tr>
                                    <td>{{ $schedule->id }}</td>
                                    <td>{{ $schedule->doctor->user->full_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($schedule->day)->format('d/m/Y') }}</td>
                                    <td>{{ $schedule->day_of_week }}</td>
                                    <td>{{ $schedule->start_time }}</td>
                                    <td>{{ $schedule->end_time }}</td>
                                     <td>
                                <a href="{{ route('admin.schedules.show', $schedule->id) }}" class="btn btn-sm btn-info" title="Chi tiết">
                                    <i class="bx bx-show"></i>
                                </a>
                                <a href="{{ route('admin.schedules.edit', $schedule->id) }}" class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa lịch làm việc này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Phân trang -->
                <div class="d-flex justify-content-end mt-3">
                    {{ $schedules->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/feather-icons"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        feather.replace();

        // Khởi tạo tooltip
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (el) {
            new bootstrap.Tooltip(el);
        });
    });
</script>
@endsection
