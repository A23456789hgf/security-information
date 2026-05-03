@extends('layouts.app')

@section('title', 'دليل التنسيقات والأنماط (UI Style Guide)')
@section('page_title', 'دليل التنسيقات والأنماط (UI Style Guide)')

@section('content')
<div class="style-guide-container">
    
    {{-- 1. Typography --}}
    <section class="mb-5">
        <h5 class="section-title mb-4"><i class="fas fa-font me-2"></i> طباعة النصوص (Typography)</h5>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="p-4 border rounded shadow-sm bg-light-subtle">
                    <h1>العنوان الرئيسي H1 (Cairo/Tajawal)</h1>
                    <h2>عنوان مستوى H2</h2>
                    <h3>عنوان مستوى H3</h3>
                    <h4>عنوان مستوى H4</h4>
                    <h5>عنوان مستوى H5</h5>
                    <h6>عنوان مستوى H6</h6>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-4 border rounded shadow-sm bg-light-subtle">
                    <p class="lead">هذا نص تمهيدي (Lead Paragraph) يستخدم في مقدمات الصفحات.</p>
                    <p>هذا نص عادي (Normal Text) يوضح كيفية ظهور الفقرات في النظام. نحن نستخدم خط 'Tajawal' للتصفح و'Cairo' للعناوين لضمان أفضل تجربة قراءة باللغة العربية.</p>
                    <p class="text-muted">هذا نص باهت (Muted Text) يستخدم للمعلومات الثانوية.</p>
                    <p><a href="#" class="text-primary text-decoration-none">هذا رابط نصي (Primary Link)</a></p>
                </div>
            </div>
        </div>
    </section>

    <hr class="my-5 opacity-25">

    {{-- 2. Colors --}}
    <section class="mb-5">
        <h5 class="section-title mb-4"><i class="fas fa-palette me-2"></i> لوحة الألوان (Color Palette)</h5>
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="color-card">
                    <div class="color-preview" style="background-color: var(--main-dark);"></div>
                    <div class="color-info">
                        <strong>Main Dark</strong>
                        <code>var(--main-dark)</code>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="color-card">
                    <div class="color-preview" style="background: var(--accent-gradient);"></div>
                    <div class="color-info">
                        <strong>Accent Gradient</strong>
                        <code>var(--accent-gradient)</code>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="color-card">
                    <div class="color-preview" style="background-color: var(--accent-color);"></div>
                    <div class="color-info">
                        <strong>Accent Color</strong>
                        <code>var(--accent-color)</code>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="color-card">
                    <div class="color-preview" style="background-color: #10b981;"></div>
                    <div class="color-info">
                        <strong>Success</strong>
                        <code>#10b981</code>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <hr class="my-5 opacity-25">

    {{-- 3. Buttons --}}
    <section class="mb-5">
        <h5 class="section-title mb-4"><i class="fas fa-mouse me-2"></i> الأزرار (Buttons)</h5>
        <div class="d-flex flex-wrap gap-3">
            <button class="btn-premium"><i class="fas fa-crown"></i> زر فاخر (Premium)</button>
            <button class="btn btn-primary px-4 py-2 rounded-3 shadow-sm">زر أساسي (Primary)</button>
            <button class="btn btn-success px-4 py-2 rounded-3 shadow-sm">زر نجاح (Success)</button>
            <button class="btn btn-danger px-4 py-2 rounded-3 shadow-sm">زر خطر (Danger)</button>
            <button class="btn btn-warning px-4 py-2 rounded-3 shadow-sm">زر تحذير (Warning)</button>
            <button class="btn btn-info px-4 py-2 rounded-3 shadow-sm">زر معلومات (Info)</button>
            <button class="btn btn-outline-primary px-4 py-2 rounded-3">رسم حدودي (Outline)</button>
        </div>
    </section>

    <hr class="my-5 opacity-25">

    {{-- 4. Form Elements --}}
    <section class="mb-5">
        <h5 class="section-title mb-4"><i class="fas fa-edit me-2"></i> عناصر النماذج (Form Elements)</h5>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">حقل إدخال زجاجي (Glass Input)</label>
                    <input type="text" class="form-control glass-input" placeholder="أدخل نصاً هنا...">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">حقل اختيار متطور (Select2)</label>
                    <select class="form-select select2" data-placeholder="اختر خياراً...">
                        <option></option>
                        <option>الخيار الأول</option>
                        <option>الخيار الثاني</option>
                        <option>الخيار الثالث</option>
                    </select>
                </div>
            </div>
        </div>
    </section>

    <hr class="my-5 opacity-25">

    {{-- 5. Badges & Indicators --}}
    <section class="mb-5">
        <h5 class="section-title mb-4"><i class="fas fa-tag me-2"></i> الأوسمة والمؤشرات (Badges)</h5>
        <div class="d-flex flex-wrap gap-3 align-items-center">
            <span class="badge-premium">Premium Badge Role</span>
            <span class="badge rounded-pill bg-primary px-3 py-2">Badge Primary</span>
            <span class="badge rounded-pill bg-success px-3 py-2">Badge Success</span>
            <span class="badge rounded-pill bg-danger px-3 py-2">Badge Danger</span>
            <span class="badge rounded-pill bg-warning text-dark px-3 py-2">Badge Warning</span>
        </div>
    </section>

    <hr class="my-5 opacity-25">

    {{-- 6. Tables --}}
    <section class="mb-5">
        <h5 class="section-title mb-4"><i class="fas fa-table me-2"></i> الجداول (Tables)</h5>
        <div class="table-responsive">
            <table class="table premium-table">
                <thead>
                    <tr>
                        <th>المعرف</th>
                        <th>الاسم الكامل</th>
                        <th>تاريخ التسجيل</th>
                        <th>الحالة</th>
                        <th class="text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#1001</td>
                        <td>أحمد محمد علي</td>
                        <td>2024-03-30</td>
                        <td><span class="badge-premium">نشط</span></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-link text-primary"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-sm btn-link text-info"><i class="fas fa-edit"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>#1002</td>
                        <td>سارة خالد حسن</td>
                        <td>2024-03-29</td>
                        <td><span class="badge bg-warning text-dark rounded-pill">قيد الانتظار</span></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-link text-primary"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-sm btn-link text-info"><i class="fas fa-edit"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

</div>

@push('styles')
<style>
    .style-guide-container {
        padding: 1rem 0;
    }
    .section-title {
        border-right: 4px solid var(--accent-color);
        padding-right: 15px;
        font-weight: 800;
        color: var(--text-white);
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .color-card {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 12px;
        overflow: hidden;
        transition: transform 0.3s ease;
    }
    .color-card:hover {
        transform: translateY(-5px);
    }
    .color-preview {
        height: 80px;
        width: 100%;
    }
    .color-info {
        padding: 12px;
        font-size: 0.8rem;
    }
    .color-info strong {
        display: block;
        color: var(--text-white);
        margin-bottom: 4px;
    }
    .color-info code {
        color: var(--accent-color);
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Trigger select2 if needed specifically for this page guide
        $('.select2').select2({
            dir: "rtl",
            width: '100%',
            allowClear: true
        });
    });
</script>
@endpush
@endsection
