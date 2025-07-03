{{-- Cần truyền $item và $loopIndex vào từ edit.blade.php --}}
<div class="treatment-item-card card mb-3 p-3 border">
    <div class="card-body">
        {{-- Quan trọng: Thêm hidden input chứa ID của item để xác định item cũ khi submit --}}
        <input type="hidden" name="items[{{ $loopIndex }}][id]" value="{{ $item->id }}">
        <button type="button" class="btn btn-sm btn-danger float-end remove-item-btn">Xóa</button> {{-- Đảm bảo nút này có class "remove-item-btn" --}}
        <h5 class="card-title">Bước Điều trị {{ $loopIndex + 1 }}</h5>

        <div class="form-group mb-2">
            <label for="item_{{ $loopIndex }}_title">Tên/Mô tả bước:</label>
            <input type="text" name="items[{{ $loopIndex }}][title]" id="item_{{ $loopIndex }}_title" class="form-control" value="{{ old('items.'.$loopIndex.'.title', $item->title) }}" required>
        </div>
        <div class="form-group mb-2">
            <label for="item_{{ $loopIndex }}_description">Mô tả chi tiết:</label>
            <textarea name="items[{{ $loopIndex }}][description]" id="item_{{ $loopIndex }}_description" class="form-control" rows="2">{{ old('items.'.$loopIndex.'.description', $item->description) }}</textarea>
        </div>
        <div class="form-group mb-2">
            <label for="item_{{ $loopIndex }}_expected_start_date">Ngày bắt đầu dự kiến:</label>
            <input type="date" name="items[{{ $loopIndex }}][expected_start_date]" id="item_{{ $loopIndex }}_expected_start_date" class="form-control" value="{{ old('items.'.$loopIndex.'.expected_start_date', $item->expected_start_date?->format('Y-m-d')) }}">
        </div>
        <div class="form-group mb-2">
            <label for="item_{{ $loopIndex }}_expected_end_date">Ngày kết thúc dự kiến:</label>
            <input type="date" name="items[{{ $loopIndex }}][expected_end_date]" id="item_{{ $loopIndex }}_expected_end_date" class="form-control" value="{{ old('items.'.$loopIndex.'.expected_end_date', $item->expected_end_date?->format('Y-m-d')) }}">
        </div>
        <div class="form-group mb-2">
            <label for="item_{{ $loopIndex }}_frequency">Tần suất:</label>
            <input type="text" name="items[{{ $loopIndex }}][frequency]" id="item_{{ $loopIndex }}_frequency" class="form-control" value="{{ old('items.'.$loopIndex.'.frequency', $item->frequency) }}" placeholder="Ví dụ: 2 lần/ngày, 1 lần/tuần">
        </div>
        <div class="form-group mb-2">
            <label for="item_{{ $loopIndex }}_notes">Ghi chú cho bước:</label>
            <textarea name="items[{{ $loopIndex }}][notes]" id="item_{{ $loopIndex }}_notes" class="form-control" rows="2">{{ old('items.'.$loopIndex.'.notes', $item->notes) }}</textarea>
        </div>
    </div>
</div>