<div class="mb-3">
    <label class="form-label fw-bold small text-muted">{{ $field->label }} @if($field->is_required) <span class="text-danger">*</span> @endif</label>
    <textarea name="fields[{{ $field->id }}]" class="form-control rounded-3 shadow-sm border-0 bg-light" 
              rows="3" {{ $field->is_required ? 'required' : '' }}
              placeholder="أدخل {{ $field->label }}...">{{ old('fields.' . $field->id, $values[$field->id] ?? '') }}</textarea>
</div>
