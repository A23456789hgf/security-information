{{-- resources/views/components/skeleton.blade.php --}}
{{-- Skeleton Loader matching tem Skeleton.tsx --}}
@props([
    'class' => '',
])

<div class="bg-slate-200 dark:bg-slate-800 rounded animate-pulse {{ $class }}"></div>
