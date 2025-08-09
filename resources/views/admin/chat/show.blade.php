@extends('admin.dashboard')

@section('title', 'Chi tiết Chat Session')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Chat với
                            {{ $session->user ? $session->user->full_name : ($session->visitor_name ?: 'Khách ẩn danh') }}
                        </h3>
                    </div>

                    <div class="card-body">
                        <div class="chat-box"
                            style="height: 400px; overflow-y: auto; border: 1px solid #ddd; padding: 15px; margin-bottom: 15px;">
                            @foreach ($messages as $message)
                                <div
                                    class="message mb-3 d-flex {{ $message->sender_type === 'user' ? 'justify-content-end' : 'justify-content-start' }}">
                                    <div class="message-content" style="max-width: 70%;">
                                        <div
                                            class="message-bubble p-3 rounded {{ $message->sender_type === 'user' ? 'bg-primary text-white' : 'bg-light' }}">
                                            {{ $message->message }}
                                        </div>
                                        <small class="text-muted d-block mt-1">
                                            {{ $message->sender_type === 'admin' ? 'Admin' : ($message->sender_type === 'bot' ? 'Bot' : 'Khách hàng') }}
                                            •
                                            {{ $message->created_at->format('H:i d/m') }}
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Reply Form -->
                        <form action="{{ route('admin.chat.send', $session) }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <textarea name="message" class="form-control" placeholder="Nhập phản hồi..." rows="2" required></textarea>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane"></i> Gửi
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Thông tin khách hàng</h4>
                    </div>
                    <div class="card-body">
                        @if ($session->user)
                            <p><strong>Tên:</strong> {{ $session->user->full_name }}</p>
                            <p><strong>Email:</strong> {{ $session->user->email }}</p>
                            <p><strong>Điện thoại:</strong> {{ $session->user->phone }}</p>
                        @else
                            <p><strong>Tên:</strong> {{ $session->visitor_name ?: 'N/A' }}</p>
                            <p><strong>Email:</strong> {{ $session->visitor_email ?: 'N/A' }}</p>
                            <p><strong>Điện thoại:</strong> {{ $session->visitor_phone ?: 'N/A' }}</p>
                        @endif

                        <hr>
                        <p><strong>Trạng thái:</strong>
                            <span class="badge badge-{{ $session->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($session->status) }}
                            </span>
                        </p>
                        <p><strong>Bắt đầu:</strong> {{ $session->started_at->format('H:i d/m/Y') }}</p>
                        <p><strong>Tổng tin nhắn:</strong> {{ $messages->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Auto scroll to bottom
        const chatBox = document.querySelector('.chat-box');
        chatBox.scrollTop = chatBox.scrollHeight;
    </script>
@endpush
