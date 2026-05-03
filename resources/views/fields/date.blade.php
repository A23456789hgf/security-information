<div class="mb-3">
    <label class="form-label fw-bold small text-muted">{{ $field->label }} @if($field->is_required) <span class="text-danger">*</span> @endif</label>
    <input type="date" name="fields[{{ $field->id }}]" class="form-control rounded-3 shadow-sm border-0 bg-light" 
           value="{{ old('fields.' . $field->id, $values[$field->id] ?? '') }}" 
           {{ $field->is_required ? 'required' : '' }}>
</div>
