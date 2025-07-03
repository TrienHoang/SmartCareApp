@extends('doctor.dashboard')

@section('title', 'Đăng ký lịch nghỉ')

@section('content')
<div class="container">
    <h3 class="mb-4">Đăng ký lịch nghỉ</h3>

    {{-- Thông tin bác sĩ --}}
    <div class="mb-4 border rounded p-3 bg-light">
        <h5>Thông tin bác sĩ</h5>
        <p><strong>Họ tên:</strong> {{ Auth::user()->full_name }}</p>
        <p><strong>Tên đăng nhập:</strong> {{ Auth::user()->username }}</p>
        <p><strong>Khoa:</strong> {{ Auth::user()->doctor->department->name ?? 'Chưa cập nhật' }}</p>
    </div>

    {{-- Form đăng ký nghỉ phép --}}
    <form method="POST" action="{{ route('doctor.leaves.store') }}">
        @csrf

        <div class="mb-3">
            <label for="start_date" class="form-label">Ngày bắt đầu</label>
            <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}">
            @error('start_date') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="end_date" class="form-label">Ngày kết thúc</label>
            <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
            @error('end_date') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="reason" class="form-label">Lý do</label>
            <textarea name="reason" class="form-control" rows="4">{{ old('reason') }}</textarea>
            @error('reason') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-success">Gửi yêu cầu</button>
        <a href="{{ route('doctor.leaves.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
