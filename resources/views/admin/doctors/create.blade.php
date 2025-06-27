@extends('admin.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <i class="fas fa-user-md mr-2"></i>
                    <h4 class="mb-0">Thêm bác sĩ mới</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.doctors.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            {{-- Người dùng --}}
                            <div class="col-12 mb-3">
                                <label><i class="fas fa-user"></i> Người dùng có sẵn</label>
                                <select name="user_id" class="form-control @error('user_id') is-invalid @enderror">
                                    <option value="">-- Chọn người dùng --</option>
                                    @foreach($availableUsers as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->full_name ?? $user->username }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Chuyên môn --}}
                            <div class="col-md-6 col-12 mb-3">
                                <label>Chuyên môn <span class="text-danger">*</span></label>
                                <input type="text" name="specialization"
                                       class="form-control @error('specialization') is-invalid @enderror"
                                       value="{{ old('specialization') }}">
                                @error('specialization')
                                    <small class="text-danger d-block mt-1">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            {{-- Phòng ban --}}
                            <div class="col-md-6 col-12 mb-3">
                                <label>Phòng ban <span class="text-danger">*</span></label>
                                <select name="department_id"
                                        class="form-control @error('department_id') is-invalid @enderror">
                                    <option value="">-- Chọn phòng ban --</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <small class="text-danger d-block mt-1">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            {{-- Phòng khám --}}
                            <div class="col-md-6 col-12 mb-3">
                                <label>Phòng khám <span class="text-danger">*</span></label>
                                <select name="room_id"
                                        class="form-control @error('room_id') is-invalid @enderror">
                                    <option value="">-- Chọn phòng khám --</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                            {{ $room->name ?? 'Phòng ' . $room->room_number }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('room_id')
                                    <small class="text-danger d-block mt-1">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            {{-- Tiểu sử --}}
                            <div class="col-12 mb-3">
                                <label>Tiểu sử</label>
                                <textarea name="biography" rows="4"
                                          class="form-control @error('biography') is-invalid @enderror">{{ old('biography') }}</textarea>
                                @error('biography')
                                    <small class="text-danger d-block mt-1">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </small>
                                @enderror
                            </div>
                        </div>

                        {{-- Nút hành động --}}
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-plus-circle"></i> Thêm bác sĩ
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
</div>
@endsection
