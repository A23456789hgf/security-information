<!-- resources/views/components/form-input.blade.php -->
{{-- FormInput matching tem design system --}}
@props([
    'name',
    'type' => 'text',
    'label' => null,
    'placeholder' => '',
    'value' => null,
    'required' => false,
    'class' => '',
])
<div class="flex flex-col gap-1.5 {{ $class }}">
    @if($label)
        <label for="{{ $name }}" class="text-sm font-bold text-slate-700 dark:text-slate-300">
            {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif
    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        {{ $required ? 'required' : '' }}
        placeholder="{{ $placeholder }}"
        value="{{ old($name, $value) }}"
        {{ $attributes->merge(['class' => 'w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 focus:ring-brand-500/20 focus:border-brand-500 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-600 outline-none focus:ring-2 transition-all']) }}
    >
    @error($name)
        <span class="text-xs font-semibold text-red-500 mt-1">{{ $message }}</span>
    @enderror
</div>



