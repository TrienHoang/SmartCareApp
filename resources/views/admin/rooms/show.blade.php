
@extends('admin.dashboard')

@section('title', 'Chi tiết Phòng')

@section('content')
<div class="max-w-2xl mx-auto p-6">
    <h2 class="text-xl font-bold mb-4">Chi tiết phòng</h2>

    <div class="space-y-3">
        <div><strong>ID:</strong> {{ $room->id }}</div>
        <div><strong>Tên phòng:</strong> {{ $room->name }}</div>
        <div><strong>Khoa:</strong> {{ $room->department->name ?? 'Chưa rõ' }}</div>
        <div><strong>Mô tả:</strong> {{ $room->description }}</div>
        <div><strong>Trạng thái:</strong> 
            @if ($room->status === 'active')
                <span class="text-green-600 font-semibold">Đang hoạt động</span>
            @elseif ($room->status === 'inactive')
                <span class="text-gray-500 font-semibold">Không hoạt động</span>
            @else
                <span class="text-red-600 font-semibold">Chưa rõ</span>
            @endif
        </div>
        <div><strong>Ngày tạo:</strong> {{ $room->created_at->format('d/m/Y H:i') }}</div>
        <div><strong>Ngày cập nhật:</strong> {{ $room->updated_at->format('d/m/Y H:i') }}</div>
        @if ($room->deleted_at)
        <div><strong>Đã xóa lúc:</strong> {{ $room->deleted_at->format('d/m/Y H:i') }}</div>
        @endif
    </div>

    <div class="mt-4 flex space-x-4">
        <a href="{{ route('admin.rooms.edit', $room->id) }}" class="text-blue-600 hover:underline">Sửa</a>
        <a href="{{ route('admin.rooms.index') }}" class="text-gray-600 hover:underline">Quay lại</a>
    </div>
</div>
@endsection
