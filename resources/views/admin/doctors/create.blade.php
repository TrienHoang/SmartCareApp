@extends('admin.dashboard')

@section('title', 'Thêm Bác sĩ Mới')

@section('content')
<div class="container-fluid py-4 animate__animated animate__fadeIn">
    <!-- Enhanced Header Section -->
    <div class="content-header row">
        <div class="content-header-left col-md-8 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-circle bg-primary me-3 ">
                            <i class="bx bx-user-plus text-white "></i> {{-- Icon for Add Doctor --}}
                        </div>
                        <div>
                            <h2 class="content-header-title mb-0 text-primary fw-bold">Thêm Bác sĩ Mới</h2>
                            <p class="text-muted mb-0">Thêm thông tin bác sĩ mới vào hệ thống</p>
                        </div>
                    </div>
                    <div class="breadcrumb-wrapper col-12">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent p-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.dashboard.index') }}" class="text-decoration-none">Trang chủ</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.doctors.index') }}" class="text-decoration-none">Bác sĩ</a>
                                </li>
                                <li class="breadcrumb-item active text-primary fw-semibold">Thêm mới</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-header-right col-md-4 col-12 text-md-end">
            <div class="form-group breadcrum-right">
                <a href="{{ route('admin.doctors.index') }}"
                    class="btn btn-secondary btn-lg waves-effect waves-light shadow-lg">
                    <i class="bx bx-arrow-back me-2"></i> Quay lại danh sách
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card border-0 shadow rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4 p-4">
                    <h3 class="card-title mb-0">
                        <i class="bx bx-user-plus me-2"></i> Thêm bác sĩ mới
                    </h3>
                </div>
                <div class="card-body p-4 bg-light">
                    <form action="{{ route('admin.doctors.store') }}" method="POST" id="doctorForm">
                        @csrf

                        <div class="mb-3"> {{-- Replaced form-group with mb-3 for Bootstrap 5 spacing --}}
                            <label for="user_id" class="form-label fw-semibold">
                                <i class="bx bx-user me-2"></i> Chọn người dùng có sẵn
                            </label>
                            <select name="user_id" id="user_id"
                                class="form-select @error('user_id') is-invalid @enderror"> {{-- form-select for Bootstrap 5 --}}
                                <option value="">-- Chọn người dùng --</option>
                                @foreach($availableUsers as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->full_name ?? $user->username }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback d-flex align-items-center mt-1"> {{-- d-flex align-items-center for icon alignment --}}
                                    <i class="bx bx-info-circle me-1"></i> {{-- Changed icon to Boxicons --}}
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-text text-muted">Hoặc nhập tên mới bên dưới.</div> {{-- form-text for Bootstrap 5 --}}
                        </div>

                        <div class="mb-3">
                            <label for="specialization" class="form-label fw-semibold">Chuyên môn <span class="text-danger">*</span></label>
                            <input type="text" name="specialization" id="specialization"
                                class="form-control @error('specialization') is-invalid @enderror"
                                value="{{ old('specialization') }}">
                            @error('specialization')
                                <div class="invalid-feedback d-flex align-items-center mt-1">
                                    <i class="bx bx-info-circle me-1"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="department_id" class="form-label fw-semibold">Phòng ban <span class="text-danger">*</span></label>
                            <select name="department_id" id="department_id"
                                class="form-select @error('department_id') is-invalid @enderror">
                                <option value="">-- Chọn phòng ban --</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <div class="invalid-feedback d-flex align-items-center mt-1">
                                    <i class="bx bx-info-circle me-1"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="room_id" class="form-label fw-semibold">Phòng khám <span class="text-danger">*</span></label>
                            <select name="room_id" id="room_id"
                                class="form-select @error('room_id') is-invalid @enderror">
                                <option value="">-- Chọn phòng khám --</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                        {{ $room->name ?? $room->room_number }}
                                    </option>
                                @endforeach
                            </select>
                            @error('room_id')
                                <div class="invalid-feedback d-flex align-items-center mt-1">
                                    <i class="bx bx-info-circle me-1"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="biography" class="form-label fw-semibold">Tiểu sử</label>
                            <textarea name="biography" id="biography" class="form-control @error('biography') is-invalid @enderror"
                                rows="4">{{ old('biography') }}</textarea>
                            @error('biography')
                                <div class="invalid-feedback d-flex align-items-center mt-1">
                                    <i class="bx bx-info-circle me-1"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-center gap-3 mt-4"> {{-- Using d-flex and gap-3 for button spacing --}}
                            <button type="submit" class="btn btn-success btn-lg px-5 py-3 rounded-3 shadow-sm">
                                <i class="bx bx-plus me-2"></i> Thêm bác sĩ
                            </button>
                            <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary btn-lg px-5 py-3 rounded-3 shadow-sm">
                                <i class="bx bx-arrow-back me-2"></i> Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- No additional styles needed as they are handled by admin.dashboard or direct Bootstrap classes --}}
