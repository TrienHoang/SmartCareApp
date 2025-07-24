@extends('doctor.dashboard')

@section('title', 'Đăng ký lịch nghỉ')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Đăng ký lịch nghỉ</h3>

    {{-- Thông tin bác sĩ --}}
    <div class="mb-4 border rounded p-3 bg-light">
        <h5>Thông tin bác sĩ</h5>
        <p><strong>Họ tên:</strong> {{ Auth::user()->full_name }}</p>
        <p><strong>Tên đăng nhập:</strong> {{ Auth::user()->username }}</p>
        <p><strong>Khoa:</strong> {{ Auth::user()->doctor->department->name ?? 'Chưa cập nhật' }}</p>
    </div>

    {{-- Thông báo lỗi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form đăng ký --}}
    <form method="POST" action="{{ route('doctor.leaves.store') }}">
        @csrf

        <div class="mb-3">
            <label for="start_date" class="form-label">Ngày bắt đầu</label>
            <input type="date" id="start_date" name="start_date" class="form-control" value="{{ old('start_date') }}">
            @error('start_date')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="end_date" class="form-label">Ngày kết thúc</label>
            <input type="date" id="end_date" name="end_date" class="form-control" value="{{ old('end_date') }}">
            @error('end_date')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="reason" class="form-label">Lý do nghỉ</label>
            <textarea name="reason" id="reason" class="form-control" rows="4">{{ old('reason') }}</textarea>
            @error('reason')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Checkbox nghỉ đột xuất --}}
        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="is_emergency" id="is_emergency" value="1" {{ old('is_emergency') ? 'checked' : '' }}>
            <label class="form-check-label text-danger" for="is_emergency">
                Đây là đơn nghỉ đột xuất (nghỉ gấp không cần báo trước)
            </label>
        </div>

        <button type="submit" class="btn btn-success">Gửi đơn nghỉ</button>
        <a href="{{ route('doctor.leaves.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
