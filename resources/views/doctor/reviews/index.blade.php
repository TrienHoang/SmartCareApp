@extends('doctor.dashboard')

@section('title', 'Đánh giá từ bệnh nhân')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Đánh giá từ bệnh nhân</h2>

    @if ($reviews->isEmpty())
        <div class="alert alert-info">Chưa có đánh giá nào.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Bệnh nhân</th>
                        <th>Dịch vụ</th>
                        <th>Đánh giá</th>
                        <th>Bình luận</th>
                        <th>Thời gian</th>
                        <th>Hiển thị</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reviews as $index => $review)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $review->patient->full_name ?? '---' }}</td>
                            <td>{{ $review->service->name ?? '---' }}</td>
                            <td>
                                ⭐ {{ $review->rating }}/5
                            </td>
                            <td>{{ $review->comment }}</td>
                            <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if ($review->is_visible)
                                    <span class="badge bg-success">Hiển thị</span>
                                @else
                                    <span class="badge bg-secondary">Đã ẩn</span>
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('doctor.reviews.toggle', $review->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-outline-primary">
                                        {{ $review->is_visible ? 'Ẩn' : 'Hiện' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
