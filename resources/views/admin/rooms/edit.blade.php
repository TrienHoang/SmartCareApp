@extends('admin.dashboard')

@section('title', 'Sửa thông tin phòng')
@section('content')
    <div class="max-w-xl mx-auto p-6">
        <h2 class="text-xl font-bold mb-4">Chỉnh sửa phòng</h2>

        <form action="{{ route('admin.rooms.update', $room->id) }}" method="POST">
            @csrf @method('PUT')

            {{-- Tên phòng --}}
            <div class="mb-4">
                <label for="name" class="block font-medium">Tên phòng</label>
                <input type="text" name="name" id="name" class="w-full border px-3 py-2 rounded"
                    value="{{ old('name', $room->name) }}">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Khoa --}}
            <div class="mb-4">
                <label for="department_id" class="block font-medium">Khoa</label>
                <select name="department_id" id="department_id" class="w-full border px-3 py-2 rounded">
                    <option value="">-- Chọn khoa --</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept->id }}"
                            {{ old('department_id', $room->department_id) == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
                @error('department_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Mô tả --}}
            <div class="mb-4">
                <label for="description" class="block font-medium">Mô tả</label>
                <textarea name="description" id="description" rows="4" class="w-full border px-3 py-2 rounded">{{ old('description', $room->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Trạng thái --}}
            <div class="mb-4">
                <label for="status" class="block font-medium">Trạng thái</label>
                <select name="status" id="status" class="w-full border px-3 py-2 rounded">
                    <option value="active" {{ old('status', $room->status) == 'active' ? 'selected' : '' }}>Đang hoạt động
                    </option>
                    <option value="inactive" {{ old('status', $room->status) == 'inactive' ? 'selected' : '' }}>Ngưng hoạt
                        động</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Cập nhật
            </button>

            <a href="{{ route('admin.rooms.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded ml-2 inline-block">
                Quay lại
            </a>

        </form>
    </div>
@endsection
