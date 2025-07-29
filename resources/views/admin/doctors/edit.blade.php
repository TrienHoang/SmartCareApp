@extends('admin.dashboard')

@section('content')
<style>
    .card-3d {
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 1rem;
    }

    .card-3d:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
    }

    .form-control,
    .form-select,
    textarea {
        transition: all 0.2s ease-in-out;
        border-radius: 0.5rem;
    }

    .form-control:focus,
    .form-select:focus,
    textarea:focus {
        box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.25);
        transform: scale(1.01);
    }

    .btn-3d {
        transition: all 0.2s ease-in-out;
        border-radius: 0.5rem;
    }

    .btn-3d:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    .section-title {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #0d6efd;
        font-size: 1.05rem;
    }
</style>

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="card card-3d">
                <div class="card-header bg-info text-white d-flex align-items-center rounded-top">
                    <i class="fas fa-user-edit me-2"></i>
                    <h4 class="mb-0">Cập nhật thông tin bác sĩ</h4>
                </div>

                <div class="card-body bg-light">
                    <form action="{{ route('admin.doctors.update', $doctor->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Thông tin người dùng --}}
                        <div class="mb-4">
                            <div class="section-title">👤 Thông tin người dùng</div>
                            <input type="text" class="form-control bg-white" disabled
                                value="{{ $doctor->user->full_name ?? $doctor->user->username ?? 'Không có' }}">
                        </div>

                        {{-- Thông tin chuyên môn --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="section-title">💼 Chuyên môn</label>
                                <input type="text" name="specialization"
                                    class="form-control @error('specialization') is-invalid @enderror"
                                    value="{{ old('specialization', $doctor->specialization) }}">
                                @error('specialization')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mt-3 mt-md-0">
                                <label class="section-title">🏥 Phòng ban</label>
                                <select name="department_id"
                                    class="form-select @error('department_id') is-invalid @enderror">
                                    <option value="">-- Chọn phòng ban --</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}"
                                            {{ old('department_id', $doctor->department_id) == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror

                                {{-- Checkbox xác nhận đổi phòng ban --}}
                                @if(old('department_id', $doctor->department_id) != $doctor->department_id)
                                    <div class="form-check mt-2">
                                        <input type="checkbox" class="form-check-input" name="confirm_department_change"
                                            id="confirm_department_change" value="1">
                                        <label class="form-check-label text-danger fw-semibold" for="confirm_department_change">
                                            ⚠️ Tôi xác nhận muốn thay đổi phòng ban của bác sĩ này.
                                        </label>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Phòng khám --}}
                        {{-- <div class="mb-4">
                            <label class="section-title">🏨 Phòng khám</label>
                            <select name="room_id" class="form-select @error('room_id') is-invalid @enderror">
                                <option value="">-- Chọn phòng khám --</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}"
                                        {{ old('room_id', $doctor->room_id) == $room->id ? 'selected' : '' }}>
                                        {{ $room->name ?? 'Phòng ' . $room->room_number }}
                                    </option>
                                @endforeach
                            </select>
                            @error('room_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div> --}}

                        {{-- Tiểu sử --}}
                        <div class="mb-4">
                            <label class="section-title">📝 Tiểu sử</label>
                            <textarea name="biography" rows="4"
                                class="form-control @error('biography') is-invalid @enderror"
                                placeholder="Nhập tiểu sử bác sĩ...">{{ old('biography', $doctor->biography) }}</textarea>
                            @error('biography')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Hành động --}}
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-3d px-4">
                                <i class="fas fa-save me-1"></i> Cập nhật
                            </button>
                            <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary btn-3d px-4 ms-2">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
