@extends('doctor.dashboard')

@section('title', 'Đánh giá từ bệnh nhân')

@section('content')
    <div class="container mt-4">
        <h3>Đánh giá của bạn</h3>

        @forelse ($reviews as $review)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Dịch vụ: {{ $review->service->name ?? 'N/A' }}</h5>
                    <p class="card-text">
                        <strong>Nội dung đánh giá:</strong><br>
                        {{ $review->comment }}
                    </p>
                    <p class="text-muted mb-0">
                        <small>Thời gian: {{ $review->created_at->format('d/m/Y H:i') }}</small>
                    </p>
                </div>
            </div>
        @empty
            <p>Không có đánh giá nào.</p>
        @endforelse

        <div class="mt-3">
            {{ $reviews->links() }}
        </div>
    </div>
@endsection
