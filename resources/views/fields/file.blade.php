<div class="mb-4">
    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
        {{ $field->label }} @if($field->is_required) <span class="text-red-500">*</span> @endif
    </label>
    <div class="flex items-center gap-3">
        <input type="file" name="fields[{{ $field->id }}]" 
               class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2 text-sm text-slate-700 dark:text-slate-300 file:mr-0 file:ml-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-brand-50 dark:file:bg-brand-500/10 file:text-brand-600 dark:file:text-brand-400 hover:file:bg-brand-100 transition-all cursor-pointer" 
               {{ $field->is_required && empty($values[$field->id]) ? 'required' : '' }}>
        @if(!empty($values[$field->id]))
            <a href="{{ asset('storage/' . $values[$field->id]) }}" target="_blank" class="shrink-0 px-3 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 text-xs font-bold transition-colors">
                عرض الملف الحالي
            </a>
        @endif
    </div>
</div>
