@extends('admin.dashboard')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h2 class="mb-4">Chi tiết Dịch vụ</h2>

        <div class="card">
            <div class="card-body">
                <p><strong>Tên dịch vụ:</strong> {{ $service->name }}</p>
                <p><strong>Loại dịch vụ:</strong> {{ $service->category->name ?? '---' }}</p>
                <p><strong>Giá:</strong> {{ number_format($service->price) }} đ</p>
                <p><strong>Thời lượng:</strong> {{ $service->duration }} phút</p>
                <p><strong>Trạng thái:</strong>
                    @if ($service->status == 'active')
                        <span class="text-success">Hoạt động</span>
                    @else
                        <span class="text-danger">Tạm ngừng</span>
                    @endif
                </p>
                <p><strong>Mô tả:</strong> {{ $service->description ?? 'Không có' }}</p>
                <p><strong>Ngày tạo:</strong> {{ $service->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Cập nhật gần nhất:</strong> {{ $service->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div class="d-flex gap-2 mt-3">
            <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
            <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-warning">Sửa</a>
        </div>
    </div>
@endsection
