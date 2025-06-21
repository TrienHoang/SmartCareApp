@extends('admin.dashboard')
@section('title', 'Chi tiết Thông báo')

@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Chi tiết Thông báo</h2>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="me-1"><a href="{{ route('admin.dashboard.index') }}">Trang chủ ></a></li>
                                <li class="me-1"><a href="{{ route('admin.notifications.index') }}">Thông báo ></a></li>
                                <li class="breadcrumb-item active">Chi tiết</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- {{ dd($notification) }} --}}
        <div class="content-body">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Thông báo #{{ $notification->id }}</h4>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Tiêu đề:</dt>
                        <dd class="col-sm-9">{{ $notification->title }}</dd>

                        <dt class="col-sm-3">Nội dung:</dt>
                        <dd class="col-sm-9">{!! $notification->content !!}</dd> {{-- Hiển thị HTML --}}

                        <dt class="col-sm-3">Loại:</dt>
                        <dd class="col-sm-9">{{ ucfirst(str_replace('_', ' ', $notification->type)) }}</dd>

                        <dt class="col-sm-3">Người gửi:</dt>
                        <dd class="col-sm-9">
                            {{ $notification->sender ? $notification->sender->name : 'Hệ thống' }}
                            @if ($notification->sender_id)
                                (ID: {{ $notification->sender_id }})
                            @endif
                        </dd>

                        <dt class="col-sm-3">Người nhận:</dt>
                        <dd class="col-sm-9">
                            @if ($notification->recipient_type === 'all')
                                Tất cả người dùng
                            @elseif ($notification->recipient_type === 'specific_users' && !empty($notification->display_recipients))
                                @foreach ($notification->display_recipients as $recipient)
                                <span class="badge bg-label-info text-decoration-none mb-1 ">{{ $recipient }}</span><br>
                                @endforeach
                            @elseif ($notification->recipient_type === 'roles' && !empty($notification->display_recipients))
                                @foreach ($notification->display_recipients as $recipient)
                                <span class="badge bg-label-info text-decoration-none mb-1 ">{{ $recipient }}</span><br>
                                @endforeach
                            @else
                                Không xác định
                            @endif
                        </dd>

                        <dt class="col-sm-3">Trạng thái:</dt>
                        <dd class="col-sm-9">
                            <span
                            class="badge
                            @if ($notification->status == 'sent') text-bg-success
                            @elseif ($notification->status == 'scheduled') text-bg-info 
                            @elseif ($notification->status == 'sending') text-bg-warning
                            @elseif ($notification->status == 'failed') text-bg-danger
                            @else text-bg-dark @endif">
                            {{ ucfirst($notification->status) }}
                        </span>
                        </dd>

                        <dt class="col-sm-3">Thời gian lên lịch:</dt>
                        <dd class="col-sm-9">
                            {{ $notification->scheduled_at ? $notification->scheduled_at->format('d/m/Y H:i') : 'Không có' }}
                        </dd>

                        <dt class="col-sm-3">Thời gian gửi:</dt>
                        <dd class="col-sm-9">
                            {{ $notification->sent_at ? $notification->sent_at->format('d/m/Y H:i') : 'Chưa gửi' }}</dd>

                        <dt class="col-sm-3">Ngày tạo:</dt>
                        <dd class="col-sm-9">{{ $notification->created_at->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-3">Cập nhật cuối:</dt>
                        <dd class="col-sm-9">{{ $notification->updated_at->format('d/m/Y H:i') }}</dd>
                    </dl>

                    <a href="{{ route('admin.notifications.index') }}"
                        class="btn btn-secondary waves-effect waves-light mt-3">Quay lại danh sách</a>
                </div>
            </div>
        </div>
    </div>
@endsection
