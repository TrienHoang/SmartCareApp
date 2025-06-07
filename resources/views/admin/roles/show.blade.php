@extends('admin.dashboard')
@section('title', 'Chi tiết vai trò')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 breadcrumb-wrapper mb-4">
            <span class="text-muted fw-light">Roles /</span> Chi tiết vai trò
        </h4>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Vai trò: {{ $role->name }}</h5>
            </div>

            <div class="card-body">
                <h6>Danh sách quyền:</h6>
                <div class="row">
                    @if (!empty($permissions))
                        @foreach ($permissions as $group => $perms)
                            <div class="mb-3">
                                <h6 class="text-primary">{{ __(getPermissionGroupLabel($group)) }}</h6>
                                <div class="row">
                                    @foreach ($perms as $permission)
                                        <div class="col-md-2 mb-2">
                                            <span class="">{{ getPermissionLabel($permission['name']) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                <hr>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Vai trò này chưa được gán quyền nào.</p>
                    @endif
                </div>

                <div class="mt-3 d-flex justify-content-end">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Quay lại</a>
                </div>
            </div>
        </div>
    </div>
@endsection