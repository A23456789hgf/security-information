<div class="mb-4">
    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
        {{ $field->label }} @if($field->is_required) <span class="text-red-500">*</span> @endif
    </label>
    <input type="date" name="fields[{{ $field->id }}]" 
           class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white outline-none transition-all" 
           value="{{ old('fields.' . $field->id, $values[$field->id] ?? '') }}" 
           {{ $field->is_required ? 'required' : '' }}>
</div>
