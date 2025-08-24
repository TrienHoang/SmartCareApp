@extends('admin.dashboard')

@section('title', 'Quản lý Phòng')

@section('content')

    <div class="max-w-6xl mx-auto p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Danh sách phòng</h1>
            <div>
                <a href="{{ route('admin.rooms.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">+
                    Thêm phòng</a>
                <a href="{{ route('admin.rooms.trash') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Phòng đã xóa</a>

            </div>

        </div>

        <table class="min-w-full border bg-white shadow rounded-lg">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="p-3">#</th>
                    <th class="p-3">Tên phòng</th>
                    <th class="p-3">Khoa</th>
                    <th class="p-3">Mô tả</th>
                    <th class="p-3">Trạng thái</th>
                    <th class="p-3 text-right">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rooms as $room)
                    <tr class="border-t">
                        <td class="p-3">{{ $loop->iteration }}</td>
                        <td class="p-3">{{ $room->name }}</td>
                        <td class="p-3">{{ $room->department->name ?? 'Chưa rõ' }}</td>
                        <td class="p-3">{{ Str::limit($room->description, 50) }}</td>
                        <td class="p-3">
                            @php
                                $statusConfig = [
                                    'active' => ['class' => 'success', 'icon' => 'check-circle'],
                                    'inactive' => ['class' => 'secondary', 'icon' => 'x-circle'],
                                ];
                                $config = $statusConfig[$room->status] ?? $statusConfig['inactive'];
                            @endphp

                            <form action="{{ route('admin.rooms.toggleStatus', $room->id) }}" method="POST"
                                class="inline-block">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="btn btn-sm btn-{{ $config['class'] }} rounded-full px-3 py-1 flex items-center gap-1">
                                    <i class="bx bx-{{ $config['icon'] }}"></i>
                                    {{ ucfirst($room->status) }}
                                </button>
                            </form>
                        </td>
                        <td class="p-3 text-right space-x-2">
                            <a href="{{ route('admin.rooms.show', $room->id) }}"
                                class="text-blue-600 hover:underline">Xem</a>
                            <a href="{{ route('admin.rooms.edit', $room->id) }}"
                                class="text-yellow-600 hover:underline">Sửa</a>


                            <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST"
                                class="inline-block" onsubmit="return confirm('Xóa phòng này?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Xoá</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if ($rooms->hasPages())
        <div class="pagination-wrapper bg-light p-3 border-top">
            <div class="d-flex justify-content-between align-items-center">
                <div class="pagination-info">
                    <small class="text-muted">
                        Hiển thị {{ $rooms->firstItem() }} - {{ $rooms->lastItem() }}
                        trong tổng số {{ $rooms->total() }} kết quả
                    </small>
                </div>
                <div class="pagination-links">
                    {{ $rooms->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    @endif

@endsection
