<div class="item-card" data-index="{{ $index }}">
    <div class="card-header">
        <span>Bước Điều Trị #<span class="item-number">{{ (int)$index + 1 }}</span></span>
        <button type="button" class="btn-close remove-item" aria-label="Close"></button>
    </div>
    <input type="hidden" name="items[{{ $index }}][id]" value="{{ old("items.$index.id", $item->id ?? '') }}">

    <div class="mb-3">
        <label class="form-label">Loại Bước Điều Trị <span class="text-danger">*</span></label>
        <div>
            <div class="form-check form-check-inline">
                <input class="form-check-input item-type-radio" type="radio" name="items[{{ $index }}][item_type]"
                    id="items_{{ $index }}_service" value="service"
                    {{ old("items.$index.item_type", isset($item->service_id) && $item->service_id ? 'service' : 'custom') === 'service' ? 'checked' : '' }}>
                <label class="form-check-label" for="items_{{ $index }}_service">Chọn Dịch vụ</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input item-type-radio" type="radio" name="items[{{ $index }}][item_type]"
                    id="items_{{ $index }}_custom" value="custom"
                    {{ old("items.$index.item_type", isset($item->service_id) && $item->service_id ? 'service' : 'custom') === 'custom' ? 'checked' : '' }}>
                <label class="form-check-label" for="items_{{ $index }}_custom">Tạo Bước Tùy chỉnh</label>
            </div>
        </div>
    </div>

    <div class="service-selection" style="{{ old("items.$index.item_type", isset($item->service_id) && $item->service_id ? 'service' : 'custom') === 'custom' ? 'display: none;' : '' }}">
        <div class="mb-3">
            <label for="items_{{ $index }}_service_id" class="form-label">Dịch vụ <span class="text-danger">*</span></label>
            <select class="form-control service-select" id="items_{{ $index }}_service_id"
                name="items[{{ $index }}][service_id]"
                {{ old("items.$index.item_type", isset($item->service_id) && $item->service_id ? 'service' : 'custom') === 'service' ? 'required' : '' }}>
                <option value="">-- Chọn dịch vụ --</option>
                @foreach ($services as $service)
                    <option value="{{ $service->id }}" data-price="{{ $service->price }}"
                        data-description="{{ $service->description }}"
                        {{ old("items.$index.service_id", isset($item->service_id) ? $item->service_id : '') == $service->id ? 'selected' : '' }}>
                        {{ $service->name }} ({{ number_format($service->price) }} VND)
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="custom-step-fields" style="{{ old("items.$index.item_type", isset($item->service_id) && $item->service_id ? 'service' : 'custom') === 'service' ? 'display: none;' : '' }}">
        <div class="mb-3">
            <label for="items_{{ $index }}_title" class="form-label">Tiêu đề Bước <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="items_{{ $index }}_title" name="items[{{ $index }}][title]"
                value="{{ old("items.$index.title", $item->title ?? '') }}"
                {{ old("items.$index.item_type", isset($item->service_id) && $item->service_id ? 'service' : 'custom') === 'custom' ? 'required' : '' }}>
        </div>
        <div class="mb-3">
            <label for="items_{{ $index }}_description" class="form-label">Mô tả Bước</label>
            <textarea class="form-control" id="items_{{ $index }}_description" name="items[{{ $index }}][description]" rows="2"
                {{ old("items.$index.item_type", isset($item->service_id) && $item->service_id ? 'service' : 'custom') === 'custom' ? 'required' : '' }}>{{ old("items.$index.description", $item->description ?? '') }}</textarea>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="items_{{ $index }}_expected_start_date" class="form-label">Ngày bắt đầu dự kiến <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="items_{{ $index }}_expected_start_date"
                name="items[{{ $index }}][expected_start_date]"
                value="{{ old("items.$index.expected_start_date", isset($item->expected_start_date) ? \Carbon\Carbon::parse($item->expected_start_date)->format('Y-m-d') : date('Y-m-d')) }}"
                required>
        </div>
        <div class="col-md-6">
            <label for="items_{{ $index }}_expected_end_date" class="form-label">Ngày kết thúc dự kiến</label>
            <input type="date" class="form-control" id="items_{{ $index }}_expected_end_date"
                name="items[{{ $index }}][expected_end_date]"
                value="{{ old("items.$index.expected_end_date", isset($item->expected_end_date) ? \Carbon\Carbon::parse($item->expected_end_date)->format('Y-m-d') : '') }}">
        </div>
    </div>

    <div class="mb-3">
        <label for="items_{{ $index }}_frequency" class="form-label">Tần suất</label>
        <input type="text" class="form-control" id="items_{{ $index }}_frequency" name="items[{{ $index }}][frequency]"
            value="{{ old("items.$index.frequency", $item->frequency ?? '') }}">
    </div>

    <div class="mb-3">
        <label for="items_{{ $index }}_notes" class="form-label">Ghi chú Bước</label>
        <textarea class="form-control" id="items_{{ $index }}_notes" name="items[{{ $index }}][notes]" rows="2">{{ old("items.$index.notes", $item->notes ?? '') }}</textarea>
    </div>

    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input needs-appointment-checkbox"
            id="items_{{ $index }}_needs_appointment" name="items[{{ $index }}][needs_appointment]" value="1"
            {{ old("items.$index.needs_appointment", isset($item->needs_appointment) ? $item->needs_appointment : 0) ? 'checked' : '' }}>
        <label class="form-check-label" for="items_{{ $index }}_needs_appointment">Tạo lịch hẹn cho bước này?</label>
    </div>

    <div class="appointment-fields" style="{{ old("items.$index.needs_appointment", isset($item->needs_appointment) ? $item->needs_appointment : 0) ? '' : 'display: none;' }}">
        <h5>Thông tin Lịch hẹn</h5>
        <input type="hidden" name="items[{{ $index }}][appointment_id]" value="{{ old("items.$index.appointment_id", '') }}">
        <div class="mb-3">
            <label for="items_{{ $index }}_appointment_time" class="form-label">Thời gian Lịch hẹn <span class="text-danger">*</span></label>
            <input type="datetime-local" class="form-control" id="items_{{ $index }}_appointment_time"
                name="items[{{ $index }}][appointment_time]"
                value="{{ old("items.$index.appointment_time", '') }}"
                {{ old("items.$index.needs_appointment", isset($item->needs_appointment) ? $item->needs_appointment : 0) ? 'required' : '' }}>
            <small class="form-text text-muted">Kiểm tra lịch làm việc và nghỉ phép của bác sĩ để đảm bảo tính hợp lệ.</small>
        </div>
        <div class="mb-3">
            <label for="items_{{ $index }}_reason" class="form-label">Lý do Lịch hẹn</label>
            <textarea class="form-control" id="items_{{ $index }}_reason" name="items[{{ $index }}][reason]" rows="2">{{ old("items.$index.reason", '') }}</textarea>
        </div>
    </div>
</div>