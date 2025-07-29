@extends('admin.dashboard')

@section('title', 'Thêm bác sĩ mới')

@section('content')
<div class="container my-4">
    <h3 class="mb-4">➕ Thêm bác sĩ mới</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- THÊM multipart/form-data để upload ảnh --}}
    <form method="POST" action="{{ route('admin.doctors.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- Tạo user mới nếu không chọn user sẵn --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">👨‍⚕️ Họ tên bác sĩ mới</label>
                <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror"
                    value="{{ old('full_name') }}" placeholder="VD: Nguyễn Văn A">
                @error('full_name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">📧 Email bác sĩ mới</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" placeholder="VD: bacsi@email.com">
                @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">🔒 Mật khẩu</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                    placeholder="Nhập mật khẩu...">
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            {{-- UPLOAD AVATAR --}}
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">🖼️ Ảnh đại diện</label>
                <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror">
                @error('avatar')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <hr class="my-3">

        {{-- Thông tin chuyên môn bác sĩ --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">💼 Chuyên môn <span class="text-danger">*</span></label>
                <input type="text" name="specialization"
                    class="form-control @error('specialization') is-invalid @enderror"
                    value="{{ old('specialization') }}" placeholder="VD: Nội tổng quát">
                @error('specialization')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">🏥 Phòng ban <span class="text-danger">*</span></label>
                <select name="department_id"
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

            {{-- <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">🏨 Phòng khám <span class="text-danger">*</span></label>
                <select name="room_id" class="form-select @error('room_id') is-invalid @enderror">
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
            </div> --}}

            <div class="col-12 mb-3">
                <label class="form-label fw-semibold">📝 Tiểu sử</label>
                <textarea name="biography" class="form-control @error('biography') is-invalid @enderror"
                    rows="4" placeholder="Nhập tiểu sử...">{{ old('biography') }}</textarea>
                @error('biography')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex justify-content-center gap-3 mt-4">
            <button type="submit" class="btn btn-success px-4">
                <i class="fas fa-plus-circle me-1"></i> Thêm bác sĩ
            </button>
            <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary px-4">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </form>
</div>
@endsection
