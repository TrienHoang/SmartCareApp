<div class="mb-3">
    <label for="question" class="form-label">Câu hỏi</label>
    <input type="text" name="question" id="question" class="form-control"
        value="{{ old('question', optional($faq)->question) }}" required>
</div>

<div class="mb-3">
    <label for="answer" class="form-label">Câu trả lời</label>
    <textarea name="answer" id="answer" class="form-control" rows="5" required>{{ old('answer', optional($faq)->answer) }}</textarea>
</div>

<div class="mb-3">
    <label for="service_category_id" class="form-label">Danh mục</label>
    <select name="service_category_id" id="service_category_id" class="form-select" required>
        <option value="">-- Chọn danh mục --</option>
        @foreach ($categories as $id => $name)
            <option value="{{ $id }}"
                {{ old('service_category_id', optional($faq)->service_category_id) == $id ? 'selected' : '' }}>
                {{ $name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="display_order" class="form-label">Thứ tự hiển thị</label>
    <input type="number" name="display_order" id="display_order" class="form-control"
        value="{{ old('display_order', optional($faq)->display_order ?? 0) }}">
</div>

<div class="form-check mb-3">
    <input type="checkbox" name="is_active" id="is_active" class="form-check-input"
        {{ old('is_active', optional($faq)->is_active) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Hiển thị</label>
</div>
