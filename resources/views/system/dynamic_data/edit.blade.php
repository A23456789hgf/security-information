@extends('layouts.app')

@section('title', 'تعديل سجل: ' . $page->name)
@section('content')
<div class="container-fluid mt-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold">
                <i class="fas fa-edit text-warning me-2"></i>تعديل سجل: {{ $page->name }}
            </h2>
            <p class="text-muted">قم بتحديث البيانات أدناه، الحقول ذات <span class="text-danger">*</span> إلزامية</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('system.dynamic-data.show', $page->slug) }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
                <i class="fas fa-arrow-right me-1"></i>عودة للقائمة
            </a>
        </div>
    </div>

    <form action="{{ route('system.dynamic-data.update', [$page->slug, $record]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden">
            <div class="card-header bg-gradient-warning text-white py-3" style="background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);">
                <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-file-signature me-2"></i>تعديل بيانات السجل</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    @foreach($page->fields as $field)
                        <div class="col-md-6">
                            @include('fields.' . $field->type, ['field' => $field, 'values' => $values])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-md-12 d-flex gap-3 justify-content-end">
                <button type="submit" class="btn btn-warning btn-lg rounded-pill px-5 shadow text-dark fw-bold">
                    <i class="fas fa-check me-2"></i> تحديث السجل
                </button>
                <a href="{{ route('system.dynamic-data.show', $page->slug) }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
                    <i class="fas fa-times me-2"></i> إلغاء
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
