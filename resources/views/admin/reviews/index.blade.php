@extends('admin.dashboard')

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4">Danh sách đánh giá</h1>

        <form method="GET" action="{{ route('admin.reviews.index') }}" class="mb-3 d-flex">
            <input type="text" name="keyword" class="form-control me-2" placeholder="Tìm nội dung..."
                value="{{ request('keyword') }}">
            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
        </form>

        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Bệnh nhân</th>
                    <th>Bác sĩ</th>
                    <th>Dịch vụ</th>
                    <th>Điểm</th>
                    <th>Ẩn/Hiện</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reviews as $review)
                    <tr>
                        <td>{{ $review->id }}</td>
                        <td>{{ $review->patient->full_name ?? '---' }}</td>
                        <td>{{ $review->doctor->user->full_name ?? '---' }}</td>
                        <td>{{ $review->service->name ?? '---' }}</td>
                        <td>
                            <span class="text-warning">
                                @for ($i = 0; $i < $review->rating; $i++)
                                    ★
                                @endfor
                                @for ($i = $review->rating; $i < 5; $i++)
                                    ☆
                                @endfor
                            </span>
                            ({{ $review->rating }})
                        </td>
                        <td>
                            @if ($review->is_visible)
                                <span class="badge bg-success">Hiện</span>
                            @else
                                <span class="badge bg-secondary">Ẩn</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.reviews.show', $review->id) }}" class="btn btn-sm btn-info me-2">
                                Chi tiết
                            </a>
                            <form method="POST" action="{{ route('admin.reviews.toggle', $review->id) }}" class="d-inline">
                                @csrf
                                <button type="submit"
                                    class="btn btn-sm {{ $review->is_visible ? 'btn-warning' : 'btn-success' }}"
                                    onclick="return confirm('Bạn có chắc muốn thay đổi trạng thái?')">
                                    {{ $review->is_visible ? 'Ẩn' : 'Hiện' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">Không có đánh giá nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $reviews->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
