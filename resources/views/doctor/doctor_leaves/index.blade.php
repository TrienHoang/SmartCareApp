@extends('doctor.dashboard')

@section('title', 'Lịch nghỉ của tôi')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">📅 Lịch nghỉ của tôi</h2>

    {{-- Hiển thị thông báo --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('doctor.leaves.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Đăng ký lịch nghỉ mới
        </a>
    </div>

    @if ($leaves->isEmpty())
        <div class="alert alert-info">Bạn chưa đăng ký lịch nghỉ nào.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Ngày bắt đầu</th>
                        <th>Ngày kết thúc</th>
                        <th>Lý do</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($leaves as $index => $leave)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($leave->end_date)->format('d/m/Y') }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($leave->reason, 80) }}</td>
                            <td>
                                @if ($leave->approved)
                                    <span class="badge bg-success">Đã duyệt</span>
                                @else
                                    <span class="badge bg-warning text-dark">Chưa duyệt</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('doctor.leaves.show', $leave->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Xem
                                    </a>
                                    @if (!$leave->approved)
                                        <a href="{{ route('doctor.leaves.edit', $leave->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Sửa
                                        </a>
                                        <form action="{{ route('doctor.leaves.destroy', $leave->id) }}" method="POST" style="display: inline-block;"
                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa lịch nghỉ này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i> Xóa
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
