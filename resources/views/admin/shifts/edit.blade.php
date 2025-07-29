@extends('admin.dashboard')

@section('title', 'Cập nhật ca làm việc')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Cập nhật ca làm việc</h2>

    <form action="{{ route('admin.shifts.update', $shift->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Tên ca làm</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $shift->name) }}" required>
            @error('name')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="start_time" class="form-label">Giờ bắt đầu</label>
            <input type="time" name="start_time" id="start_time" class="form-control" value="{{ old('start_time', $shift->start_time) }}" required>
            @error('start_time')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="end_time" class="form-label">Giờ kết thúc</label>
            <input type="time" name="end_time" id="end_time" class="form-control" value="{{ old('end_time', $shift->end_time) }}" required>
            @error('end_time')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <a href="{{ route('admin.shifts.index') }}" class="btn btn-secondary">Quay lại</a>
        <button type="submit" class="btn btn-success">Cập nhật</button>
    </form>
</div>
@endsection
