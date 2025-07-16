@extends('admin.dashboard')

@section('content')
<div class="container">
    <h2>{{ $post->title }}</h2>

    @if($post->thumbnail)
        <img src="{{ asset('storage/' . $post->thumbnail) }}" style="max-width: 300px;" class="mb-3">
    @endif

    <p><strong>Danh mục:</strong> {{ $post->category->name ?? '(Không có)' }}</p>
    <p><strong>Trạng thái:</strong> {{ ucfirst($post->status) }}</p>
    <p><strong>Ngày tạo:</strong> {{ $post->created_at->format('d/m/Y') }}</p>
    <hr>
    <div>{!! nl2br(e($post->content)) !!}</div>
</div>
@endsection
