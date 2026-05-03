@extends('layouts.app')

@section('title', 'تفاصيل البلاغ الأمني')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded shadow-sm">
                <div>
                    <h2 class="mb-0 text-primary"><i class="fas fa-info-circle me-2"></i>تفاصيل البلاغ</h2>
                    <p class="text-muted mb-0">عرض كافة البيانات المسجلة للبلاغ: <strong>{{ $securityReport->full_name }}</strong></p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('system.security-reports.edit', $securityReport) }}" class="btn btn-warning px-4">
                        <i class="fas fa-edit me-1"></i> تعديل
                    </a>
                    <a href="{{ route('system.security-reports.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-arrow-right me-1"></i> العودة للقائمة
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Grouped Details -->
    <div class="row g-4">
        <!-- Section 1: البيانات الأساسية -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0">البيانات الأساسية وتفاصيل البلاغ</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <tr><th class="ps-4 w-40 bg-light">الاسم الكامل واللقب</th><td>{{ $securityReport->full_name }}</td></tr>
                        <tr><th class="ps-4 bg-light">موضوع البلاغ</th><td>{{ $securityReport->report_name }}</td></tr>
                        <tr><th class="ps-4 bg-light">نوع البلاغ</th><td>{{ $securityReport->report_type ?: '-' }}</td></tr>
                        <tr><th class="ps-4 bg-light">تاريخ البلاغ</th><td>{{ $securityReport->report_date ? $securityReport->report_date->format('Y-m-d') : '-' }}</td></tr>
                        <tr><th class="ps-4 bg-light">الرقم العسكري</th><td>{{ $securityReport->military_number ?: '-' }}</td></tr>
                        <tr><th class="ps-4 bg-light">رقم البطاقة</th><td>{{ $securityReport->id_number ?: '-' }}</td></tr>
                        <tr><th class="ps-4 bg-light">الهاتف</th><td>{{ $securityReport->phone ?: '-' }}</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Section 2: الوحدة والعمل -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">بيانات الوحدة والجهة</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <tr><th class="ps-4 w-40 bg-light">الوحدة</th><td>{{ $securityReport->unit ?: '-' }}</td></tr>
                        <tr><th class="ps-4 bg-light">الوحدة الفرعية</th><td>{{ $securityReport->sub_unit ?: '-' }}</td></tr>
                        <tr><th class="ps-4 bg-light">الوحدة التفصيلية</th><td>{{ $securityReport->detailed_unit ?: '-' }}</td></tr>
                        <tr><th class="ps-4 bg-light">الرتبة</th><td>{{ $securityReport->rank ?: '-' }}</td></tr>
                        <tr><th class="ps-4 bg-light">التعيين</th><td>{{ $securityReport->designation ?: '-' }}</td></tr>
                        <tr><th class="ps-4 bg-light">المسمى الوظيفي</th><td>{{ $securityReport->job_title ?: '-' }}</td></tr>
                        <tr><th class="ps-4 bg-light">العمل / الجهة</th><td>{{ $securityReport->entity ? $securityReport->entity->name : ($securityReport->work ?: '-') }}</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Section 3: السكن والأصل -->
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0">بيانات الأصل والسكن</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 border-end">
                            <h6 class="fw-bold text-success border-bottom pb-2 mb-3">بيانات الأصل</h6>
                            <table class="table table-sm">
                                <tr><th class="w-40 bg-light">الأصل / المحافظة</th><td>{{ $securityReport->original_governorate ?: '-' }}</td></tr>
                                <tr><th class="bg-light">الأصل / المديرية</th><td>{{ $securityReport->original_directorate ?: '-' }}</td></tr>
                                <tr><th class="bg-light">الأصل / القرية</th><td>{{ $securityReport->original_village ?: '-' }}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6 ps-md-4">
                            <h6 class="fw-bold text-success border-bottom pb-2 mb-3">بيانات السكن</h6>
                            <table class="table table-sm">
                                <tr><th class="w-40 bg-light">السكن / المحافظة</th><td>{{ $securityReport->residence_governorate ?: '-' }}</td></tr>
                                <tr><th class="bg-light">السكن / المديرية</th><td>{{ $securityReport->residence_directorate ?: '-' }}</td></tr>
                                <tr><th class="bg-light">السكن / القرية</th><td>{{ $securityReport->residence_village ?: '-' }}</td></tr>
                                <tr><th class="bg-light fw-bold text-dark">المنطقة</th><td>{{ $securityReport->area ?: '-' }}</td></tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 4: الحالة القانونية والإجرائية -->
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-danger text-white py-3">
                    <h5 class="mb-0">الحالة القانونية والإجراءات</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <table class="table table-sm">
                                <tr><th class="bg-light">حالة القبض</th><td>{{ $securityReport->arrest_status ?: '-' }}</td></tr>
                                <tr><th class="bg-light">تاريخ القبض</th><td>{{ $securityReport->arrest_date ? $securityReport->arrest_date->format('Y-m-d') : '-' }}</td></tr>
                                <tr><th class="bg-light">تاريخ الإيداع</th><td>{{ $securityReport->deposit_date ? $securityReport->deposit_date->format('Y-m-d') : '-' }}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <table class="table table-sm">
                                <tr><th class="bg-light">التهمة</th><td>{{ $securityReport->charge ?: '-' }}</td></tr>
                                <tr><th class="bg-light">الإجراءات</th><td>{{ $securityReport->procedures ?: '-' }}</td></tr>
                                <tr><th class="bg-light">جهة الحجز</th><td>{{ $securityReport->detention_source ?: '-' }}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <table class="table table-sm">
                                <tr><th class="bg-light">جهة الإطلاق</th><td>{{ $securityReport->source_of_release ?: '-' }}</td></tr>
                                <tr><th class="bg-light">تاريخ الإطلاق</th><td>{{ $securityReport->date_of_release ? $securityReport->date_of_release->format('Y-m-d') : '-' }}</td></tr>
                                <tr><th class="bg-light font-weight-bold">الحالة</th><td>{{ $securityReport->status ?: '-' }}</td></tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 5: ملاحظات -->
        <div class="col-md-12 mb-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning py-3">
                    <h5 class="mb-0">ملاحظات إضافية</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="small text-muted mb-1">ملاحظات (1)</p>
                            <div class="p-3 bg-light rounded border">{{ $securityReport->notes ?: 'لا توجد ملاحظات' }}</div>
                        </div>
                        <div class="col-md-6">
                            <p class="small text-muted mb-1">ملاحظات (2)</p>
                            <div class="p-3 bg-light rounded border">{{ $securityReport->additional_notes ?: 'لا توجد ملاحظات إضافية' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
