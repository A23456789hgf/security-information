<!-- resources/views/components/w-full pr-2 pl-2 py-2 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-brand-500/20 transition-all.blade.php -->
{{-- FormSelect matching tem design system --}}
@props([
    'name',
    'options' => [],
    'selected' => null,
    'placeholder' => '',
    'label' => null,
    'required' => false,
    'class' => '',
])
<div class="flex flex-col gap-1.5 {{ $class }}">
    @if($label)
        <label for="{{ $name }}" class="text-sm font-bold text-slate-700 dark:text-slate-300">
            {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif
    <select
        id="{{ $name }}"
        name="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 focus:ring-brand-500/20 focus:border-brand-500 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white outline-none focus:ring-2 transition-all cursor-pointer']) }}
    >
        @if($placeholder)
            <option value="" disabled {{ old($name, $selected) === null ? 'selected' : '' }}>{{ $placeholder }}</option>
        @endif
        @foreach($options as $value => $text)
            <option value="{{ $value }}" @selected($value == old($name, $selected))>{{ $text }}</option>
        @endforeach
    </select>
    @error($name)
        <span class="text-xs font-semibold text-red-500 mt-1">{{ $message }}</span>
    @enderror
</div>




