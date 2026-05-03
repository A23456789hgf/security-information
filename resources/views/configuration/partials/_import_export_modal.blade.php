@php
    $routeName = $routeName ?? ('configuration.' . $type);
@endphp
<div class="d-flex gap-2 mb-3">
    <a href="{{ route($routeName . '.export') }}" class="btn btn-outline-success">
        <i class="fas fa-file-export me-1"></i> تصدير إكسل
    </a>
    <a href="{{ route($routeName . '.import') }}" class="btn btn-outline-primary">
        <i class="fas fa-file-import me-1"></i> استيراد من إكسل (ضخم)
    </a>
</div>

{{-- Modal Deprecated for scalability --}}
{{-- 
<div class="modal fade" id="importModal" ...
--}}
</div>

{{-- Modal Deprecated for scalability - Redirected to dedicated page --}}
{{-- 
<div class="modal fade" id="importModal" ...
--}}
@push('scripts')
<script>
    // Modal script deprecated. All logic moved to configuration/import.blade.php
</script>
@endpush
