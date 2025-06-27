@extends('admin.dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            {{-- Header --}}
            <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                <h4 class="mb-0">
                    <i class="fas fa-building mr-2"></i> Danh sách phòng ban
                </h4>
                <a href="{{ route('admin.departments.create') }}" class="btn btn-light text-primary">
                    ➕ Thêm phòng ban
                </a>
            </div>

            {{-- Body --}}
            <div class="card-body">

                {{-- Flash messages --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>
                @endif

                {{-- Danh sách --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Tên phòng ban</th>
                                <th>Mô tả</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($departments as $department)
                                <tr>
                                    <td>{{ $department->id }}</td>
                                    <td>{{ $department->name }}</td>
                                    <td>{{ $department->description }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.departments.edit', $department->id) }}"
                                           class="btn btn-sm btn-primary mr-1">✏️ Sửa</a>
                                        <form action="{{ route('admin.departments.destroy', $department->id) }}"
                                              method="POST" style="display:inline-block;"
                                              onsubmit="return confirm('Bạn có chắc muốn xóa phòng ban này không?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">🗑️ Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Không có phòng ban nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Phân trang --}}
                @if($departments->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $departments->appends(request()->query())->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
