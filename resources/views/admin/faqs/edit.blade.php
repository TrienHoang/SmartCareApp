@extends('admin.dashboard')
@section('title', 'Chỉnh sửa Câu hỏi')

@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bx bx-edit-alt me-2 text-primary" style="font-size: 24px;"></i>
                            <h2 class="content-header-title mb-0">Chỉnh sửa Câu hỏi</h2>
                        </div>
                        <div class="breadcrumb-wrapper">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="">
                                        <a href="{{ route('admin.dashboard.index') }}" class="text-decoration-none">
                                            Trang chủ >
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.faqs.index') }}" class="text-decoration-none">
                                            Câu hỏi thường gặp >
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active">Chỉnh sửa</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light border-bottom">
                            <h6 class="m-0 font-weight-bold text-primary">Chỉnh sửa Câu hỏi #{{ $faq->id }}</h6>
                        </div>
                        <div class="card-body p-4">

                            <form action="{{ route('admin.faqs.update', $faq->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                @include('admin.faqs._form', ['faq' => $faq])

                                <div class="d-flex justify-content-start gap-2 mt-4">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                                        <i class="bx bx-save me-1"></i> Cập nhật
                                    </button>
                                    <a href="{{ route('admin.faqs.index') }}"
                                        class="btn btn-outline-secondary waves-effect">
                                        <i class="bx bx-arrow-back me-1"></i> Quay lại
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
