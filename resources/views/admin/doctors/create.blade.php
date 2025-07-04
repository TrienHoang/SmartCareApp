@extends('admin.dashboard')

@section('content')
<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user-md me-2"></i> Thêm bác sĩ mới
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.doctors.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="user_id" class="form-label fw-semibold">
                                <i class="fas fa-user me-1"></i> Chọn người dùng <span class="text-danger">*</span>
                            </label>
                            <select name="user_id" id="user_id" 
                                class="form-select @error('user_id') is-invalid @enderror">
                                <option value="">-- Chọn người dùng --</option>
                                @foreach($availableUsers as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->full_name }} (ID: {{ $user->id }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="specialization" class="form-label fw-semibold">
                                    Chuyên môn <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="specialization" name="specialization"
                                    class="form-control @error('specialization') is-invalid @enderror"
                                    value="{{ old('specialization') }}" placeholder="Nhập chuyên môn...">
                                @error('specialization')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="department_id" class="form-label fw-semibold">
                                    Phòng ban <span class="text-danger">*</span>
                                </label>
                                <select name="department_id" id="department_id"
                                    class="form-select @error('department_id') is-invalid @enderror">
                                    <option value="">-- Chọn phòng ban --</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="room_id" class="form-label fw-semibold">
                                    Phòng khám <span class="text-danger">*</span>
                                </label>
                                <select name="room_id" id="room_id"
                                    class="form-select @error('room_id') is-invalid @enderror">
                                    <option value="">-- Chọn phòng khám --</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                            {{ $room->name ?? 'Phòng ' . $room->room_number }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('room_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="biography" class="form-label fw-semibold">Tiểu sử</label>
                                <textarea name="biography" id="biography" rows="4"
                                    class="form-control @error('biography') is-invalid @enderror"
                                    placeholder="Nhập tiểu sử bác sĩ...">{{ old('biography') }}</textarea>
                                @error('biography')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-center gap-2 mt-4">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="fas fa-plus-circle me-1"></i> Thêm bác sĩ
                            </button>
                            <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary px-4">
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
