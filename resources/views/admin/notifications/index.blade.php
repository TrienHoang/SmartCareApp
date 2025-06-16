@extends('admin.dashboard')

@section('title', 'Quản lý Thông báo') 

@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Quản lý Thông báo</h2>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="me-1"><a href="{{ route('admin.dashboard') }}">Trang chủ  >   </a></li>
                                <li class="breadcrumb-item active ">Thông báo</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
                <div class="form-group breadcrum-right">
                    <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary waves-effect waves-light">
                        <i class="feather icon-plus"></i> Tạo thông báo mới
                    </a>
                </div>
            </div>
        </div>

        <div class="content-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Danh sách Thông báo</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.notifications.index') }}" method="GET" class="form-inline mb-4">
                        <div class="form-group mr-1 mb-1">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tiêu đề, nội dung..." value="{{ request('search') }}">
                        </div>
                        <div class="form-group mr-1 mb-1">
                            <select name="type" class="form-control">
                                <option value="">Tất cả loại</option>
                                @foreach($notificationTypes as $type)
                                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $type)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mr-1 mb-1">
                            <select name="status" class="form-control">
                                <option value="">Tất cả trạng thái</option>
                                @foreach($notificationStatuses as $status)
                                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-info mb-1 mt-1 p-2">Lọc</button>
                        <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary mb-1 mt-1 p-2">Reset</a>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tiêu đề</th>
                                    <th>Loại</th>
                                    <th>Người nhận</th>
                                    <th>Trạng thái</th>
                                    <th>Lên lịch/Gửi lúc</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($notifications as $notification)
                                    <tr>
                                        <td>{{ $notification->id }}</td>
                                        <td>{{ Str::limit($notification->title, 50) }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $notification->type)) }}</td>
                                        <td>
                                            @if ($notification->recipient_type == 'all')
                                                Tất cả người dùng
                                            @elseif ($notification->recipient_type == 'specific_users')
                                                Người dùng cụ thể ({{ count($notification->recipient_data ?? []) }})
                                            @elseif ($notification->recipient_type == 'roles')
                                                Vai trò: {{ implode(', ', array_map('ucfirst', $notification->recipient_data ?? [])) }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge
                                                @if ($notification->status == 'sent') bg-label-success
                                                @elseif ($notification->status == 'scheduled') badge-light-info
                                                @elseif ($notification->status == 'sending') badge-light-warning
                                                @elseif ($notification->status == 'failed') badge-light-danger
                                                @else badge-light-secondary @endif">
                                                {{ ucfirst($notification->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($notification->sent_at)
                                                Đã gửi: {{ $notification->sent_at->format('d/m/Y H:i') }}
                                            @elseif ($notification->scheduled_at)
                                                Lên lịch: {{ $notification->scheduled_at->format('d/m/Y H:i') }}
                                            @else
                                                Chưa gửi
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Hành động">
                                                <a href="{{ route('admin.notifications.show', $notification) }}" class="btn btn-sm btn-info me-1" data-toggle="tooltip" data-placement="top" title="Xem chi tiết">Xem</a>
                                                @if ($notification->status != 'sent' && $notification->status != 'sending')
                                                    <a href="{{ route('admin.notifications.edit', $notification) }}" class="btn btn-sm btn-warning me-1" data-toggle="tooltip" data-placement="top" title="Chỉnh sửa">Sửa</i></a>
                                                    @if ($notification->status != 'scheduled')
                                                        <form action="{{ route('admin.notifications.sendNow', $notification) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-primary me-1" data-toggle="tooltip" data-placement="top" title="Gửi ngay" onclick="return confirm('Bạn có chắc chắn muốn gửi thông báo này ngay lập tức?')">Gửi</i></button>
                                                        </form>
                                                    @endif
                                                @endif
                                                <form action="{{ route('admin.notifications.destroy', $notification) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger me-1" data-toggle="tooltip" data-placement="top" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa thông báo này? Thông báo đã gửi sẽ không thể khôi phục!')">Xóa</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Không có thông báo nào được tìm thấy.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $notifications->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection