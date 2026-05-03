@extends('layouts.app')

@section('title', 'استيراد: ' . $page->name)
@section('content')
<div class="container-fluid mt-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold">
                <i class="fas fa-file-import text-success me-2"></i>استيراد بيانات: {{ $page->name }}
            </h2>
            <p class="text-muted">
                ارفع ملف Excel أو CSV لإضافة سجلات جديدة لهذه الصفحة.
                <a href="{{ route('system.dynamic-data.template', $page->slug) }}" class="text-success fw-bold text-decoration-none ms-2">
                    <i class="fas fa-download me-1"></i>تحميل نموذج ملف (Excel)
                </a>
            </p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('system.dynamic-data.show', $page->slug) }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="fas fa-arrow-right me-1"></i>عودة للقائمة
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-gradient-success text-white py-3" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-upload me-2"></i>رفع ملف الاستيراد</h5>
                </div>
                <div class="card-body p-4 text-center">
                    <form action="{{ route('system.dynamic-data.upload', $page->slug) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <div class="border rounded-4 p-5 bg-light mb-3">
                                <i class="fas fa-cloud-upload-alt fa-3x text-success mb-3 opacity-50"></i>
                                <input type="file" name="file" class="form-control form-control-lg rounded-pill" required accept=".xlsx,.xls,.csv">
                                <p class="small text-muted mt-2">تأكد من وجود رؤوس أعمدة في الصف الأول من الملف.</p>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('system.dynamic-data.show', $page->slug) }}" class="btn btn-outline-secondary rounded-pill px-4">إلغاء</a>
                            <button type="submit" class="btn btn-success rounded-pill px-5 shadow">
                                رفع ومعاينة البيانات <i class="fas fa-chevron-left ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
