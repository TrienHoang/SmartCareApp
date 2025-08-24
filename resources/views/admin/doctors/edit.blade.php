@extends('admin.dashboard')

@section('content')
    <div class="container my-4">
        <h3 class="mb-4">Chỉnh sửa thông tin bác sĩ</h3>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.doctors.update', $doctor->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                {{-- Họ và tên --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Họ và tên đầy đủ <span class="text-danger">*</span></label>
                    <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror"
                        value="{{ old('full_name', $doctor->user->full_name ?? '') }}">
                    @error('full_name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $doctor->user->email ?? '') }}">
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Ảnh đại diện --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Ảnh đại diện</label>
                    <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror">
                    @if($doctor->user && $doctor->user->avatar)
                        <div class="mt-2">
                            <img src="{{ asset('storage/'.$doctor->user->avatar) }}" alt="Avatar" width="80"
                                 class="rounded">
                        </div>
                    @endif
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
                            <option value="{{ $dept->id }}"
                                {{ old('department_id', $doctor->department_id) == $dept->id ? 'selected' : '' }}>
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
                           value="{{ old('specialization', $doctor->specialization) }}">
                    @error('specialization')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div> --}}

                {{-- Tiểu sử --}}
                <div class="col-12 mb-3">
                    <label class="form-label fw-semibold">Tiểu sử</label>
                    <textarea name="biography" class="form-control @error('biography') is-invalid @enderror" rows="4">{{ old('biography', $doctor->biography) }}</textarea>
                    @error('biography')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-center gap-3 mt-4">
                <button type="submit" class="btn btn-primary px-4">Cập nhật</button>
                <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary px-4">Quay lại</a>
            </div>
        </form>
    </div>
@endsection