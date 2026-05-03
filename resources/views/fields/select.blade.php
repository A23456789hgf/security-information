<div class="mb-3">
    <label class="form-label fw-bold small text-muted">{{ $field->label }} @if($field->is_required) <span class="text-danger">*</span> @endif</label>
    <select name="fields[{{ $field->id }}]" class="form-select rounded-3 shadow-sm border-0 bg-light"
            {{ $field->is_required ? 'required' : '' }}>
        <option value="">-- اختر --</option>
        @php
            $options = json_decode($field->options, true) ?? [];
            $currentValue = old('fields.' . $field->id, $values[$field->id] ?? '');
        @endphp
        @foreach($options as $option)
            <option value="{{ $option }}" {{ $currentValue == $option ? 'selected' : '' }}>{{ $option }}</option>
        @endforeach
    </select>
</div>
