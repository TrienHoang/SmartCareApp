@extends('reception.dashboard')

@section('title', 'Quản lý Chat')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Danh sách Chat Sessions</h3>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Khách hàng</th>
                                        <th>Tin nhắn cuối</th>
                                        <th>Trạng thái</th>
                                        <th>Bắt đầu</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sessions as $key => $session)
                                        <tr>
                                            <td>{{ $loop->iteration + ($sessions->currentPage() - 1) * $sessions->perPage() }}
                                            </td>
                                            <td>
                                                @if ($session->user)
                                                    <strong>{{ $session->user->full_name }}</strong><br>
                                                    <small class="text-muted">{{ $session->user->email }}</small>
                                                @else
                                                    <strong>{{ $session->visitor_name ?: 'Khách ẩn danh' }}</strong><br>
                                                    <small class="text-muted">{{ $session->visitor_email }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($session->latestMessage)
                                                    <div class="text-truncate" style="max-width: 200px;">
                                                        {{ Str::limit($session->latestMessage->message, 50) }}
                                                    </div>
                                                    <small
                                                        class="text-muted">{{ $session->latestMessage->created_at->diffForHumans() }}</small>
                                                @else
                                                    <span class="text-muted">Chưa có tin nhắn</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $session->status === 'active' ? 'success' : ($session->status === 'waiting' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($session->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $session->started_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('receptionist.chat.show', $session->id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> Xem
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{ $sessions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
