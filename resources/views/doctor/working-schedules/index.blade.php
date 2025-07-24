@extends('doctor.dashboard')

@section('title', 'Danh sách lịch làm việc')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Danh sách lịch làm việc</h2>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Lỗi!</strong><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <a href="{{ route('doctor.working_schedules.create') }}" class="btn btn-success mb-3">Thêm lịch làm việc mới</a>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead class="table-primary">
                <tr>
                    <th>STT</th>
                    <th>Ngày</th>
                    <th>Thứ</th>
                    <th>Ca làm</th>
                    <th>Giờ bắt đầu</th>
                    <th>Giờ kết thúc</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($workingSchedules as $index => $schedule)
                    <tr>
                        <td>{{ $workingSchedules->firstItem() + $index }}</td>
                        <td>{{ $schedule->day ? \Carbon\Carbon::parse($schedule->day)->format('d/m/Y') : 'Chưa có ngày' }}</td>
                        <td>{{ $schedule->day ? \Carbon\Carbon::parse($schedule->day)->locale('vi')->translatedFormat('l') : '' }}</td>
                        <td>{{ $schedule->shift?->name ?? 'Chưa xác định' }}</td>
                        <td>{{ $schedule->shift?->start_time ? \Carbon\Carbon::parse($schedule->shift->start_time)->format('H:i') : 'Chưa có' }}</td>
                        <td>{{ $schedule->shift?->end_time ? \Carbon\Carbon::parse($schedule->shift->end_time)->format('H:i') : 'Chưa có' }}</td>
                        <td>{{ $schedule->status }}</td>
                        <td>
                            @if ($schedule->status === 'Chờ xét duyệt')
                                <a href="{{ route('doctor.working_schedules.edit', $schedule->id) }}" class="btn btn-warning btn-sm">Sửa</a>

                                <form action="{{ route('doctor.working_schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                </form>
                            @else
                                <span class="text-muted">Không khả dụng</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Phân trang --}}
        <div class="mt-3">
            {{ $workingSchedules->links() }}
        </div>
    </div>
</div>
@endsection
