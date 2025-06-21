@if ($errors->any())
    <div class="alert alert-danger mb-3">
        <strong>Đã xảy ra lỗi!</strong> Vui lòng kiểm tra lại các trường bên dưới.
    </div>
@endif

<div class="mb-3">
    <label for="service_cate_id" class="form-label">Danh mục dịch vụ</label>
    <select name="service_cate_id" id="service_cate_id" class="form-select @error('service_cate_id') is-invalid @enderror">
        <option value="">-- Chọn danh mục --</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}"
                {{ old('service_cate_id', $service->service_cate_id ?? '') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    @error('service_cate_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="name" class="form-label">Tên dịch vụ</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
        value="{{ old('name', $service->name ?? '') }}">
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="description" class="form-label">Mô tả</label>
    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $service->description ?? '') }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="price" class="form-label">Giá</label>
    <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror"
        value="{{ old('price', $service->price ?? '') }}" step="0.01" min="0">
    @error('price')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="duration" class="form-label">Thời lượng (phút)</label>
    <input type="number" name="duration" id="duration" class="form-control @error('duration') is-invalid @enderror"
        value="{{ old('duration', $service->duration ?? '') }}" min="1">
    @error('duration')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="status" class="form-label">Trạng thái</label>
    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
        <option value="">-- Chọn trạng thái --</option>
        <option value="active" {{ old('status', $service->status ?? '') == 'active' ? 'selected' : '' }}>Kích hoạt
        </option>
        <option value="inactive" {{ old('status', $service->status ?? '') == 'inactive' ? 'selected' : '' }}>Tạm ngưng
        </option>
    </select>
    @error('status')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
