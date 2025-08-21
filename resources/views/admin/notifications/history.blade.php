@extends('admin.dashboard')

@section('title', 'Lịch sử thông báo')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Lịch sử thông báo</h1>

        @if ($notifications->count() > 0)
            <table class="min-w-full bg-white border rounded-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-2 px-4 border-b">Thời gian</th>
                        <th class="py-2 px-4 border-b">Nội dung</th>
                        <th class="py-2 px-4 border-b">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($notifications as $notification)
                        <tr class="{{ $notification->read_at ? '' : 'bg-yellow-50' }}">
                            <td class="py-2 px-4 border-b">{{ $notification->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-2 px-4 border-b">{{ $notification->data['content'] ?? 'N/A' }}</td>
                            <td class="py-2 px-4 border-b">
                                @if ($notification->read_at)
                                    <span class="text-green-600 font-semibold">Đã đọc</span>
                                @else
                                    <span class="text-red-600 font-semibold">Chưa đọc</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $notifications->links() }}
            </div>
        @else
            <p>Không có thông báo nào.</p>
        @endif
    </div>
@endsection
