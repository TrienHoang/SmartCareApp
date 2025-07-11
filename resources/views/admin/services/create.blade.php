@extends('admin.dashboard')
@section('content')
    <h2>Thêm Dịch Vụ</h2>
    <form method="POST" action="{{ route('admin.services.store') }}">
        @csrf
        @include('admin.services.form')
        <button type="submit" class="btn btn-primary">Lưu</button>
    </form>
@endsection
