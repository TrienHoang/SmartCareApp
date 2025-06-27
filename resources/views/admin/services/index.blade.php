@extends('admin.dashboard')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h2 class="mb-4">Danh sách Dịch vụ</h2>

        <a href="{{ route('admin.services.create') }}" class="btn btn-success mb-3">Thêm Dịch Vụ</a>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Tên</th>
                        <th>Loại</th>
                        <th>Giá</th>
                        <th>Thời gian</th>
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
                            <td>
                                @php
                                    $statusConfig = [
                                        'active' => 'success',
                                        'inactive' => 'danger',
                                    ];
                                    $statusClass = $statusConfig[$service->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">{{ ucfirst($service->status) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.services.show', $service) }}" class="btn btn-info btn-sm">Xem</a>
                                <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-warning btn-sm">Sửa</a>
                                <form action="{{ route('admin.services.destroy', $service) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xoá?')">Xoá</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer d-flex justify-content-end">
            {{ $services->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
