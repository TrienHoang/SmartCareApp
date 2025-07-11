@extends('admin.dashboard')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">📢 Danh sách thông báo hệ thống</h4>

    {{-- Bộ lọc --}}
    <form method="GET" action="{{ route('admin.system_notifications.index') }}" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tiêu đề hoặc nội dung..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">-- Tất cả trạng thái --</option>
                @foreach($notificationStatuses ?? [] as $status)
                    <option value="{{ $status }}" @selected(request('status') == $status)>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="type" class="form-select">
                <option value="">-- Tất cả loại --</option>
                @foreach($notificationTypes ?? [] as $type)
                    <option value="{{ $type }}" @selected(request('type') == $type)>
                        {{ ucfirst($type) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button class="btn btn-outline-primary">Lọc</button>
            <a href="{{ route('admin.system_notifications.index') }}" class="btn btn-outline-secondary">Đặt lại</a>
        </div>
    </form>

    {{-- Thống kê nhanh --}}
    <div class="mb-3">
        <strong>Thống kê:</strong>
        <span class="badge bg-success">Đã gửi: {{ $statusCounts['sent'] ?? 0 }}</span>
        <span class="badge bg-warning text-dark">Hẹn giờ: {{ $statusCounts['scheduled'] ?? 0 }}</span>
        <span class="badge bg-info text-dark">Đang gửi: {{ $statusCounts['sending'] ?? 0 }}</span>
        <span class="badge bg-danger">Lỗi: {{ $statusCounts['failed'] ?? 0 }}</span>
    </div>

    {{-- Bảng danh sách --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>Tiêu đề</th>
                    <th>Nội dung</th>
                    <th>Loại</th>
                    <th>Người nhận</th>
                    <th>Thời gian gửi</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($notifications as $noti)
                    <tr>
                        <td>{{ $noti->title }}</td>
                        <td>{!! \Illuminate\Support\Str::limit(strip_tags($noti->content), 60) !!}</td>
                        <td><span class="badge bg-secondary">{{ $noti->type }}</span></td>
                        <td>
                            @if(!empty($noti->display_recipients) && is_array($noti->display_recipients))
                                <ul class="mb-0 ps-3">
                                    @foreach($noti->display_recipients as $name)
                                        <li>{{ $name }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <em>Không xác định</em>
                            @endif
                        </td>
                        <td>{{ $noti->sent_at ? $noti->sent_at->format('d/m/Y H:i:s') : '—' }}</td>
                        <td>
                            <span class="badge 
                                @if($noti->status === 'sent') bg-success
                                @elseif($noti->status === 'scheduled') bg-warning text-dark
                                @elseif($noti->status === 'sending') bg-info text-dark
                                @elseif($noti->status === 'failed') bg-danger
                                @else bg-secondary
                                @endif">
                                {{ ucfirst($noti->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Không có thông báo nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Phân trang --}}
    <div class="mt-3">
        {{ $notifications->withQueryString()->links() }}
    </div>
</div>
@endsection
