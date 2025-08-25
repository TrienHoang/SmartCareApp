@extends('admin.dashboard')

@section('content')
<div class="container mt-4">
    <h2>🔍 Chi tiết công việc</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Thông tin công việc --}}
    <div class="card mb-4 shadow">
        <div class="card-header bg-primary text-white">
            <strong>{{ $task->title }}</strong>
        </div>
        <div class="card-body">
            <p><strong>Mô tả:</strong> {{ $task->description ?: 'Không có' }}</p>
            <p><strong>Trạng thái:</strong> 
                @switch($task->status)
                    @case('moi_tao') <span class="badge bg-secondary">Mới tạo</span> @break
                    @case('dang_lam') <span class="badge bg-warning text-dark">Đang làm</span> @break
                    @case('hoan_thanh') <span class="badge bg-success">Hoàn thành</span> @break
                    @case('tre_han') <span class="badge bg-danger">Trễ hạn</span> @break
                    @default {{ $task->status }}
                @endswitch
            </p>
            <p><strong>Deadline:</strong> 
                {{ $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('d/m/Y H:i') : 'Không có' }}
            </p>
            <p><strong>Người được giao:</strong> {{ $task->assignedUser->full_name ?? 'Chưa được gán' }}</p>
            <p><strong>Người tạo:</strong> {{ $task->createdBy->full_name ?? 'Không xác định' }}</p>
            <p><strong>Ưu tiên:</strong> 
                @switch($task->priority)
                    @case('thap') <span class="badge bg-info">Thấp</span> @break
                    @case('trung_binh') <span class="badge bg-secondary">Trung bình</span> @break
                    @case('cao') <span class="badge bg-danger">Cao</span> @break
                    @default {{ $task->priority }}
                @endswitch
            </p>
        </div>
    </div>

    {{-- 💬 Bình luận --}}
    <div class="card mb-4">
        <div class="card-header bg-light"><strong>💬 Bình luận gần đây</strong></div>
        <ul class="list-group list-group-flush">
            @forelse($comments as $comment)
                <li class="list-group-item">
                    <strong>{{ $comment->user->full_name ?? 'Ẩn danh' }}</strong>:
                    {{ $comment->comment }}
                    <br>
                    <small class="text-muted">{{ $comment->created_at->format('d/m/Y H:i') }}</small>
                </li>
            @empty
                <li class="list-group-item text-muted">Chưa có bình luận nào.</li>
            @endforelse
        </ul>
        @if($totalComments > 5)
            <div class="card-footer text-end">
                <a href="#" class="btn btn-sm btn-outline-primary">📄 Xem tất cả bình luận</a>
            </div>
        @endif
    </div>

    {{-- Form bình luận --}}
    <form method="POST" action="{{ route('admin.tasks.comment', $task->id) }}" class="card mb-4 shadow-sm">
        @csrf
        <div class="card-body">
            <label for="comment" class="form-label">Thêm bình luận</label>
            <textarea name="comment" id="comment" rows="3" class="form-control" required></textarea>
        </div>
        <div class="card-footer text-end">
            <button class="btn btn-primary">💬 Gửi bình luận</button>
        </div>
    </form>

    {{-- 🕒 Lịch sử thay đổi trạng thái --}}
    <div class="card mb-4">
        <div class="card-header bg-light"><strong>🕒 Lịch sử trạng thái</strong></div>
        <ul class="list-group list-group-flush">
            @forelse($task->logs as $log)
                <li class="list-group-item">
                    <strong>{{ $log->user->full_name ?? 'Ẩn danh' }}</strong> đã đổi trạng thái 
                    <em>{{ $log->from_status }}</em> → <em>{{ $log->to_status }}</em> 
                    lúc {{ \Carbon\Carbon::parse($log->changed_at)->format('d/m/Y H:i') }}
                </li>
            @empty
                <li class="list-group-item text-muted">Chưa có lịch sử trạng thái.</li>
            @endforelse
        </ul>
    </div>

    <a href="{{ route('admin.tasks.index') }}" class="btn btn-secondary">🔙 Quay lại</a>
</div>
@endsection
