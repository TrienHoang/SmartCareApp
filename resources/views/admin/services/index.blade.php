@extends('admin.dashboard')
@section('content')
    <h2>Danh sách dịch vụ</h2>
    <a href="{{ route('admin.services.create') }}" class="btn btn-success">Thêm Dịch Vụ</a>
    <table class="table">
        <thead>
            <tr>
                <th>Tên</th>
                <th>Loại</th>
                <th>Giá</th>
                <th>Thời lượng</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($services as $service)
                <tr>
                    <td>{{ $service->name }}</td>
                    <td>{{ $service->category->name ?? '---' }}</td>
                    <td>{{ number_format($service->price) }} đ</td>
                    <td>{{ $service->duration }} phút</td>
                    <td>{{ $service->status }}</td>
                    <td>
                        <a href="{{ route('admin.services.show', $service) }}" class="btn btn-info btn-sm">Xem</a>
                        <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-warning btn-sm">Sửa</a>
                        <form action="{{ route('admin.services.destroy', $service) }}" method="POST"
                            style="display:inline-block">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Xoá?')">Xoá</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="card-footer d-flex justify-content-end">
        {{ $services->onEachSide(1)->links('pagination::bootstrap-5') }}
    </div>
@endsection
