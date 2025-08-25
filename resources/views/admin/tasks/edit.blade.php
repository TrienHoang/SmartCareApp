@extends('admin.dashboard')

@section('content')
    <div class="container mt-4">
        <h2>Chỉnh sửa công việc</h2>

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
                <strong>Lỗi:</strong> {{ $errors->first('outside_hours') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.tasks.update', $task->id) }}">
            @csrf
            @method('PUT')

            {{-- Tiêu đề --}}
            <div class="mb-3">
                <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                    value="{{ old('title', $task->title) }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Mô tả --}}
            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea name="description" rows="3"
                    class="form-control @error('description') is-invalid @enderror">{{ old('description', $task->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Hạn chót --}}
            <div class="mb-3">
                <label class="form-label">Hạn chót</label>
                <input type="datetime-local" name="deadline" class="form-control @error('deadline') is-invalid @enderror"
                    value="{{ old('deadline', $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('Y-m-d\TH:i') : '') }}">
                @error('deadline')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Giao cho bác sĩ (nhiều người) --}}
            <div class="mb-3">
                <label class="form-label">Giao cho bác sĩ <span class="text-danger">*</span></label>

                {{-- Hiển thị danh sách bác sĩ đã được giao --}}
                @if ($task->assignedUsers->count())
                    <div class="mb-2">
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($task->assignedUsers as $doctor)
                                <span class="badge bg-info text-dark">
                                    {{ $doctor->full_name }}
                                    @if ($doctor->doctor && $doctor->doctor->specialization)
                                        - {{ $doctor->doctor->specialization }}
                                    @endif
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Select danh sách bác sĩ --}}
                <select name="assigned_to[]" multiple class="form-select @error('assigned_to') is-invalid @enderror">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @if (collect(old('assigned_to', $task->assignedUsers->pluck('id')->toArray()))->contains($user->id)) selected @endif>
                            {{ $user->full_name }} - {{ $user->doctor->specialization ?? 'Chưa rõ chuyên môn' }}
                        </option>
                    @endforeach
                </select>

                @error('assigned_to')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            {{-- Trạng thái --}}
            <div class="mb-3">
                <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                <select name="status" class="form-select @error('status') is-invalid @enderror">
                    @foreach(['moi_tao' => 'Mới tạo', 'dang_lam' => 'Đang làm', 'hoan_thanh' => 'Hoàn thành', 'tre_han' => 'Trễ hạn'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('status', $task->status) === $value)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Mức độ ưu tiên --}}
            <div class="mb-3">
                <label class="form-label">Mức độ ưu tiên <span class="text-danger">*</span></label>
                <select name="priority" class="form-select @error('priority') is-invalid @enderror">
                    @foreach(['thap' => 'Thấp', 'trung_binh' => 'Trung bình', 'cao' => 'Cao'] as $key => $label)
                        <option value="{{ $key }}" @selected(old('priority', $task->priority) === $key)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('priority')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Hành động --}}
            <div class="d-flex gap-2">
                <button class="btn btn-success">💾 Cập nhật</button>
                <a href="{{ route('admin.tasks.index') }}" class="btn btn-secondary">Huỷ</a>
            </div>
        </form>
    </div>
@endsection