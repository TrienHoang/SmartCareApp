@extends('admin.dashboard')

@section('title', 'Quản lý Đánh giá')
@section('content')
<div class="max-w-xl mx-auto p-6">
    <h2 class="text-xl font-bold mb-4">Chỉnh sửa phòng</h2>

    <form action="{{ route('admin.rooms.update', $room->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-4">
            <label for="name" class="block font-medium">Tên phòng</label>
            <input type="text" name="name" id="name" class="w-full border px-3 py-2 rounded" value="{{ $room->name }}">
        </div>

        <div class="mb-4">
            <label for="department_id" class="block font-medium">Khoa</label>
            <select name="department_id" id="department_id" class="w-full border px-3 py-2 rounded">
                <option value="">-- Chọn khoa --</option>
                @foreach ($departments as $dept)
                    <option value="{{ $dept->id }}" @selected($room->department_id == $dept->id)>{{ $dept->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="description" class="block font-medium">Mô tả</label>
            <textarea name="description" id="description" rows="4" class="w-full border px-3 py-2 rounded">{{ $room->description }}</textarea>
        </div>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Cập nhật</button>
    </form>
</div>
@endsection
