@extends('admin.dashboard')

@section('title', 'Thùng rác - Phòng đã xóa')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold mb-4">Thùng rác phòng</h2>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if ($rooms->isEmpty())
        <p class="text-gray-600">Không có phòng nào trong thùng rác.</p>
    @else
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Tên phòng</th>
                    <th class="px-4 py-2 text-left">Khoa</th>
                    <th class="px-4 py-2 text-left">Ngày xóa</th>
                    <th class="px-4 py-2 text-left">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rooms as $room)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $room->id }}</td>
                        <td class="px-4 py-2">{{ $room->name }}</td>
                        <td class="px-4 py-2">{{ $room->department->name ?? 'Chưa rõ' }}</td>
                        <td class="px-4 py-2">{{ $room->deleted_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-2">
                            <form action="{{ route('admin.rooms.restore', $room->id) }}" method="POST" onsubmit="return confirm('Khôi phục phòng này?')">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="text-blue-600 hover:underline">Khôi phục</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="mt-6">
        <a href="{{ route('admin.rooms.index') }}" class="text-gray-600 hover:underline">← Quay về danh sách phòng</a>
    </div>
</div>
@endsection
