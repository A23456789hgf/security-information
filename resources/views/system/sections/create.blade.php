@extends('layouts.app')

@section('title', 'إضافة قسم جديد')
@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-plus-circle me-2"></i>إضافة قسم إداري جديد</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('system.sections.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold">اسم القسم <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-lg rounded-3" value="{{ old('name') }}" required placeholder="مثلاً: الشؤون القانونية">
                            @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">الأيقونة (FontAwesome)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-icons"></i></span>
                                    <input type="text" name="icon" class="form-control rounded-end-3" value="{{ old('icon', 'fa-folder') }}" placeholder="fa-folder">
                                </div>
                                <small class="text-muted">مثال: fa-users, fa-file-invoice, fa-building</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">ترتيب العرض</label>
                                <input type="number" name="order_column" class="form-control rounded-3" value="{{ old('order_column', 0) }}">
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch p-0 ms-4">
                                <input class="form-check-input ms-0 me-2" type="checkbox" name="is_active" value="1" id="isActive" checked>
                                <label class="form-check-label fw-bold" for="isActive">تفعيل القسم في القائمة</label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-5">
                            <a href="{{ route('system.sections.index') }}" class="btn btn-light rounded-pill px-4">إلغاء</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 shadow">
                                <i class="fas fa-save me-1"></i> حفظ القسم
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
