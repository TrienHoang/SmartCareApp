@extends('admin.dashboard')

@section('content')
    <div class="container mt-4">
        <h2>📋 Danh sách công việc</h2>

        {{-- Thông báo --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Form tìm kiếm nâng cao --}}
        <form method="GET" action="{{ route('admin.tasks.index') }}" class="row g-3 mb-4">
            <div class="col-md-3">
                <input type="text" name="title" class="form-control" placeholder="🔍 Tìm theo tiêu đề"
                    value="{{ request('title') }}">
            </div>

            <div class="col-md-3">
                <select name="assigned_to" class="form-select">
                    <option value="">👤 Người thực hiện</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected(request('assigned_to') == $user->id)>
                            {{ $user->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">📝 Trạng thái</option>
                    @foreach(['moi_tao' => 'Mới tạo', 'dang_lam' => 'Đang làm', 'hoan_thanh' => 'Hoàn thành', 'tre_han' => 'Trễ hạn'] as $val => $label)
                        <option value="{{ $val }}" @selected(request('status') == $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <select name="priority" class="form-select">
                    <option value="">🚦 Ưu tiên</option>
                    @foreach(['thap' => 'Thấp', 'trung_binh' => 'Trung bình', 'cao' => 'Cao'] as $val => $label)
                        <option value="{{ $val }}" @selected(request('priority') == $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button class="btn btn-outline-primary w-100">Lọc kết quả</button>
                <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-secondary w-100">🔄 Đặt lại</a>
            </div>
        </form>


        <div class="mb-3 text-end">
            <a href="{{ route('admin.tasks.create') }}" class="btn btn-primary">➕ Thêm công việc</a>
        </div>

        {{-- Bảng danh sách --}}
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Tiêu đề</th>
                    <th>Người thực hiện</th>
                    <th>Trạng thái</th>
                    <th>Ưu tiên</th>
                    <th>Hạn chót</th>
                    <th width="180">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                    <tr>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->assignedUser->full_name ?? '—' }}</td>
                        <td>
                            @php
                                $statusLabels = [
                                    'moi_tao' => '🟡 Mới tạo',
                                    'dang_lam' => '🟠 Đang làm',
                                    'hoan_thanh' => '🟢 Hoàn thành',
                                    'tre_han' => '🔴 Trễ hạn',
                                ];
                            @endphp
                            {{ $statusLabels[$task->status] ?? $task->status }}
                        </td>
                        <td>
                            @php
                                $priorityLabels = [
                                    'thap' => 'Thấp',
                                    'trung_binh' => 'Trung bình',
                                    'cao' => 'Cao',
                                ];
                            @endphp
                            {{ $priorityLabels[$task->priority] ?? '—' }}
                        </td>
                        <td>{{ $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('d/m/Y H:i') : '—' }}</td>
                        <td>
                            <a href="{{ route('admin.tasks.show', $task) }}" class="btn btn-sm btn-info">👁️ Xem</a>
                            <a href="{{ route('admin.tasks.edit', $task) }}" class="btn btn-sm btn-warning">✏️ Sửa</a>
                            <form action="{{ route('admin.tasks.destroy', $task) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Bạn có chắc muốn xoá công việc này?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">🗑️ Xoá</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Không có công việc phù hợp.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-end">
            {{ $tasks->links() }}
        </div>
    </div>
@endsection