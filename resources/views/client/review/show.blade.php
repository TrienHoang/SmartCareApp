@extends('client.layouts.app')

@section('content')
    <div class="container">
        <!-- Thông tin bác sĩ -->
        <div class="doctor-info">
            <h1>{{ $doctor->user->full_name }}</h1>
            <p>Điểm đánh giá trung bình: {{ number_format($averageRating, 1) }} / 5 ({{ $reviewCount }} đánh giá)</p>
        </div>

        <!-- Bình luận của người dùng hiện tại -->
        @if ($userReview)
            <div class="user-review">
                <h3>Bình luận của bạn</h3>
                <div class="review">
                    <p><strong>{{ $userReview->patient->name }}</strong> - {{ $userReview->rating }} sao</p>
                    <p>{{ $userReview->comment }}</p>
                    <p><small>Đăng vào: {{ $userReview->created_at->format('d/m/Y H:i') }}</small></p>
                </div>
            </div>
        @else
            <p>Bạn chưa có bình luận nào cho bác sĩ này. <a href="{{ route('client.review.create', $doctor->id) }}">Viết bình luận</a></p>
        @endif
@endsection
