<div class="mb-4">
    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
        {{ $field->label }} @if($field->is_required) <span class="text-red-500">*</span> @endif
    </label>
    <select name="fields[{{ $field->id }}]" 
            class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white outline-none transition-all cursor-pointer"
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
