@extends('admin.dashboard')
@section('content')
    <h2>Sửa Dịch Vụ</h2>
    <form method="POST" action="{{ route('admin.services.update', $service) }}">
        @csrf @method('PUT')
        @include('admin.services.form', ['service' => $service])
        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
@endsection
