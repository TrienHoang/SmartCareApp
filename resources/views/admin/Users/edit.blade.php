@extends('admin.dashboard')

@section('title', 'Chỉnh sửa người dùng')

@section('content')
<div class="content-wrapper">
    <!-- Header Section -->
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bx bx-user-edit me-2 text-primary" style="font-size: 24px;"></i>
                        <h2 class="content-header-title mb-0">Chỉnh sửa người dùng</h2>
                    </div>
                    <div class="breadcrumb-wrapper">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="">
                                    <a href="{{ route('admin.dashboard.index') }}" class="text-decoration-none">
                                        Trang chủ >
                                    </a>
                                </li>
                                <li class="">
                                    <a href="{{ route('admin.users.index') }}" class="text-decoration-none">
                                        Người dùng >
                                    </a>
                                </li>
                                <li class="breadcrumb-item active">Chỉnh sửa</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-body">
        <div class="row">
            <div class="col-12">
                <!-- Form Card -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="avatar-wrapper me-3">
                                <div class="avatar avatar-lg bg-primary">
                                    <i class="bx bx-user-edit text-white" style="font-size: 20px;"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="card-title mb-1">Thông tin người dùng</h4>
                                <small class="text-muted">Cập nhật thông tin và quyền của người dùng</small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <!-- Error Messages -->
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bx bx-error-circle me-2" style="font-size: 18px;"></i>
                                    <strong>Vui lòng kiểm tra lại thông tin:</strong>
                                </div>
                                <ul class="mb-0 ps-4">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-error-circle me-2" style="font-size: 18px;"></i>
                                    {{ session('error') }}
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <!-- Left Column: Avatar & Info -->
                                <div class="col-lg-4 mb-4 mb-lg-0">
                                    <div class="d-flex flex-column align-items-center">
                                        @if($user->avatar)
                                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar"
                                                 class="rounded-circle border border-3 border-primary shadow-sm mb-3"
                                                 style="width: 120px; height: 120px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white shadow-sm mb-3"
                                                 style="width: 120px; height: 120px;">
                                                <span>Chưa có ảnh</span>
                                            </div>
                                        @endif
                                        <div class="fw-bold mb-1">{{ $user->full_name }}</div>
                                        <div class="text-muted small">{{ $user->email }}</div>
                                    </div>
                                    <div class="mt-4">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item px-0"><strong>Điện thoại:</strong> {{ $user->phone }}</li>
                                            <li class="list-group-item px-0"><strong>Giới tính:</strong> {{ $user->gender }}</li>
                                            <li class="list-group-item px-0"><strong>Ngày sinh:</strong> {{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('d/m/Y') : '' }}</li>
                                            <li class="list-group-item px-0"><strong>Địa chỉ:</strong> {{ $user->address }}</li>
                                            <li class="list-group-item px-0"><strong>Ngày tạo:</strong> {{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : '' }}</li>
                                            <li class="list-group-item px-0"><strong>Cập nhật gần nhất:</strong> {{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i') : '' }}</li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- Right Column: Edit Form -->
                                <div class="col-lg-8">
                                    <div class="form-section mb-4">
                                        <div class="section-header mb-3">
                                            <h5 class="section-title">
                                                <i class="bx bx-cog text-primary me-2"></i>
                                                Phân quyền người dùng
                                            </h5>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="role_id" class="form-label fw-semibold">Chọn quyền</label>
                                            <select name="role_id" id="role_id" class="form-select form-select-lg" required>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                                        {{ $role->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('role_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="d-flex justify-content-center gap-3 mt-4 pt-2 border-top border-gray-300">
                                            <button type="submit" class="btn btn-success btn-lg px-5 py-3 rounded-3 shadow-sm">Cập nhật</button>
                                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-lg px-5 py-3 rounded-3 shadow-sm">Hủy</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-section {
        background: #fff;
        border-radius: 8px;
        padding: 1.5rem;
        border: 1px solid #e3e6f0;
        margin-bottom: 1.5rem;
    }
    .section-header {
        border-bottom: 2px solid #f8f9fc;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
    }
    .section-title {
        color: #5a5c69;
        font-weight: 600;
        margin: 0;
    }
    .btn {
        border-radius: 0.375rem;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        transition: all 0.2s ease;
    }
    .btn-success {
        background: linear-gradient(135deg, #39DA8A 0%, #55a3ff 100%);
        border: none;
    }
    .btn-success:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(57,218,138,0.2);
    }
    .btn-secondary:hover {
        transform: translateY(-1px);
    }
    @media (max-width: 991px) {
        .form-section {
            padding: 1rem;
        }
    }
</style>
@endpush
