@extends('admin.dashboard')
@section('title', 'Edit Doctor Leave')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Doctor Leave</h1>

    {{-- Hiển thị thông tin bác sĩ --}}
    <div class="mb-3">
        <label class="form-label"><strong>Họ và tên bác sĩ:</strong></label>
        <p>{{ $leave->doctor->user->full_name ?? 'Chưa có thông tin' }}</p>
    </div>

    <div class="mb-3">
        <label class="form-label"><strong>Số phòng:</strong></label>
        <p>{{ $leave->doctor->room->name ?? 'Chưa có thông tin' }}</p>
    </div>
    {{-- Form chỉnh sửa --}}
    <form action="{{ route('admin.doctor_leaves.update', $leave->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="approved" class="form-label">Trạng thái duyệt</label>
            <select class="form-select" id="approved" name="approved" required>
                <option value="0" {{ $leave->approved == 0 ? 'selected' : '' }}>Chưa duyệt</option>
                <option value="1" {{ $leave->approved == 1 ? 'selected' : '' }}>Đã duyệt</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.doctor_leaves.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
    </form>
</div>
@endsection
