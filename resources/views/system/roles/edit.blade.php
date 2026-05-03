@extends('layouts.app')

@section('title', 'تعديل الدور')
@section('content')
<div class="container-fluid mt-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold">
                <i class="fas fa-edit text-warning me-2"></i>تعديل الدور: {{ $role->name }}
            </h2>
            <p class="text-muted">تحديث اسم الدور وصلاحيات الوصول</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('system.roles.index') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
                <i class="fas fa-arrow-right me-1"></i>عودة للقائمة
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('system.roles.update', $role) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden">
            <div class="card-header bg-gradient-warning text-white py-3" style="background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);">
                <h5 class="mb-0 fw-bold"><i class="fas fa-user-tag me-2"></i>البيانات الأساسية</h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold">اسم الدور <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control form-control-lg rounded-3 @error('name') is-invalid @enderror" value="{{ old('name', $role->name) }}" required {{ $role->name === 'super-admin' ? 'readonly' : '' }}>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-check-square me-2"></i>الصلاحيات والعمليات <span class="text-danger">*</span></h5>
            </div>
            <div class="card-body p-4">
                @include('system.roles.partials._permissions')
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-md-12 text-end">
                <button type="submit" class="btn btn-warning btn-lg rounded-pill px-5 shadow">
                    <i class="fas fa-save me-2"></i> تحديث الدور
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
