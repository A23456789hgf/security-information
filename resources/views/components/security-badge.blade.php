{{-- resources/views/components/security-badge.blade.php --}}
{{-- SecurityBadge Component matching tem design system --}}
@props([
    'level' => 'neutral',
    'label' => null,
    'icon' => null,
    'showIcon' => true,
    'class' => '',
])

@php
    // خريطة مستويات الأمان مع أنماطها
    $variants = [
        'critical' => 'bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400 border-red-100 dark:border-red-500/20',
        'danger'   => 'bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400 border-red-100 dark:border-red-500/20',
        'warning'  => 'bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 border-amber-100 dark:border-amber-500/20',
        'resolved' => 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border-emerald-100 dark:border-emerald-500/20',
        'success'  => 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border-emerald-100 dark:border-emerald-500/20',
        'info'     => 'bg-brand-50 dark:bg-brand-500/10 text-brand-700 dark:text-brand-400 border-brand-100 dark:border-brand-500/20',
        'primary'  => 'bg-brand-50 dark:bg-brand-500/10 text-brand-700 dark:text-brand-400 border-brand-100 dark:border-brand-500/20',
        'neutral'  => 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700',
    ];

    $icons = [
        'critical' => 'fas fa-exclamation-circle',
        'danger'   => 'fas fa-exclamation-circle',
        'warning'  => 'fas fa-exclamation-triangle',
        'resolved' => 'fas fa-check-circle',
        'success'  => 'fas fa-check-circle',
        'info'     => 'fas fa-info-circle',
        'primary'  => 'fas fa-shield-alt',
        'neutral'  => 'fas fa-shield-alt',
    ];

    // تحديد المستوى الفعلي (إذا كان غير معروف نستخدم neutral)
    $level = $level ?? 'neutral';
    $levelClass = $variants[$level] ?? $variants['neutral'];
    $iconClass = $icon ?? $icons[$level] ?? $icons['neutral'];
    $displayLabel = $label ?? ucfirst($level);
@endphp

<span {{ $attributes->merge([
    'class' => "inline-flex items-center gap-1.5 px-2.5 py-1 rounded-2xl text-xs font-bold transition-colors border $levelClass " . $class
]) }}>
    @if($showIcon && $iconClass)
        <i class="{{ $iconClass }} text-xs shrink-0"></i>
    @endif
    <span class="whitespace-nowrap">{{ $displayLabel }}</span>
</span>
