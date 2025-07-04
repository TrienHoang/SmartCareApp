@extends('admin.dashboard')

@section('title', 'Quản lý bác sĩ')

@section('content')
    <div class="content-wrapper">
        <!-- Enhanced Header Section -->
        <div class="content-header row">
            <div class="content-header-left col-md-8 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-circle bg-primary mr-3">
                                <i class="bx bx-user-plus text-white"></i>
                            </div>
                            <div>
                                <h2 class="content-header-title mb-0 text-primary font-weight-bold">Quản lý Bác sĩ</h2>
                                <p class="text-muted mb-0">Quản lý thông tin bác sĩ và chuyên môn trong hệ thống</p>
                            </div>
                        </div>
                        <div class="breadcrumb-wrapper col-12">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0">
                                    <li class="">
                                        <a href="{{ route('admin.dashboard.index') }}" class="text-decoration-none">
                                            Trang chủ >
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active text-primary font-weight-semibold">
                                        Bác sĩ
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right col-md-4 col-12">
                <div class="btn-group float-md-right">
                    <button type="button" class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#createDoctorModal">
                        <i class="bx bx-plus mr-1"></i>Thêm bác sĩ
                    </button>
                </div>
            </div>
        </div>

        <div class="content-body">
            <!-- Enhanced Alert Messages -->
            @foreach (['success', 'error'] as $msg)
                @if(session($msg))
                    <div class="alert alert-{{ $msg == 'error' ? 'danger' : $msg }} alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-{{ $msg == 'success' ? 'check-circle' : 'x-circle' }} mr-2"></i>
                            <strong>{{ $msg == 'success' ? 'Thành công!' : 'Lỗi!' }} </strong> {{ session($msg) }}
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
            @endforeach

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card gradient-card bg-gradient-success">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white mr-2">
                                    <div class="avatar-content">
                                        <i class="bx bx-user-plus font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $doctors->total() ?? $doctors->count() }}</h4>
                                    <small class="text-white">Tổng số bác sĩ</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card gradient-card bg-gradient-info">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white mr-2">
                                    <div class="avatar-content">
                                        <i class="bx bx-building font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $departments->count() }}</h4>
                                    <small class="text-white">Phòng ban</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card gradient-card bg-gradient-warning">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white mr-2">
                                    <div class="avatar-content">
                                        <i class="bx bx-star font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $doctors->unique('specialization')->count() }}</h4>
                                    <small class="text-white">Chuyên môn</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card gradient-card bg-gradient-danger">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white mr-2">
                                    <div class="avatar-content">
                                        <i class="bx bx-calendar-check font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">0</h4>
                                    <small class="text-white">Lịch hẹn hôm nay</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Card -->
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-primary text-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-list mr-2"></i>
                            <h4 class="card-title mb-0 text-white font-weight-bold">Danh sách Bác sĩ</h4>
                        </div>
                        <div class="card-tools">
                            <span class="badge badge-light">{{ $doctors->total() ?? $doctors->count() }} bác sĩ</span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <!-- Enhanced Filter Section -->
                    <div class="filter-section bg-light p-4 border-bottom">
                        <form action="{{ route('admin.doctors.index') }}" method="GET" class="filter-form">
                            <div class="row align-items-end">
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-building mr-1 text-primary"></i>Phòng ban
                                    </label>
                                    <select name="department_id" class="form-control custom-select">
                                        <option value="">Tất cả phòng ban</option>
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                                {{ $dept->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-search mr-1 text-info"></i>Chuyên môn
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="specialization" class="form-control"
                                               placeholder="Nhập chuyên môn..." value="{{ request('specialization') }}">
                                    </div>
            
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-user mr-1 text-success"></i>Tên bác sĩ
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="name" class="form-control"
                                               placeholder="Nhập tên bác sĩ..." value="{{ request('name') }}">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <div class="btn-group w-100" role="group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bx bx-filter mr-1"></i>Lọc
                                        </button>
                                        <a href="{{ route('admin.doctors.index') }}" class="btn btn-outline-secondary">
                                            <i class="bx bx-refresh mr-1"></i>Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Enhanced Table -->
                    <div class="table-responsive">
                        <table class="table table-hover table-modern mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-top-0">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="select-all">
                                            <label class="custom-control-label" for="select-all"></label>
                                        </div>
                                    </th>
                                    <th class="border-top-0">#ID</th>
                                    <th class="border-top-0">
                                        <i class="bx bx-user mr-1"></i>Thông tin bác sĩ
                                    </th>
                                    <th class="border-top-0">
                                        <i class="bx bx-star mr-1"></i>Chuyên môn
                                    </th>
                                    <th class="border-top-0">
                                        <i class="bx bx-building mr-1"></i>Phòng ban
                                    </th>
                                    <th class="border-top-0">
                                        <i class="bx bx-phone mr-1"></i>Liên hệ
                                    </th>
                                    <th class="border-top-0 text-center">
                                        <i class="bx bx-cog mr-1"></i>Hành động
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($doctors as $doctor)
                                    <tr class="doctor-row" data-id="{{ $doctor->id }}">
                                        <td>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input doctor-checkbox"
                                                    id="doctor-{{ $doctor->id }}" value="{{ $doctor->id }}">
                                                <label class="custom-control-label" for="doctor-{{ $doctor->id }}"></label>
                                            </div>
                                        </td>
                                        <td class="font-weight-bold text-primary">
                                            #{{ $loop->iteration + (method_exists($doctors, 'currentPage') ? ($doctors->currentPage() - 1) * $doctors->perPage() : 0) }}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-wrapper mr-3">
                                                    @if($doctor->user && $doctor->user->avatar)
                                                        <img src="{{ asset('storage/' . $doctor->user->avatar) }}" alt="Avatar"
                                                             class="rounded-circle shadow" style="width: 50px; height: 50px; object-fit: cover;">
                                                    @else
                                                        <div class="rounded-circle bg-light d-flex justify-content-center align-items-center"
                                                             style="width: 50px; height: 50px;">
                                                            <i class="bx bx-user-circle text-muted fs-4"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="doctor-info">
                                                    <h6 class="mb-0 font-weight-semibold text-primary">
                                                        {{ $doctor->user->full_name ?? 'Không có thông tin' }}
                                                    </h6>
                                                    <small class="text-muted">
                                                        @{{ $doctor->user->username ?? 'N/A' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-info badge-pill">
                                                <i class="bx bx-star mr-1"></i>
                                                {{ $doctor->specialization }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success badge-pill">
                                                <i class="bx bx-building mr-1"></i>
                                                {{ $doctor->department->name ?? 'Chưa có' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="contact-info">
                                                @if($doctor->user && $doctor->user->phone)
                                                    <div class="d-flex align-items-center text-muted mb-1">
                                                        <i class="bx bx-phone mr-1"></i>
                                                        <small>{{ $doctor->user->phone }}</small>
                                                    </div>
                                                @endif
                                                @if($doctor->user && $doctor->user->email)
                                                    <div class="d-flex align-items-center text-muted">
                                                        <i class="bx bx-envelope mr-1"></i>
                                                        <small>{{ $doctor->user->email }}</small>
                                                    </div>
                                                @endif
                                                @if(!$doctor->user || (!$doctor->user->phone && !$doctor->user->email))
                                                    <small class="text-muted">Chưa có thông tin</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                {{-- Thiếu show --}}
                                                <a href="" 
                                                   class="btn btn-outline-info" data-toggle="tooltip" title="Chi tiết">
                                                    <i class="bx bx-show-alt"></i>
                                                </a>
                                                <a href="{{ route('admin.doctors.edit', $doctor->id) }}"
                                                   class="btn btn-outline-warning" data-toggle="tooltip" title="Chỉnh sửa">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger"
                                                        data-toggle="tooltip" title="Xóa" onclick="deleteDoctor({{ $doctor->id }})">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="bx bx-user-x text-muted" style="font-size: 48px;"></i>
                                                <h5 class="mt-3 text-muted">Không có bác sĩ nào</h5>
                                                <p class="text-muted">Chưa có bác sĩ nào được tạo hoặc không tìm thấy kết quả phù hợp.</p>
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDoctorModal">
                                                    <i class="bx bx-plus mr-1"></i>Thêm bác sĩ đầu tiên
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Enhanced Pagination -->
                    @if(method_exists($doctors, 'hasPages') && $doctors->hasPages())
                        <div class="pagination-wrapper bg-light p-3 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="pagination-info">
                                    <small class="text-muted">
                                        Hiển thị {{ $doctors->firstItem() }} - {{ $doctors->lastItem() }}
                                        trong tổng số {{ $doctors->total() }} kết quả
                                    </small>
                                </div>
                                <div class="pagination-links">
                                    {{ $doctors->appends(request()->query())->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Create Doctor Modal --}}
    <div class="modal fade" id="createDoctorModal" tabindex="-1" aria-labelledby="createDoctorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-gradient-primary text-white border-0">
                    <h5 class="modal-title" id="createDoctorModalLabel">
                        <i class="bx bx-user-plus me-2"></i>Thêm bác sĩ mới
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.doctors.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="user_id" class="form-label font-weight-semibold">Chọn người dùng <span class="text-danger">*</span></label>
                                <select class="form-control custom-select" id="user_id" name="user_id" required>
                                    <option value="">-- Chọn người dùng --</option>
                                    {{-- Giả sử bạn có $users từ controller --}}
                                    {{-- @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->full_name }} (@{{ $user->username }})</option>
                                    @endforeach --}}
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="department_id" class="form-label font-weight-semibold">Phòng ban <span class="text-danger">*</span></label>
                                <select class="form-control custom-select" id="department_id" name="department_id" required>
                                    <option value="">-- Chọn phòng ban --</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="specialization" class="form-label font-weight-semibold">Chuyên môn <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="specialization" name="specialization" required
                                       placeholder="Ví dụ: Tim mạch, Nhi khoa, Thần kinh...">
                            </div>
                            <div class="col-12 mb-3">
                                <label for="experience" class="form-label font-weight-semibold">Kinh nghiệm</label>
                                <textarea class="form-control" id="experience" name="experience" rows="3"
                                          placeholder="Mô tả kinh nghiệm làm việc của bác sĩ..."></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="license_number" class="form-label font-weight-semibold">Số giấy phép hành nghề</label>
                                <input type="text" class="form-control" id="license_number" name="license_number"
                                       placeholder="Nhập số giấy phép...">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="qualification" class="form-label font-weight-semibold">Trình độ</label>
                                <select class="form-control custom-select" id="qualification" name="qualification">
                                    <option value="">-- Chọn trình độ --</option>
                                    <option value="Bác sĩ">Bác sĩ</option>
                                    <option value="Thạc sĩ">Thạc sĩ</option>
                                    <option value="Tiến sĩ">Tiến sĩ</option>
                                    <option value="Giáo sư">Giáo sư</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x mr-1"></i>Hủy
                        </button>
                        <button type="submit" class="btn btn-gradient-primary">
                            <i class="bx bx-check mr-1"></i>Thêm bác sĩ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

                                    @endsection