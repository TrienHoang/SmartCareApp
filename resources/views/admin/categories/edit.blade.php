@extends('admin.dashboard')
@section('title', 'Edit Service Category')
@section('content')
    <div class="container">
        <h1 class="mb-4">Edit Service Category</h1>
        <form action="{{ route('admin.categories.update', $serviceCategory->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Tên danh mục</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                    value="{{ old('name', $serviceCategory->name) }}">
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3">{{ $serviceCategory->description }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Category</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Back to List</a>
        </form>
    </div>
@endsection
