<div class="mb-3">
    <label class="form-label fw-bold small text-muted">{{ $field->label }} @if($field->is_required) <span class="text-danger">*</span> @endif</label>
    <div class="input-group">
        <input type="file" name="fields[{{ $field->id }}]" class="form-control rounded-3 shadow-sm border-0 bg-light" 
               {{ $field->is_required && !isset($values[$field->id]) ? 'required' : '' }}>
    </div>
    @if(isset($values[$field->id]) && $values[$field->id])
        <div class="mt-2 small">
            <a href="{{ asset('storage/' . $values[$field->id]) }}" target="_blank" class="text-primary">
                <i class="fas fa-file-download me-1"></i> الملف الحالي
            </a>
        </div>
    @endif
</div>
