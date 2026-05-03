@extends('layouts.app')

@section('title', 'تصدير البيانات الشخصية')

@section('content')
<div class="container-fluid mt-4" dir="rtl">
    <div class="row mb-4 text-end">
        <div class="col-12">
            <h2 class="fw-bold text-primary"><i class="fas fa-file-export me-2"></i>تصدير البيانات الشخصية</h2>
            <p class="text-muted">اختر المعايير التي تريد تصفية البيانات بها قبل تصديرها إلى ملف Excel.</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5">
                    <form action="{{ route('system.personal-data.export-download') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">بحث عام (الاسم / الهوية)</label>
                            <input type="text" name="search" class="form-control rounded-pill px-4" placeholder="اتركه فارغاً لتصدير الكل...">
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">المحافظة</label>
                                <select name="governorate_id" class="form-select rounded-pill px-4" id="gov_id">
                                    <option value="">كل المحافظات</option>
                                    @foreach(\App\Models\Governorate::all() as $gov)
                                        <option value="{{ $gov->id }}">{{ $gov->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">نوع التصدير</label>
                                <select class="form-select rounded-pill px-4" disabled>
                                    <option>ملف Excel القياسي (.xlsx)</option>
                                </select>
                            </div>
                        </div>

                        <div class="alert alert-warning rounded-4 border-0 mb-4 p-3 d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle fa-2x ms-3 opacity-50"></i>
                            <div>
                                <h6 class="fw-bold mb-1">تنبيه سرية</h6>
                                <small>يرجى العلم أن الملف المصدر يحتوي على بيانات شخصية حساسة. تعامل معه بحذر وفقاً لسياسات الأمن.</small>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow">
                                <i class="fas fa-download me-2"></i>بدء تحميل الملف
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="mt-4 text-center">
                <a href="{{ route('system.personal-data.index') }}" class="btn btn-link text-decoration-none">إلغاء والعودة للقائمة</a>
            </div>
        </div>
    </div>
</div>
@endsection
