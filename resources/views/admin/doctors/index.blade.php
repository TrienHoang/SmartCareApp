@extends('admin.dashboard')

@section('content')
    <h2>Danh sách bác sĩ</h2>

    {{-- Thông báo --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Nút thêm mới --}}
    <a href="{{ route('admin.doctors.create') }}" class="btn btn-success mb-3">➕ Thêm bác sĩ</a>

    {{-- Form lọc --}}
    <form method="GET" action="{{ route('admin.doctors.index') }}" class="row mb-4">
        <div class="col-md-4 col-12 mb-2">
            <select name="department_id" class="form-control">
                <option value="">-- Lọc theo phòng ban --</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                        {{ $dept->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4 col-12 mb-2">
            <input type="text" name="specialization" class="form-control"
                   placeholder="Nhập chuyên môn..." value="{{ request('specialization') }}">
        </div>

        <div class="col-md-4 col-12 mb-2">
            <button type="submit" class="btn btn-primary">Lọc</button>
            <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">Đặt lại</a>
        </div>
    </form>

    {{-- Bảng danh sách responsive --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Họ Tên</th>
                    <th>Chuyên Môn</th>
                    <th>Phòng Ban</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($doctors as $doctor)
                    <tr>
                        <td>{{ $doctor->id }}</td>
                        <td>{{ $doctor->user->full_name ?? 'Không có' }}</td>
                        <td>{{ $doctor->specialization }}</td>
                        <td>{{ $doctor->department->name ?? 'Chưa có' }}</td>
                        <td>
                            <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn btn-primary btn-sm">Sửa</a>
                            <form action="{{ route('admin.doctors.destroy', $doctor->id) }}" method="POST"
                                  style="display:inline-block;"
                                  onsubmit="return confirm('Bạn có chắc muốn xóa bác sĩ này không?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Không có bác sĩ nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Phân trang --}}
    @if(method_exists($doctors, 'links'))
        <div class="mt-3">
            {{ $doctors->appends(request()->query())->links() }}
        </div>
    @endif
@endsection
