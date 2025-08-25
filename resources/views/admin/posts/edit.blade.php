@extends('admin.dashboard')

@section('title', 'Chỉnh sửa bài viết')

@section('content')
    <div class="content-wrapper">
        <!-- Enhanced Header Section -->
        <div class="content-header row">
            <div class="content-header-left col-md-8 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-circle bg-primary mr-3">
                                <i class="bx bx-edit text-white"></i>
                            </div>
                            <div>
                                <h2 class="content-header-title mb-0 text-primary font-weight-bold">Chỉnh sửa bài viết</h2>
                                <p class="text-muted mb-0">Cập nhật thông tin bài viết trong hệ thống</p>
                            </div>
                        </div>
                        <div class="breadcrumb-wrapper col-12">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0">
                                    <li class="">
                                        <a href="{{ route('admin.dashboard.index') }}" class="text-decoration-none">
                                            Trang chủ >
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.posts.index') }}" class="text-decoration-none">
                                            Bài viết >
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active text-primary font-weight-semibold">
                                        Chỉnh sửa
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <!-- Enhanced Alert Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-check-circle mr-2"></i>
                        <strong>Thành công! </strong> {{ session('success') }}
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-x-circle mr-2"></i>
                        <strong>Lỗi! </strong> {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- Main Form Card -->
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-primary text-white border-0">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-edit mr-2"></i>
                        <h4 class="card-title mb-0 text-white font-weight-bold">Thông tin bài viết</h4>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data"
                        class="form-modern">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-lg-8">
                                <!-- Title Field -->
                                <div class="form-group mb-4">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-edit mr-2 text-primary"></i>Tiêu đề bài viết
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="title" value="{{ old('title', $post->title) }}"
                                        class="form-control form-control-lg @error('title') is-invalid @enderror"
                                        placeholder="Nhập tiêu đề bài viết...">
                                    @error('title')
                                        <div class="invalid-feedback">
                                            <i class="bx bx-error-circle mr-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Category Field -->
                                <div class="form-group mb-4">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-category mr-2 text-info"></i>Danh mục dịch vụ
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="service_cate_id"
                                        class="form-control custom-select @error('service_cate_id') is-invalid @enderror">
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach ($categories as $cate)
                                            <option value="{{ $cate->id }}"
                                                {{ old('service_cate_id', $post->service_cate_id) == $cate->id ? 'selected' : '' }}>
                                                {{ $cate->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('service_cate_id')
                                        <div class="invalid-feedback">
                                            <i class="bx bx-error-circle mr-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Excerpt Field -->
                                {{-- <div class="form-group mb-4">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-text mr-2 text-warning"></i>Mô tả ngắn
                                    </label>
                                    <textarea name="excerpt" rows="3" 
                                        class="form-control @error('excerpt') is-invalid @enderror"
                                        placeholder="Nhập mô tả ngắn cho bài viết...">{{ old('excerpt', $post->excerpt) }}</textarea>
                                    @error('excerpt') 
                                        <div class="invalid-feedback">
                                            <i class="bx bx-error-circle mr-1"></i>{{ $message }}
                                        </div> 
                                    @enderror
                                    <small class="form-text text-muted">
                                        <i class="bx bx-info-circle mr-1"></i>Mô tả ngắn sẽ được hiển thị trong danh sách bài viết
                                    </small>
                                </div> --}}

                                <!-- Content Field -->
                                <div class="form-group mb-4">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-detail mr-2 text-success"></i>Nội dung bài viết
                                        <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="content" rows="6" id="content-editor" class="form-control @error('content') is-invalid @enderror"
                                        placeholder="Nhập nội dung bài viết...">{{ old('content', $post->content) }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">
                                            <i class="bx bx-error-circle mr-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-lg-4">
                                <!-- Status Card -->
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-light border-0">
                                        <h6 class="card-title mb-0 font-weight-semibold">
                                            <i class="bx bx-cog mr-2 text-primary"></i>Cài đặt bài viết
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <!-- Status Field -->
                                        <div class="form-group mb-3">
                                            <label class="form-label font-weight-semibold">
                                                <i class="bx bx-activity mr-2 text-success"></i>Trạng thái
                                            </label>
                                            <select name="status"
                                                class="form-control custom-select @error('status') is-invalid @enderror">
                                                <option value="draft"
                                                    {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>
                                                    📝 Nháp
                                                </option>
                                                <option value="published"
                                                    {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>
                                                    ✅ Xuất bản
                                                </option>
                                                <option value="archived"
                                                    {{ old('status', $post->status) == 'archived' ? 'selected' : '' }}>
                                                    📦 Lưu trữ
                                                </option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">
                                                    <i class="bx bx-error-circle mr-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Current Image Display Card -->
                                @if ($post->thumbnail)
                                    <div class="card border-0 shadow-sm mb-4">
                                        <div class="card-header bg-light border-0">
                                            <h6 class="card-title mb-0 font-weight-semibold">
                                                <i class="bx bx-image-alt mr-2 text-success"></i>Ảnh hiện tại
                                            </h6>
                                        </div>
                                        <div class="card-body text-center">
                                            <img src="{{ asset('storage/' . $post->thumbnail) }}"
                                                class="img-fluid rounded shadow-sm"
                                                style="max-height: 200px; object-fit: cover;" alt="Ảnh hiện tại">
                                            <div class="mt-2">
                                                <span class="badge badge-success">
                                                    <i class="bx bx-check mr-1"></i>Đã có ảnh
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Image Upload Card -->
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-light border-0">
                                        <h6 class="card-title mb-0 font-weight-semibold">
                                            <i
                                                class="bx bx-image mr-2 text-warning"></i>{{ $post->thumbnail ? 'Thay đổi ảnh đại diện' : 'Ảnh đại diện' }}
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-0">
                                            <div class="custom-file">
                                                <input type="file" name="thumbnail" id="thumbnail"
                                                    class="custom-file-input @error('thumbnail') is-invalid @enderror"
                                                    accept="image/*">
                                                <label class="custom-file-label"
                                                    for="thumbnail">{{ $post->thumbnail ? 'Chọn ảnh mới...' : 'Chọn ảnh...' }}</label>
                                            </div>
                                            @error('thumbnail')
                                                <div class="invalid-feedback d-block">
                                                    <i class="bx bx-error-circle mr-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                <i class="bx bx-info-circle mr-1"></i>Định dạng: JPG, JPEG, PNG. Tối đa 2MB
                                                @if ($post->thumbnail)
                                                    <br><i class="bx bx-info-circle mr-1"></i>Để trống nếu không muốn thay
                                                    đổi
                                                @endif
                                            </small>

                                            <!-- Image Preview -->
                                            <div id="image-preview" class="mt-3" style="display: none;">
                                                <img id="preview-img" src="" class="img-fluid rounded shadow-sm"
                                                    style="max-height: 200px;">
                                                <button type="button" id="remove-image"
                                                    class="btn btn-sm btn-outline-danger mt-2">
                                                    <i class="bx bx-trash mr-1"></i>Xóa ảnh
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block mb-2">
                                            <i class="bx bx-save mr-2"></i>Cập nhật bài viết
                                        </button>
                                        <a href="{{ route('admin.posts.index') }}"
                                            class="btn btn-outline-secondary btn-block">
                                            <i class="bx bx-arrow-back mr-2"></i>Quay lại danh sách
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .icon-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-modern .form-group {
            position: relative;
        }

        .form-control:focus,
        .custom-select:focus,
        .custom-file-input:focus~.custom-file-label {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .form-control-lg {
            font-size: 1.1rem;
            padding: 0.75rem 1rem;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card {
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: translateY(-1px);
        }

        .custom-file-label {
            cursor: pointer;
        }

        .custom-file-label::after {
            content: "Duyệt";
        }

        #image-preview {
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .alert {
            border-radius: 10px;
        }

        .badge {
            font-size: 0.85em;
        }

        @media (max-width: 768px) {
            .col-lg-4 {
                margin-top: 2rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
    <script>
        // Initialize CKEditor
        CKEDITOR.replace('content-editor', {
            height: 300,
            toolbar: [{
                    name: 'basicstyles',
                    items: ['Bold', 'Italic', 'Underline', 'Strike']
                },
                {
                    name: 'paragraph',
                    items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent']
                },
                {
                    name: 'links',
                    items: ['Link', 'Unlink']
                },
                {
                    name: 'insert',
                    items: ['Image', 'Table']
                },
                {
                    name: 'styles',
                    items: ['Format']
                },
                {
                    name: 'tools',
                    items: ['Maximize']
                }
            ]
        });

        // File input handling
        document.getElementById('thumbnail').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const label = document.querySelector('.custom-file-label');
            const preview = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');

            if (file) {
                // Update label
                label.textContent = file.name;

                // Show preview
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            } else {
                label.textContent = '{{ $post->thumbnail ? 'Chọn ảnh mới...' : 'Chọn ảnh...' }}';
                preview.style.display = 'none';
            }
        });

        // Remove image
        document.getElementById('remove-image').addEventListener('click', function() {
            document.getElementById('thumbnail').value = '';
            document.querySelector('.custom-file-label').textContent =
                '{{ $post->thumbnail ? 'Chọn ảnh mới...' : 'Chọn ảnh...' }}';
            document.getElementById('image-preview').style.display = 'none';
        });

        // Auto-hide alerts after 5 seconds
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });

        // Form validation
        $('form').on('submit', function(e) {
            const title = $('input[name="title"]').val().trim();
            const category = $('select[name="service_cate_id"]').val();

            if (!title) {
                e.preventDefault();
                $('input[name="title"]').focus();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Vui lòng nhập tiêu đề bài viết!'
                    });
                } else {
                    alert('Vui lòng nhập tiêu đề bài viết!');
                }
                return false;
            }

            if (!category) {
                e.preventDefault();
                $('select[name="service_cate_id"]').focus();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Vui lòng chọn danh mục!'
                    });
                } else {
                    alert('Vui lòng chọn danh mục!');
                }
                return false;
            }
        });
    </script>
@endpush
