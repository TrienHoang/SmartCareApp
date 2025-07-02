@extends('doctor.dashboard')

@section('title', 'Chỉnh sửa lịch nghỉ')

@section('content')
<div class="container">
    <h3>Chỉnh sửa lịch nghỉ</h3>

    <form method="POST" action="{{ route('doctor.leaves.update', $leave->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="start_date" class="form-label">Ngày bắt đầu</label>
            <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $leave->start_date) }}">
            @error('start_date') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="end_date" class="form-label">Ngày kết thúc</label>
            <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $leave->end_date) }}">
            @error('end_date') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="reason" class="form-label">Lý do</label>
            <textarea name="reason" class="form-control" rows="4">{{ old('reason', $leave->reason) }}</textarea>
            @error('reason') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('doctor.leaves.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
