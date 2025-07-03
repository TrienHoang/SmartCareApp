@extends('client.layouts.app')

@section('title', 'Danh sách tài liệu Y tế')

@section('content')
    <h2 class="text-2xl font-semibold mb-4">Tài liệu đã tải lên</h2>
    
    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Nút tạo mới tài liệu -->
    <div class="mb-4">
        <a href="{{ route('client.uploads.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            + Tạo mới tài liệu
        </a>
    </div>

    <table class="min-w-full bg-white shadow rounded">
        <thead>
            <tr>
                <th class="text-left p-3">Tên File</th>
                <th class="text-left p-3">Loại</th>
                <th class="text-left p-3">Kích thước</th>
                <th class="text-left p-3">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($files as $file)
                <tr class="border-t">
                    <td class="p-3">{{ $file->file_name }}</td>
                    <td class="p-3">{{ $file->file_category }}</td>
                    <td class="p-3">{{ number_format($file->size / 1024, 2) }} KB</td>
                    <td class="p-3">
                        <a href="{{ asset('storage/' . $file->file_path) }}" download class="text-blue-600 hover:underline">
                            Tải xuống
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
