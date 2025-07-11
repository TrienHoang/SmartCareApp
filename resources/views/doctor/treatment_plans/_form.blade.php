<div class="form-group">
    <label for="patient_id">Bệnh nhân:</label>
    <select name="patient_id" id="patient_id" class="form-control select2 @error('patient_id') is-invalid @enderror" required>
        <option value="">Chọn bệnh nhân</option>
        @if(old('patient_id') && $patients)
            @foreach($patients as $patient)
                @if(old('patient_id') == $patient->id)
                    <option value="{{ $patient->id }}" selected>{{ $patient->full_name }} ({{ $patient->email }})</option>
                @endif
            @endforeach
        @endif
    </select>
    @error('patient_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="plan_title">Tiêu đề Kế hoạch:</label>
    <input type="text" name="plan_title" id="plan_title" class="form-control @error('plan_title') is-invalid @enderror" value="{{ old('plan_title', $treatmentPlan->plan_title ?? '') }}">
    @error('plan_title')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="total_estimated_cost">Chi phí ước tính:</label>
    <input type="number" step="0.01" name="total_estimated_cost" id="total_estimated_cost" class="form-control @error('total_estimated_cost') is-invalid @enderror" value="{{ old('total_estimated_cost', $treatmentPlan->total_estimated_cost ?? '') }}">
    @error('total_estimated_cost')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="notes">Ghi chú:</label>
    <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $treatmentPlan->notes ?? '') }}</textarea>
    @error('notes')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="diagnosis">Chẩn đoán:</label>
    <textarea name="diagnosis" id="diagnosis" class="form-control @error('diagnosis') is-invalid @enderror">{{ old('diagnosis', $treatmentPlan->diagnosis ?? '') }}</textarea>
    @error('diagnosis')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="goal">Mục tiêu:</label>
    <textarea name="goal" id="goal" class="form-control @error('goal') is-invalid @enderror">{{ old('goal', $treatmentPlan->goal ?? '') }}</textarea>
    @error('goal')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="start_date">Ngày bắt đầu:</label>
    <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', isset($treatmentPlan->start_date) ? $treatmentPlan->start_date->format('Y-m-d') : '') }}">
    @error('start_date')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="end_date">Ngày kết thúc:</label>
    <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', isset($treatmentPlan->end_date) ? $treatmentPlan->end_date->format('Y-m-d') : '') }}">
    @error('end_date')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="status">Trạng thái:</label>
    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
        @php
            $statuses = ['draft', 'active', 'completed', 'paused', 'cancelled'];
        @endphp
        @foreach($statuses as $statusOption)
            <option value="{{ $statusOption }}" {{ old('status', $treatmentPlan->status ?? 'draft') == $statusOption ? 'selected' : '' }}>
                {{ ucfirst($statusOption) }}
            </option>
        @endforeach
    </select>
    @error('status')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>