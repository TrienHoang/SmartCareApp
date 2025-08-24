@extends('admin.dashboard')

@section('title', 'Thêm bác sĩ mới')

@section('content')
    <div class="container my-4">
        <h3 class="mb-4">Thêm bác sĩ mới</h3>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.doctors.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                {{-- Họ và tên --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Họ và tên đầy đủ <span class="text-danger">*</span></label>
                    <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror"
                        value="{{ old('full_name') }}" placeholder="VD: Nguyễn Văn A">
                    @error('full_name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tên đăng nhập --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Tên đăng nhập <span class="text-danger">*</span></label>
                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                        value="{{ old('username') }}" placeholder="VD: nguyenvana123" autocomplete="off">
                    @error('username')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" placeholder="VD: bacsi@email.com">
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Mật khẩu --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Mật khẩu <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        placeholder="Nhập mật khẩu" autocomplete="new-password">
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Ảnh đại diện --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Ảnh đại diện</label>
                    <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror">
                    @error('avatar')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr class="my-3">

            <div class="row">
                {{-- Phòng ban --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Phòng ban <span class="text-danger">*</span></label>
                    <select name="department_id" class="form-select @error('department_id') is-invalid @enderror">
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

                {{-- Chuyên khoa --}}
                {{-- <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Chuyên khoa <span class="text-danger">*</span></label>
                    <input type="text" name="specialization" class="form-control @error('specialization') is-invalid @enderror"
                           value="{{ old('specialization') }}" placeholder="VD: Nội khoa, Ngoại khoa, Tim mạch...">
                    @error('specialization')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div> --}}

                {{-- Tiểu sử --}}
                <div class="col-12 mb-3">
                    <label class="form-label fw-semibold">Tiểu sử</label>
                    <textarea name="biography" class="form-control @error('biography') is-invalid @enderror" rows="4"
                        placeholder="Nhập tiểu sử...">{{ old('biography') }}</textarea>
                    @error('biography')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-center gap-3 mt-4">
                <button type="submit" class="btn btn-success px-4">Thêm bác sĩ</button>
                <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary px-4">Quay lại</a>
            </div>
        </form>
    </div>
@endsection