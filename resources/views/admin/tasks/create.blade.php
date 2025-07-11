@extends('admin.dashboard')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">📝 Tạo công việc mới</h2>

    {{-- Thông báo lỗi tổng quát --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Đã xảy ra lỗi!</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Lỗi ngoài giờ hành chính --}}
    @if ($errors->has('outside_hours'))
        <div class="alert alert-danger">
            <strong>⏰ Lỗi thời gian:</strong> {{ $errors->first('outside_hours') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.tasks.store') }}">
        @csrf

        {{-- Tiêu đề --}}
        <div class="mb-3">
            <label class="form-label">📌 Tiêu đề <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title') }}" placeholder="Nhập tiêu đề công việc" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Mô tả --}}
        <div class="mb-3">
            <label class="form-label">📝 Mô tả</label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                      rows="3" placeholder="Nhập mô tả công việc">{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Hạn chót --}}
        <div class="mb-3">
            <label class="form-label">📅 Hạn chót</label>
            <input type="datetime-local" name="deadline" class="form-control @error('deadline') is-invalid @enderror"
                   value="{{ old('deadline') }}">
            @error('deadline')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Giao cho bác sĩ --}}
        <div class="mb-3">
            <label class="form-label">👨‍⚕️ Giao cho bác sĩ <span class="text-danger">*</span></label>
            <select name="assigned_to[]" multiple class="form-select @error('assigned_to') is-invalid @enderror">
                @foreach($users as $user)
                    <option value="{{ $user->id }}"
                        @selected(collect(old('assigned_to'))->contains($user->id))>
                        {{ $user->full_name }} @if($user->doctor) - {{ $user->doctor->specialization }} @endif
                    </option>
                @endforeach
            </select>
            @error('assigned_to')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Mức độ ưu tiên --}}
        <div class="mb-3">
            <label class="form-label">⚠️ Mức độ ưu tiên <span class="text-danger">*</span></label>
            <select name="priority" class="form-select @error('priority') is-invalid @enderror">
                <option value="">-- Chọn mức độ --</option>
                @foreach(['thap' => 'Thấp', 'trung_binh' => 'Trung bình', 'cao' => 'Cao'] as $key => $label)
                    <option value="{{ $key }}" @selected(old('priority') == $key)>{{ $label }}</option>
                @endforeach
            </select>
            @error('priority')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Nút hành động --}}
        <div class="d-flex gap-2">
            <button class="btn btn-success">💾 Lưu công việc</button>
            <a href="{{ route('admin.tasks.index') }}" class="btn btn-secondary">Huỷ</a>
        </div>
    </form>
</div>
@endsection
