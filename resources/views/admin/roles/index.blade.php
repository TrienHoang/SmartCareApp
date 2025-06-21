@extends('admin.dashboard')
@section('title', 'Danh sách vai trò')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 breadcrumb-wrapper mb-4">
            <span class="text-muted fw-light">Roles /</span> Danh sách vai trò
        </h4>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Danh sách vai trò</h5>
                <a href="{{ route('admin.roles.create') }}" class="btn btn-sm btn-primary">
                    <i class="bx bx-plus"></i> Thêm vai trò
                </a>
            </div>

            @if (session('success'))
                <div class="alert alert-success m-3">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Tên vai trò</th>
                            <th>Quyền</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($roles as $role)
                            <tr>
                                <td>{{ $role->id }}</td>
                                <td>{{ $role->name }}</td>
                                <td>
                                    @php
                                        $permissions = $role->permissions->pluck('name')->toArray();
                                        $displayPermissions = array_slice($permissions, 0, 3);
                                        $remaining = count($permissions) - count($displayPermissions);
                                    @endphp

                                    @foreach ($displayPermissions as $perm)
                                        <span class="badge bg-label-info me-1">
                                            {{ getPermissionLabel($perm) }}
                                        </span>
                                    @endforeach

                                    @if ($remaining > 0)
                                        <a href="{{ route('admin.roles.show', $role->id) }}"
                                            class="badge bg-label-secondary text-decoration-none">
                                            +{{ $remaining }} quyền khác
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    @if ($role->name === 'admin')
                                        <span class="text-muted">Vai trò mặc định</span>
                                    @else   
                                        <a href="{{ route('admin.roles.show', $role->id) }}"
                                            class="btn btn-sm btn-info me-1">
                                            <i class="bx bx-show-alt me-1"></i> Xem
                                        </a>
                                        <a href="{{ route('admin.roles.edit', $role->id) }}"
                                            class="btn btn-sm btn-warning me-1">
                                            <i class="bx bx-edit-alt me-1"></i> Sửa
                                        </a>
                                        <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST"
                                            style="display:inline-block;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Xác nhận xóa vai trò này?')">
                                                <i class="bx bx-trash me-1"></i> Xóa
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>      
        </div>
        @if ($roles->hasPages())
        <div class="mt-4">
            {{ $roles->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
@endsection
