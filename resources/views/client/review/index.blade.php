@extends('client.layouts.profile-layout')


@section('title', 'Danh sách đánh giá')

@section('profile-content')
<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6 gradient-text">Danh sách bình luận của bạn</h2>
    @if ($reviews->count())
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border rounded-lg">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 text-sm uppercase tracking-wider">
                        <th class="py-3 px-4 text-left">Sao đánh giá</th>
                        <th class="py-3 px-4 text-left">Thời gian</th>
                        <th class="py-3 px-4 text-left">Bác sĩ</th>
                        <th class="py-3 px-4 text-left">Bình luận</th>
                        <th class="py-3 px-4 text-left">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reviews as $review)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">
                                <div class="flex items-center">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i data-lucide="star"
                                           class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300 fill-none' }}"></i>
                                    @endfor
                                </div>
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-500">
                                {{ $review->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3 px-4 text-sm text-blue-600">
                                {{ $review->doctor->user->full_name ?? 'Không xác định' }}
                            </td>
                            <td class="py-3 px-4 text-gray-700">
                                {{ $review->comment }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center space-x-2">
                                    @if ($review->editable)
                                        <form method="GET" action="{{ url('/thong-tin-bac-si/' . $review->doctor_id) }}">
                                            <input type="hidden" name="edit_review_id" value="{{ $review->id }}">
                                            <button type="submit" class="px-3 py-1 bg-yellow-400 text-white rounded text-sm font-medium hover:bg-yellow-500">
                                                Chỉnh sửa
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('doctor.show', $review->doctor_id) }}" class="text-blue-600 hover:underline text-sm">Xem chi tiết</a>

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    @else
        <p class="text-gray-500">Bạn chưa có bình luận nào.</p>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endsection
