@extends('layouts.app')

@section('title', 'تعديل بيانات الجهة')
@section('page_title', 'تعديل الجهة: ' . $entity->name)

@section('content')
<div class="mb-4">
    <a href="{{ route('system.entities.index') }}" class="btn btn-outline-secondary rounded-pill px-4 btn-sm">
        <i class="fas fa-arrow-right me-1"></i>العودة للقائمة
    </a>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-4">
        <form action="{{ route('system.entities.update', $entity) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold small text-muted">اسم الجهة <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control form-control-lg rounded-3 @error('name') is-invalid @enderror" value="{{ old('name', $entity->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-muted">الجهة الأعلى (الأب)</label>
                    <select name="parent_id" class="form-select form-select-lg rounded-3 @error('parent_id') is-invalid @enderror">
                        <option value="">-- جهة رئيسية --</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $entity->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                        @endforeach
                    </select>
                    @error('parent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-muted">المحافظة</label>
                    <select name="governorate_id" class="form-select form-select-lg rounded-3 @error('governorate_id') is-invalid @enderror">
                        <option value="">اختر المحافظة...</option>
                        @foreach($governorates as $gov)
                            <option value="{{ $gov->id }}" {{ old('governorate_id', $entity->governorate_id) == $gov->id ? 'selected' : '' }}>{{ $gov->name }}</option>
                        @endforeach
                    </select>
                    @error('governorate_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-muted">المديرية</label>
                    <select name="directorate_id" class="form-select form-select-lg rounded-3 @error('directorate_id') is-invalid @enderror">
                        <option value="">اختر المديرية...</option>
                        @foreach($directorates as $dir)
                            <option value="{{ $dir->id }}" {{ old('directorate_id', $entity->directorate_id) == $dir->id ? 'selected' : '' }}>{{ $dir->name }}</option>
                        @endforeach
                    </select>
                    @error('directorate_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 mt-4 pt-3 border-top text-end">
                    <button type="submit" class="btn btn-warning rounded-pill px-5 py-2 shadow-sm fw-bold">
                        <i class="fas fa-sync-alt me-2"></i>تحديث بيانات الجهة
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
