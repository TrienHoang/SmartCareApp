@extends('admin.dashboard')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
        <div class="card border-0 shadow-lg rounded-3 animate__animated animate__fadeInUp"
            style="animation-duration: 0.8s;">
            <div class="card-header text-white d-flex align-items-center"
                style="background: linear-gradient(135deg, #00c9ff 0%, #92fe9d 100%)">
                <i class="fas fa-plus-circle mr-2 fa-lg"></i>
                <h4 class="mb-0 font-weight-bold">🎯 Thêm Phòng Ban Mới</h4>
            </div>

            <div class="card-body bg-white">
                {{-- Thông báo thành công --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <strong>🎉 {{ session('success') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <form action="{{ route('admin.departments.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf

                    {{-- Tên phòng ban --}}
                    <div class="form-group">
                        <label for="name" class="font-weight-semibold text-primary">
                            <i class="fas fa-tag mr-1"></i> Tên phòng ban <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" id="name"
                            class="form-control shadow-sm @error('name') is-invalid @enderror"
                            placeholder="VD: Nội soi tiêu hóa" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Mô tả --}}
                    <div class="form-group">
                        <label for="description" class="font-weight-semibold text-info">
                            <i class="fas fa-align-left mr-1"></i> Mô tả
                        </label>
                        <textarea name="description" id="description"
                            class="form-control shadow-sm" rows="3"
                            placeholder="Mô tả ngắn về phòng ban...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Trạng thái --}}
                    <div class="form-group mt-3">
                        <label for="is_active" class="font-weight-bold">⚙️ Trạng thái hoạt động</label>
                        <select name="is_active" id="is_active"
                            class="form-control shadow-sm @error('is_active') is-invalid @enderror" required>
                            <option value="1" {{ old('is_active', '1') === '1' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>Ngừng hoạt động</option>
                        </select>
                        @error('is_active')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nút --}}
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-lg btn-gradient-primary shadow-lg px-4 py-2"
                            style="background: linear-gradient(to right, #667eea, #764ba2); color: white;">
                            <i class="fas fa-save mr-1"></i> Lưu phòng ban
                        </button>
                        <a href="{{ route('admin.departments.index') }}"
                            class="btn btn-outline-secondary btn-lg ml-2 px-4">
                            <i class="fas fa-arrow-left mr-1"></i> Quay lại
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
