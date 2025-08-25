<div class="row g-3 mb-4">
    <div class="col-md-12">
        <div class="form-group">
            <label for="question" class="form-label d-flex align-items-center">
                <i class="bx bx-question-mark text-primary me-2"></i> Câu hỏi
            </label>
            <input type="text" name="question" id="question"
                class="form-control @error('question') is-invalid @enderror"
                value="{{ old('question', optional($faq)->question) }}" placeholder="Nhập câu hỏi...">
            @error('question')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label for="answer" class="form-label d-flex align-items-center">
                <i class="bx bx-message-rounded-dots text-info me-2"></i> Câu trả lời
            </label>
            <textarea name="answer" id="answer" class="form-control @error('answer') is-invalid @enderror" rows="5"
                placeholder="Nhập câu trả lời...">{{ old('answer', optional($faq)->answer) }}</textarea>
            @error('answer')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="service_category_id" class="form-label d-flex align-items-center">
                <i class="bx bx-category text-success me-2"></i> Danh mục
            </label>
            <select name="service_category_id" id="service_category_id"
                class="form-select @error('service_category_id') is-invalid @enderror">
                <option value="">-- Chọn danh mục --</option>
                @foreach ($categories as $id => $name)
                    <option value="{{ $id }}"
                        {{ old('service_category_id', optional($faq)->service_category_id) == $id ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
            @error('service_category_id')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6 d-flex align-items-center pt-3">
        <div class="form-check">
            <input type="checkbox" name="is_active" id="is_active"
                class="form-check-input @error('is_active') is-invalid @enderror" value="1"
                {{ old('is_active', optional($faq)->is_active) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">
                <i class="bx bx-show me-1"></i> Hiển thị
            </label>
            @error('is_active')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
