@extends('admin.dashboard')

@section('content')
<div class="container mt-4">
    <h2>📝 Sửa công việc</h2>

    {{-- Thông báo lỗi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Đã xảy ra lỗi!</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.tasks.update', $task->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control"
                   value="{{ old('title', $task->title) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $task->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Hạn chót</label>
            <input type="date" name="deadline" class="form-control"
                   value="{{ old('deadline', optional($task->deadline)->format('Y-m-d')) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Trạng thái</label>
            <select name="status" class="form-select">
                @foreach(['moi_tao' => 'Mới tạo', 'dang_lam' => 'Đang làm', 'hoan_thanh' => 'Hoàn thành', 'tre_han' => 'Trễ hạn'] as $val => $label)
                    <option value="{{ $val }}" @selected(old('status', $task->status) === $val)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Giao cho</label>
            <select name="assigned_to" class="form-select">
                <option value="">-- Không chọn --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" @selected(old('assigned_to', $task->assigned_to) == $user->id)>
                        {{ $user->full_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Mức độ ưu tiên</label>
            <select name="priority" class="form-select">
                @foreach(['thap' => 'Thấp', 'trung_binh' => 'Trung bình', 'cao' => 'Cao'] as $val => $label)
                    <option value="{{ $val }}" @selected(old('priority', $task->priority) === $val)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-primary">💾 Cập nhật</button>
            <a href="{{ route('admin.tasks.index') }}" class="btn btn-secondary">🔙 Quay lại</a>
        </div>
    </form>
</div>
@endsection
