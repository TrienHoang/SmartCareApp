@extends('client.layouts.app')

@section('title', 'Tải tài liệu Y tế')
@push('styles')
    <link rel="stylesheet" href="{{ asset('client/css/upload.css') }}">
@endpush
@section('content')
    <nav class="mb-8">
        <div class="flex items-center space-x-2 text-gray-600 text-sm">
            <a href="{{ route('client.uploads.index') }}" class="hover:text-blue-600 transition-colors">
                <i class="fas fa-folder"></i> Tài liệu của tôi
            </a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-blue-600 font-medium">Tải lên File</span>
        </div>
    </nav>

    <div class="text-center mb-12">
        <div
            class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full mb-4">
            <i class="fas fa-cloud-upload-alt text-white text-2xl"></i>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Tải lên Tài liệu cá nhân</h1>
        <p class="text-gray-600 max-w-2xl mx-auto">
            Tải lên các tài liệu y tế, hình ảnh và kết quả xét nghiệm của bạn để bác sĩ có thể tham khảo
        </p>
    </div>

    <form id="uploadForm" method="POST" enctype="multipart/form-data" data-action="{{ route('client.uploads.store') }}"
        data-success-url="{{ route('client.uploads.index') }}" class="space-y-8">
        @csrf

        {{-- Hiển thị lỗi --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-4 rounded-lg mb-6">
                <strong class="font-semibold">Đã xảy ra lỗi:</strong>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div id="errorMessages" class="mb-6 hidden">
            <div class="bg-red-100 text-red-700 p-4 rounded-lg text-sm">
                <ul id="errorList" class="list-disc list-inside space-y-1"></ul>
            </div>
        </div>

        <!-- File Category Selection -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-tags mr-3"></i>
                    Phân loại Tài liệu
                </h3>
            </div>
            <div class="p-6">
                <label class="block text-sm font-medium text-gray-700 mb-4">
                    Chọn loại tài liệu <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @php
                        $categories = [
                            'Kết quả xét nghiệm' => 'fas fa-vial text-red-500',
                            'Hình ảnh y tế' => 'fas fa-x-ray text-purple-500',
                            'Đơn thuốc' => 'fas fa-pills text-green-500',
                            'Báo cáo khám' => 'fas fa-file-medical-alt text-blue-500',
                            'Giấy tờ' => 'fas fa-file-alt text-orange-500',
                            'Khác' => 'fas fa-folder-plus text-gray-500',
                        ];
                    @endphp
                    @foreach ($categories as $value => $icon)
                        <label class="category-card bg-white border-2 border-gray-200 rounded-xl p-4 cursor-pointer hover:shadow-md transition-all duration-200 hover:border-blue-300">
                            <input type="radio" name="file_category" value="{{ $value }}"
                                class="hidden category-radio" {{ old('file_category') == $value ? 'checked' : '' }}>
                            <div class="text-center">
                                <i class="{{ $icon }} text-2xl mb-2"></i>
                                <div class="text-sm font-medium text-gray-900">{{ $value }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
                
                @if ($errors->has('file_category'))
                    <p class="text-red-500 text-sm mt-2">{{ $errors->first('file_category') }}</p>
                @endif

                <!-- Custom Category Input -->
                <div id="customCategoryDiv" class="hidden mt-4">
                    <input type="text" name="custom_category" placeholder="Nhập loại tài liệu khác..."
                        value="{{ old('custom_category') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @if ($errors->has('custom_category'))
                        <p class="text-red-500 text-sm mt-1">{{ $errors->first('custom_category') }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Appointment Selection -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-calendar-alt mr-3"></i>
                    Chọn Lịch Hẹn
                </h3>
            </div>
            <div class="p-6">
                <label for="appointment_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Lịch hẹn liên quan (tuỳ chọn)
                </label>
                <select name="appointment_id" id="appointment_id"
                    class="form-select w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Không liên kết lịch hẹn --</option>
                    @if(isset($appointments) && count($appointments) > 0)
                        @foreach ($appointments as $appointment)
                            <option value="{{ $appointment->id }}"
                                {{ old('appointment_id') == $appointment->id ? 'selected' : '' }}>
                                {{ $appointment->appointment_time }} - {{ $appointment->doctor->user->full_name ?? 'N/A' }}
                            </option>
                        @endforeach
                    @endif
                </select>
                @if ($errors->has('appointment_id'))
                    <p class="text-red-500 text-sm mt-1">{{ $errors->first('appointment_id') }}</p>
                @endif
            </div>
        </div>

        <!-- File Upload -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-cloud-upload-alt mr-3"></i>
                    Tải lên File
                </h3>
            </div>
            <div class="p-6">
                <div id="dropZone"
                    class="border-2 border-dashed border-gray-300 rounded-2xl p-8 text-center transition-all duration-300 hover:border-blue-400 hover:bg-blue-50">
                    <div id="uploadArea">
                        <div
                            class="w-16 h-16 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-cloud-upload-alt text-white text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Kéo thả file vào đây</h4>
                        <p class="text-gray-600 mb-6">hoặc nhấn vào nút bên dưới để chọn file</p>
                        <label for="files"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all cursor-pointer">
                            <i class="fas fa-folder-open mr-2"></i>
                            Chọn File
                        </label>
                        <input type="file" name="files[]" id="files" multiple
                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif" class="hidden">
                        <p class="text-sm text-gray-500 mt-4">
                            <i class="fas fa-info-circle mr-1"></i>
                            Hỗ trợ: PDF, DOC, DOCX, JPG, JPEG, PNG, GIF (Tối đa 10MB mỗi file)
                        </p>
                    </div>
                </div>

                @if ($errors->has('files'))
                    <p class="text-red-500 text-sm mt-2">{{ $errors->first('files') }}</p>
                @endif
                @if ($errors->has('files.*'))
                    <p class="text-red-500 text-sm mt-2">{{ $errors->first('files.*') }}</p>
                @endif

                <!-- Selected Files Preview -->
                <div id="selectedFiles" class="hidden mt-6">
                    <h4 class="text-sm font-medium text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-file-alt mr-2"></i>
                        File đã chọn:
                    </h4>
                    <div id="filesList" class="space-y-3"></div>
                </div>
            </div>
        </div>

        <!-- Notes -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-sticky-note mr-3"></i>
                    Ghi chú
                </h3>
            </div>
            <div class="p-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Ghi chú bổ sung (không bắt buộc)
                </label>
                <textarea name="note" rows="4"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                    placeholder="Nhập mô tả chi tiết...">{{ old('note') }}</textarea>
                @if ($errors->has('note'))
                    <p class="text-red-500 text-sm mt-1">{{ $errors->first('note') }}</p>
                @endif
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-between items-center pt-6">
            <button type="button" onclick="resetForm()"
                class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 bg-white rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-undo mr-2"></i>
                Đặt lại
            </button>
            <div class="flex space-x-4">
                <a href="{{ route('client.uploads.index') }}"
                    class="inline-flex items-center px-6 py-3 text-gray-600 hover:text-gray-800 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Quay lại
                </a>
                <button type="submit" id="submitBtn"
                    class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg hover:shadow-xl">
                    <i class="fas fa-upload mr-2"></i>
                    Tải lên Tài liệu
                </button>
            </div>
        </div>
    </form>

    <!-- Upload Progress Modal -->
    <div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-8 w-96 mx-4 shadow-2xl">
            <div class="text-center mb-6">
                <div
                    class="w-16 h-16 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-cloud-upload-alt text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900">Đang tải lên...</h3>
                <p class="text-gray-600 mt-2">Vui lòng không đóng trình duyệt</p>
            </div>

            <div class="w-full bg-gray-200 rounded-full h-3 mb-4 overflow-hidden">
                <div id="progressBar" class="bg-gradient-to-r from-blue-600 to-indigo-600 h-3 rounded-full transition-all duration-300"
                    style="width: 0%"></div>
            </div>

            <div class="text-center">
                <p id="progressText" class="text-gray-700 font-medium">Đang chuẩn bị...</p>
                <p id="progressDetail" class="text-sm text-gray-500 mt-1">0 / 0 file</p>
            </div>
        </div>
    </div>

    <style>
        .category-card.selected {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }
        
        .category-card.selected i {
            color: #3b82f6 !important;
        }
        
        .category-card:hover {
            transform: translateY(-2px);
        }
        
        #dropZone.drag-over {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }
        
        .file-preview {
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection

@push('scripts')
    <script src="{{ asset('client/js/upload.js') }}"></script>
@endpush