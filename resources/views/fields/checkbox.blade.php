<div class="mb-3">
    <div class="form-check form-switch rounded-3 p-3 bg-light-subtle border-0 shadow-sm d-flex align-items-center">
        <input class="form-check-input ms-0 me-2" type="checkbox" role="switch" 
               name="fields[{{ $field->id }}]" id="field_{{ $field->id }}" 
               value="1" {{ old('fields.' . $field->id, $values[$field->id] ?? '') ? 'checked' : '' }}>
        <label class="form-check-label fw-bold small text-muted mb-0" for="field_{{ $field->id }}">
            {{ $field->label }} @if($field->is_required) <span class="text-danger">*</span> @endif
        </label>
    </div>
</div>
