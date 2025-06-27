@extends('admin.dashboard')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-10">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white d-flex align-items-center">
                <i class="fas fa-user-edit mr-2"></i>
                <h4 class="mb-0">Sửa thông tin bác sĩ</h4>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.doctors.update', $doctor->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        {{-- Người dùng --}}
                        <div class="col-12 mb-3">
                            <label>Người dùng:</label>
                            <input type="text" class="form-control" disabled
                                value="{{ $doctor->user->full_name ?? $doctor->user->username ?? 'Không có' }}">
                        </div>

                        {{-- Chuyên môn --}}
                        <div class="col-12 col-md-6 mb-3">
                            <label>Chuyên môn:</label>
                            <input type="text" name="specialization" class="form-control @error('specialization') is-invalid @enderror"
                                value="{{ old('specialization', $doctor->specialization) }}">
                            @error('specialization')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Phòng ban --}}
                        <div class="col-12 col-md-6 mb-3">
                            <label>Phòng ban:</label>
                            <select name="department_id" class="form-control @error('department_id') is-invalid @enderror">
                                <option value="">Chọn phòng ban</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ (old('department_id', $doctor->department_id) == $department->id) ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Phòng khám --}}
                        <div class="col-12 col-md-6 mb-3">
                            <label>Phòng khám:</label>
                            <select name="room_id" class="form-control @error('room_id') is-invalid @enderror">
                                <option value="">Chọn phòng khám</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" {{ (old('room_id', $doctor->room_id) == $room->id) ? 'selected' : '' }}>
                                        {{ $room->name ?? 'Phòng ' . $room->room_number }}
                                    </option>
                                @endforeach
                            </select>
                            @error('room_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tiểu sử --}}
                        <div class="col-12 mb-3">
                            <label>Tiểu sử:</label>
                            <textarea name="biography" rows="4"
                                class="form-control @error('biography') is-invalid @enderror">{{ old('biography', $doctor->biography) }}</textarea>
                            @error('biography')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Nút hành động --}}
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Cập nhật
                        </button>
                        <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
