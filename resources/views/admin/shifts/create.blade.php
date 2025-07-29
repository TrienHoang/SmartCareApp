@extends('admin.dashboard')

@section('title', 'Thêm ca làm việc')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Thêm ca làm việc mới</h2>

    <form action="{{ route('admin.shifts.store') }}" method="POST" novalidate>
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Tên ca làm</label>
            <input type="text" name="name" id="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}"
                   required minlength="3" maxlength="50"
                   pattern="^[^\d]+$"
                   title="Tên ca không được chứa số và ít nhất 3 ký tự.">
            @error('name')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="start_time" class="form-label">Giờ bắt đầu</label>
            <input type="time" name="start_time" id="start_time"
                   class="form-control @error('start_time') is-invalid @enderror"
                   value="{{ old('start_time') }}" required>
            @error('start_time')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="end_time" class="form-label">Giờ kết thúc</label>
            <input type="time" name="end_time" id="end_time"
                   class="form-control @error('end_time') is-invalid @enderror"
                   value="{{ old('end_time') }}" required>
            @error('end_time')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <a href="{{ route('admin.shifts.index') }}" class="btn btn-secondary">Quay lại</a>
        <button type="submit" class="btn btn-primary">Lưu</button>
    </form>
</div>
@endsection
