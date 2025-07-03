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

    <form action="#" method="post" enctype="multipart/form-data" id="uploadForm" class="space-y-8">
        @csrf

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
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                    <label class="category-card bg-white border rounded-xl p-4 cursor-pointer hover:shadow-md">
                        <input type="radio" name="file_category" value="Kết quả xét nghiệm" class="hidden category-radio">
                        <div class="text-center">
                            <i class="fas fa-vial text-2xl text-red-500 mb-2"></i>
                            <div class="text-sm font-medium text-gray-900">Kết quả xét nghiệm</div>
                        </div>
                    </label>
                    <label class="category-card bg-white border rounded-xl p-4 cursor-pointer hover:shadow-md">
                        <input type="radio" name="file_category" value="Hình ảnh y tế" class="hidden category-radio">
                        <div class="text-center">
                            <i class="fas fa-x-ray text-2xl text-purple-500 mb-2"></i>
                            <div class="text-sm font-medium text-gray-900">Hình ảnh y tế</div>
                        </div>
                    </label>
                    <label class="category-card bg-white border rounded-xl p-4 cursor-pointer hover:shadow-md">
                        <input type="radio" name="file_category" value="Đơn thuốc" class="hidden category-radio">
                        <div class="text-center">
                            <i class="fas fa-pills text-2xl text-green-500 mb-2"></i>
                            <div class="text-sm font-medium text-gray-900">Đơn thuốc</div>
                        </div>
                    </label>
                    <label class="category-card bg-white border rounded-xl p-4 cursor-pointer hover:shadow-md">
                        <input type="radio" name="file_category" value="Báo cáo khám" class="hidden category-radio">
                        <div class="text-center">
                            <i class="fas fa-file-medical-alt text-2xl text-blue-500 mb-2"></i>
                            <div class="text-sm font-medium text-gray-900">Báo cáo khám</div>
                        </div>
                    </label>
                    <label class="category-card bg-white border rounded-xl p-4 cursor-pointer hover:shadow-md">
                        <input type="radio" name="file_category" value="Giấy tờ" class="hidden category-radio">
                        <div class="text-center">
                            <i class="fas fa-file-alt text-2xl text-orange-500 mb-2"></i>
                            <div class="text-sm font-medium text-gray-900">Giấy tờ</div>
                        </div>
                    </label>
                    <label class="category-card bg-white border rounded-xl p-4 cursor-pointer hover:shadow-md">
                        <input type="radio" name="file_category" value="Khác" class="hidden category-radio"
                            id="categoryOther">
                        <div class="text-center">
                            <i class="fas fa-folder-plus text-2xl text-gray-500 mb-2"></i>
                            <div class="text-sm font-medium text-gray-900">Khác</div>
                        </div>
                    </label>
                </div>

                <!-- Custom Category Input -->
                <div id="customCategoryDiv" class="hidden">
                    <input type="text" name="custom_category" placeholder="Nhập loại tài liệu khác..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
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
                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif" class="hidden" required>
                        <p class="text-sm text-gray-500 mt-4">
                            <i class="fas fa-info-circle mr-1"></i>
                            Hỗ trợ: PDF, DOC, DOCX, JPG, JPEG, PNG, GIF (Tối đa 10MB mỗi file)
                        </p>
                    </div>
                </div>

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
                    placeholder="Nhập mô tả chi tiết về tài liệu của bạn, ngày tháng thực hiện xét nghiệm, hoặc thông tin bổ sung khác..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
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
                <a href="#"
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

    <div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="glass-effect rounded-2xl p-8 w-96 mx-4">
            <div class="text-center mb-6">
                <div
                    class="w-16 h-16 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-cloud-upload-alt text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900">Đang tải lên...</h3>
                <p class="text-gray-600 mt-2">Vui lòng không đóng trình duyệt</p>
            </div>

            <div class="w-full bg-gray-200 rounded-full h-3 mb-4 overflow-hidden">
                <div id="progressBar" class="upload-progress h-3 rounded-full transition-all duration-300"
                    style="width: 0%"></div>
            </div>

            <div class="text-center">
                <p id="progressText" class="text-gray-700 font-medium">Đang chuẩn bị...</p>
                <p id="progressDetail" class="text-sm text-gray-500 mt-1">0 / 0 file</p>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('client/js/upload.js') }}"></script>
@endpush
