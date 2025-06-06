@extends('admin.dashboard')
@section('title', 'Thêm vai trò')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 breadcrumb-wrapper mb-4">
            <span class="text-muted fw-light">Roles /</span> Thêm vai trò
        </h4>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Tạo vai trò mới</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.roles.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Tên vai trò</label>
                        <input type="text" name="name" id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="Nhập tên vai trò" value="{{ old('name') }}">
                        @error('name')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Chọn quyền</label>
                        <div class="mb-2">
                            <button type="button" class="btn btn-sm btn-primary me-2 mb-3" onclick="toggleAllPermissions(true)">Chọn tất cả</button>
                            <button type="button" class="btn btn-sm btn-secondary mb-3" onclick="toggleAllPermissions(false)">Bỏ chọn tất cả</button>
                        </div>
                        <div class="row">
                            @if (!empty($permissions))
                                @foreach ($permissions as $group => $perms)
                                    <div class="mb-3">
                                        <h6 class="text-primary">{{ __(getPermissionGroupLabel($group)) }}</h6>
                                        <div class="row">
                                            @foreach ($perms as $permission)
                                                <div class="col-md-4">
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input permission-checkbox" type="checkbox"
                                                               name="permissions[]"
                                                               value="{{ $permission['id'] }}"
                                                               id="perm_{{ $permission['id'] }}">
                                                        <label class="form-check-label" for="perm_{{ $permission['id'] }}">
                                                            {{ getPermissionLabel($permission['name']) }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <hr>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted">Không có quyền nào được tìm thấy.</p>
                            @endif
                        </div>
                        @error('permissions')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary me-2">Hủy</a>
                        <button type="submit" class="btn btn-primary">Tạo vai trò</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function toggleAllPermissions(select) {
                const checkboxes = document.querySelectorAll('.permission-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = select;
                });
            }
        </script>
    @endpush
@endsection