@extends('admin.dashboard')

@section('content')
<h2>Sửa bác sĩ</h2>

<form action="{{ route('admin.doctors.update', $doctor->id) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- Không sửa tên người dùng ở đây --}}
    {{-- Nếu muốn hiển thị tên người dùng chỉ để tham khảo --}}
    <div class="mb-3">
        <label>Người dùng:</label>
        <input type="text" class="form-control" value="{{ $doctor->user->full_name ?? $doctor->user->username ?? 'Không có' }}" disabled>
    </div>

    <div class="mb-3">
        <label>Chuyên môn:</label>
        <input type="text" name="specialization" class="form-control" value="{{ old('specialization', $doctor->specialization) }}">
        @error('specialization')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label>Phòng ban:</label>
        <select name="department_id" class="form-control">
            <option value="">Chọn phòng ban</option>
            @foreach($departments as $department)
                <option value="{{ $department->id }}" {{ (old('department_id', $doctor->department_id) == $department->id) ? 'selected' : '' }}>
                    {{ $department->name }}
                </option>
            @endforeach
        </select>
        @error('department_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label>Tiểu sử:</label>
        <textarea name="biography" class="form-control" rows="4">{{ old('biography', $doctor->biography) }}</textarea>
        @error('biography')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">Cập nhật</button>
</form>
@endsection
