<div class="mb-4">
    <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
        <input type="checkbox" 
               name="fields[{{ $field->id }}]" 
               id="field_{{ $field->id }}" 
               value="1" 
               {{ old('fields.' . $field->id, $values[$field->id] ?? '') ? 'checked' : '' }}
               class="w-4 h-4 text-brand-600 rounded border-slate-300 dark:border-slate-700 dark:bg-slate-900 focus:ring-brand-500 focus:ring-offset-0 cursor-pointer">
        <label class="text-sm font-bold text-slate-700 dark:text-slate-300 cursor-pointer mb-0 select-none" for="field_{{ $field->id }}">
            {{ $field->label }} @if($field->is_required) <span class="text-red-500">*</span> @endif
        </label>
    </div>
</div>
