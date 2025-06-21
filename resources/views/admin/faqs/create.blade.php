@extends('admin.dashboard')
@section('content')
    <div class="container mt-4">
        <h2>Thêm Câu hỏi Thường Gặp</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.faqs.store') }}" method="POST">
            @csrf
            @include('admin.faqs._form', ['faq' => null])
            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
