@extends('admin.dashboard')

@section('content')
    <h2>Chi tiết đánh giá #{{ $review->id }}</h2>

    <p><strong>Bệnh nhân:</strong> {{ $review->patient->full_name ?? '---' }}</p>
    <p><strong>Bác sĩ:</strong> {{ $review->doctor->user->full_name ?? '---' }}</p>
    <p><strong>Dịch vụ:</strong> {{ $review->service->name ?? '---' }}</p>
    <p><strong>Điểm:</strong> {{ $review->rating }} ⭐</p>
    <p><strong>Nội dung:</strong> {{ $review->comment }}</p>
    <p><strong>Trạng thái:</strong> {{ $review->is_visible ? 'Hiện' : 'Ẩn' }}</p>
    <p><strong>Ngày đánh giá:</strong> {{ $review->created_at->format('d/m/Y H:i') }}</p>

    <a href="{{ route('admin.reviews.index') }}">← Quay lại</a>
@endsection
