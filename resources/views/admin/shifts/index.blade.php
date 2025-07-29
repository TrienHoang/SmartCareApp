@extends('admin.dashboard')

@section('title', 'Danh sách ca làm việc')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Danh sách ca làm việc</h2>

    <a href="{{ route('admin.shifts.create') }}" class="btn btn-success mb-3">+ Thêm ca làm mới</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Tên ca</th>
                <th>Giờ bắt đầu</th>
                <th>Giờ kết thúc</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($shifts as $index => $shift)
                <tr>
                    <td>{{ $shifts->firstItem() + $index }}</td>
                    <td>{{ $shift->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.shifts.edit', $shift->id) }}" class="btn btn-sm btn-warning">Sửa</a>

                        <form action="{{ route('admin.shifts.destroy', $shift->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Bạn có chắc muốn xoá ca này không?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Xoá</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Không có ca làm nào</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        {{ $shifts->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
