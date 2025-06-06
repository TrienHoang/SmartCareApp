@extends('admin.dashboard')

@section('content')
    <h2>Danh sách bác sĩ</h2>

    <a href="{{ route('admin.doctors.create') }}" class="btn btn-success mb-3">Thêm bác sĩ</a>

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
                    <td>{{ $doctor->user->name ?? 'Không có' }}</td>
                    <td>{{ $doctor->specialization }}</td>
                    <td>{{ $doctor->department->name ?? 'Chưa có' }}</td>
                    <td>
                        <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn btn-primary btn-sm">Sửa</a>
                        
                        <form action="{{ route('admin.doctors.destroy', $doctor->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc muốn xóa bác sĩ này không?');">
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

    {{-- Phân trang --}}
    @if(method_exists($doctors, 'links'))
        <div class="mt-3">
            {{ $doctors->links() }}
        </div>
    @endif
@endsection
