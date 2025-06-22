@extends('admin.dashboard')
@section('content')
    <div class="container mt-4">
        <h2>Chỉnh sửa Câu hỏi</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.faqs.update', $faq->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.faqs._form', ['faq' => $faq])
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
