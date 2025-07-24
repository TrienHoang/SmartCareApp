@extends('admin.dashboard')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
        <div class="card shadow-lg border border-warning rounded-4 animated fadeIn faster">
            <div class="card-header bg-warning text-dark d-flex align-items-center rounded-top">
                <i class="fas fa-tools fa-lg mr-2 text-dark"></i>
                <h4 class="mb-0">⚙️ <strong>Cập nhật phòng ban</strong></h4>
            </div>

            <div class="card-body bg-light rounded-bottom">
                {{-- Hiển thị lỗi --}}
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                        <ul class="mb-0 pl-3">
                            @foreach($errors->all() as $error)
                                <li><i class="fas fa-exclamation-circle text-danger mr-1"></i> {{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <form action="{{ route('admin.departments.update', $department->id) }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')

                    {{-- Tên --}}
                    <div class="form-group">
                        <label for="name" class="font-weight-bold text-dark">
                            <i class="fas fa-tag mr-1 text-info"></i> Tên phòng ban <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" id="name"
                               class="form-control shadow-sm rounded @error('name') is-invalid @enderror"
                               value="{{ old('name', $department->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback d-block">
                                <i class="fas fa-exclamation-triangle mr-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Mô tả --}}
                    <div class="form-group mt-3">
                        <label for="description" class="font-weight-bold text-dark">
                            <i class="fas fa-align-left mr-1 text-primary"></i> Mô tả
                        </label>
                        <textarea name="description" id="description"
                                  class="form-control shadow-sm rounded" rows="3">{{ old('description', $department->description) }}</textarea>
                        @error('description')
                            <div class="text-danger mt-1">
                                <i class="fas fa-info-circle mr-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Trạng thái --}}
                    <div class="form-group mt-3">
                        <label for="is_active" class="font-weight-bold text-dark">
                            <i class="fas fa-toggle-on mr-1 text-success"></i> Trạng thái hoạt động
                        </label>
                        <select name="is_active" id="is_active"
                                class="form-control shadow-sm rounded @error('is_active') is-invalid @enderror">
                            <option value="1" {{ old('is_active', $department->is_active) == 1 ? 'selected' : '' }}>
                                🟢 Hoạt động
                            </option>
                            <option value="0" {{ old('is_active', $department->is_active) == 0 ? 'selected' : '' }}>
                                🔴 Ngừng hoạt động
                            </option>
                        </select>
                        @error('is_active')
                            <div class="invalid-feedback d-block">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Nút --}}
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-success shadow px-4 py-2">
                            <i class="fas fa-save mr-1"></i> Lưu thay đổi
                        </button>
                        <a href="{{ route('admin.departments.index') }}" class="btn btn-outline-secondary ml-2 px-4 py-2">
                            <i class="fas fa-arrow-left mr-1"></i> Quay lại
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
