@extends('admin.dashboard')
@section('title', 'Chi tiết vai trò')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">Quản lý Vai trò /</span> Chi tiết vai trò
        </h4>

        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-primary">Vai trò: {{ $role->name }}</h5>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
                    <i class='bx bx-arrow-back me-1'></i> Quay lại danh sách
                </a>
            </div>

            <div class="card-body">
                <h6 class="mb-3 fw-bold">Danh sách quyền:</h6>

                @if (!empty($permissions))
                    <div class="row g-3"> {{-- Sử dụng g-3 để tạo khoảng cách giữa các cột --}}
                        @foreach ($permissions as $group => $perms)
                            <div class="col-12 mb-4"> {{-- Mỗi nhóm quyền chiếm toàn bộ chiều rộng --}}
                                <div class="bg-light p-3 rounded-3 border"> {{-- Tạo box cho nhóm quyền --}}
                                    <h6 class="text-secondary fw-bold mb-3">{{ __(getPermissionGroupLabel($group)) }}</h6>
                                    <div class="d-flex flex-wrap gap-2"> {{-- Dùng flexbox và gap cho các quyền --}}
                                        @foreach ($perms as $permission)
                                            <span class="badge bg-primary-subtle text-primary py-2 px-3 rounded-pill">
                                                {{ getPermissionLabel($permission['name']) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info text-center" role="alert">
                        <i class='bx bx-info-circle me-2'></i> Vai trò này hiện chưa được gán quyền nào.
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection

{{-- Bạn có thể thêm CSS tùy chỉnh nếu cần trong một file riêng hoặc trong section style --}}
@push('styles')
<style>
    /* Custom styles for better aesthetics */
    .py-3.mb-4 {
        padding-top: 1.5rem !important;
        padding-bottom: 1.5rem !important;
    }
    .card-header h5 {
        font-size: 1.5rem;
    }
    .bg-light.rounded-3 {
        background-color: #f8f9fa !important;
    }
    .badge.bg-primary-subtle {
        background-color: #e0f2fe !important; /* Lighter primary for badge background */
        color: #007bff !important; /* Primary color for text */
        font-weight: 500;
        white-space: nowrap; /* Prevent text from wrapping inside badge */
    }
</style>
@endpush