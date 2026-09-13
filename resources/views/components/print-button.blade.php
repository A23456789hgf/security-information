@props([
    'url' => null,
    'text' => 'طباعة',
    'icon' => 'fas fa-print',
    'target' => '_blank',
    'class' => '',
    'variant' => 'secondary'
])

@php
    $baseClasses = "inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-bold transition-all text-xs sm:text-sm shadow-sm print:hidden cursor-pointer ";
    $variants = [
        'primary' => 'bg-brand-600 hover:bg-brand-700 text-white shadow-brand-500/20',
        'secondary' => 'bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/60',
        'outline' => 'border border-brand-500 text-brand-600 dark:text-brand-400 hover:bg-brand-50 dark:hover:bg-brand-500/10'
    ];
    $selectedVariant = $variants[$variant] ?? $variants['secondary'];
@endphp

@if($url)
    <a href="{{ $url }}" target="{{ $target }}" class="{{ $baseClasses }} {{ $selectedVariant }} {{ $class }}" title="{{ $text }}">
        <i class="{{ $icon }} text-xs"></i>
        <span>{{ $text }}</span>
    </a>
@else
    <button type="button" onclick="window.print()" class="{{ $baseClasses }} {{ $selectedVariant }} {{ $class }}" title="{{ $text }}">
        <i class="{{ $icon }} text-xs"></i>
        <span>{{ $text }}</span>
    </button>
@endif
