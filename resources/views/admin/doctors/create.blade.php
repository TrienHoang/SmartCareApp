@extends('admin.dashboard')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title">
                            <i class="fas fa-user-md"></i> Thêm bác sĩ mới
                        </h3>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-left border-danger"
                                role="alert">
                                <strong><i class="fas fa-exclamation-triangle"></i> Có lỗi xảy ra:</strong>
                                <ul class="mb-0 mt-2 pl-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show shadow-sm border-left border-success"
                                role="alert">
                                <i class="fas fa-check-circle"></i> {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                        @endif

                        <form action="{{ route('admin.doctors.store') }}" method="POST" id="doctorForm">
                            @csrf
                            <div class="form-group">
                                <label for="user_id">
                                    <i class="fas fa-user"></i> Chọn người dùng có sẵn
                                </label>
                                <select name="user_id" id="user_id"
                                    class="form-control @error('user_id') is-invalid @enderror">
                                    <option value="">-- Chọn người dùng --</option>
                                    @foreach($availableUsers as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->full_name ?? $user->username }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                <small class="form-text text-muted">Hoặc nhập tên mới bên dưới.</small>
                            </div>

                            <div class="form-group">
                                <label for="specialization">Chuyên môn <span class="text-danger">*</span></label>
                                <input type="text" name="specialization"
                                    class="form-control @error('specialization') is-invalid @enderror"
                                    value="{{ old('specialization') }}">
                                @error('specialization')
                                    <small class="text-danger d-flex align-items-center mt-1">
                                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="department_id">Phòng ban <span class="text-danger">*</span></label>
                                <select name="department_id" id="department_id"
                                    class="form-control @error('department_id') is-invalid @enderror">
                                    <option value="">-- Chọn phòng ban --</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <small class="text-danger d-flex align-items-center mt-1">
                                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="room_id">Phòng khám <span class="text-danger">*</span></label>
                                <select name="room_id" id="room_id"
                                    class="form-control @error('room_id') is-invalid @enderror">
                                    <option value="">-- Chọn phòng khám --</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                            {{ $room->name ?? $room->room_number }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('room_id')
                                    <small class="text-danger d-flex align-items-center mt-1">
                                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="biography">Tiểu sử</label>
                                <textarea name="biography" class="form-control @error('biography') is-invalid @enderror"
                                    rows="4">{{ old('biography') }}</textarea>
                                @error('biography')
                                    <small class="text-danger d-flex align-items-center mt-1">
                                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Thêm bác sĩ
                                </button>
                                <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary ml-2">
                                    <i class="fas fa-arrow-left"></i> Quay lại
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .text-danger i {
            font-size: 0.9rem;
        }

        .card {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection