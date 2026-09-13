<!-- resources/views/components/form-wrapper.blade.php -->
{{-- Wrapper for forms matching tem design system --}}
@props([
    'title' => null,
    'icon' => null,
    'class' => '',
])
<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm transition-colors w-full {{ $class }}">
    @if($title)
        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 flex items-center gap-3">
            @if($icon)
                <div class="text-brand-600 dark:text-brand-400">
                    <i class="{{ $icon }}"></i>
                </div>
            @endif
            <h2 class="font-display font-bold text-xl text-slate-900 dark:text-white m-0">{{ $title }}</h2>
        </div>
    @endif
    <div class="p-6 sm:p-8">
        {{ $slot }}
    </div>
</div>



