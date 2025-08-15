@extends('admin.dashboard')

@section('content')
<div class="container my-4">
    <h3 class="mb-4">Cập nhật thông tin bác sĩ</h3>

    <form action="{{ route('admin.doctors.update', $doctor->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Họ và tên đầy đủ</label>
                <input type="text" 
                       class="form-control" 
                       value="{{ $doctor->user->full_name }}" 
                       disabled>
                {{-- Nếu muốn gửi kèm giá trị vào request (dù disabled) thì dùng hidden --}}
                <input type="hidden" name="full_name" value="{{ $doctor->user->full_name }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" 
                       class="form-control" 
                       value="{{ $doctor->user->email }}" 
                       disabled>
                <input type="hidden" name="email" value="{{ $doctor->user->email }}">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Ảnh đại diện</label>
            @if($doctor->user->avatar)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $doctor->user->avatar) }}" 
                         alt="Avatar" class="rounded" 
                         style="width: 100px; height: 100px; object-fit: cover;">
                </div>
            @endif
            <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror">
            @error('avatar')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Dịch vụ <span class="text-danger">*</span></label>
                <select name="service_id" class="form-select @error('service_id') is-invalid @enderror">
                    <option value="">-- Chọn dịch vụ --</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" 
                            {{ old('service_id', $doctor->services->first()->id ?? null) == $service->id ? 'selected' : '' }}>
                            {{ $service->name }}
                        </option>
                    @endforeach
                </select>
                @error('service_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Phòng ban <span class="text-danger">*</span></label>
                <select name="department_id" class="form-select @error('department_id') is-invalid @enderror">
                    <option value="">-- Chọn phòng ban --</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" 
                            {{ old('department_id', $doctor->department_id) == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
                @error('department_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Tiểu sử</label>
            <textarea name="biography" rows="4" class="form-control @error('biography') is-invalid @enderror" placeholder="Nhập tiểu sử...">{{ old('biography', $doctor->biography) }}</textarea>
            @error('biography')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Cập nhật
            </button>
            <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </form>
</div>
@endsection
