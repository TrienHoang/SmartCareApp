@extends('admin.dashboard')
@section('title', 'Service Category Details')
@section('content')
    <div class="container">
        <h1 class="mb-4">Service Category Details</h1>
        <div class="mb-3">
            <label class="form-label"><strong>Category Name:</strong></label>
            <div class="form-control-plaintext">{{ $serviceCategory->name }}</div>
        </div>
        <div class="mb-3">
            <label class="form-label"><strong>Description:</strong></label>
            <div class="form-control-plaintext">{{ $serviceCategory->description }}</div>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Back to List</a>
        <a href="{{ route('admin.categories.edit', $serviceCategory->id) }}" class="btn btn-primary">Edit</a>
    </div>
@endsection
