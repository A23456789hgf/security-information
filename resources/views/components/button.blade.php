<!-- resources/views/components/button.blade.php -->
{{-- Button component matching tem design system --}}
@props([
    'type' => 'button',
    'variant' => 'primary',
    'class' => '',
    'disabled' => false,
])

@php
    $variantClasses = [
        'primary' => 'bg-brand-600 hover:bg-brand-700 text-white rounded-xl font-bold shadow-sm shadow-brand-500/30 px-6 py-2.5 text-sm transition-all',
        'secondary' => 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl px-6 py-2.5 text-sm font-semibold transition-all',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold shadow-sm shadow-red-500/30 px-6 py-2.5 text-sm transition-all',
        'outline' => 'bg-transparent border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl px-6 py-2.5 text-sm font-semibold transition-all',
    ];

    $baseClass = $variantClasses[$variant] ?? $variantClasses['primary'];
@endphp

<button
    type="{{ $type }}"
    {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge(['class' => "inline-flex items-center justify-center gap-2 outline-none disabled:opacity-50 disabled:cursor-not-allowed {$baseClass} {$class}"]) }}
>
    {{ $slot }}
</button>



