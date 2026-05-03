@extends('layouts.app')

@section('title', 'إضافة صفحة مخصصة')
@section('content')
<div class="container-fluid mt-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold">
                <i class="fas fa-plus-circle text-primary me-2"></i>إضافة صفحة مخصصة
            </h2>
            <p class="text-muted">قم بتعريف الصفحة الأساسية أولاً ثم أضف الحقول المخصصة لها</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('system.pages.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="fas fa-arrow-right me-1"></i>عودة للقائمة
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-11">
            <form action="{{ route('system.pages.store') }}" method="POST">
                @csrf
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-gradient-primary text-white py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-cog me-2"></i>إعدادات الصفحة</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-5">
                                <label class="form-label fw-bold small text-muted">اسم الصفحة <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control form-control-lg rounded-3 shadow-sm" required placeholder="مثلاً: بلاغات المواطنين" value="{{ old('name') }}">
                                <small class="text-muted d-block mt-1"><i class="fas fa-info-circle me-1"></i>سيتم توليد الرابط (URL) تلقائياً</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-muted">القسم الإداري <span class="text-danger">*</span></label>
                                <select name="section_id" class="form-select form-select-lg rounded-3 shadow-sm" required>
                                    <option value="" selected disabled>اختر القسم الإداري</option>
                                    @foreach($sections as $sec)
                                        <option value="{{ $sec->id }}" {{ old('section_id') == $sec->id ? 'selected' : '' }}>
                                            {{ $sec->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold small text-muted">رقم الهوية (ID)</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_unique_id" value="1" id="isUniqueId">
                                    <label class="form-check-label ms-2" for="isUniqueId">معرف فريد (Unique ID)</label>
                                </div>
                            </div>
                            <div class="col-md-12 d-flex align-items-center mt-3">
                                <div class="form-check form-switch p-0 ms-4">
                                    <input class="form-check-input ms-0 me-2" type="checkbox" name="is_active" value="1" id="isActive" checked>
                                    <label class="form-check-label fw-bold" for="isActive">تفعيل الصفحة</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section: الحقول المخصصة -->
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-list me-2"></i>تعريف حقول الصفحة</h5>
                        <button type="button" class="btn btn-sm btn-light rounded-pill px-3" id="add-field">
                            <i class="fas fa-plus me-1"></i> إضافة حقل جديد
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="fields-table">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4" style="width: 250px;">اسم الحقل</th>
                                        <th style="width: 200px;">نوع البيانات</th>
                                        <th style="width: 100px;" class="text-center">إلزامي؟</th>
                                        <th>إعدادات إضافية</th>
                                        <th style="width: 100px;" class="text-center">إجراء</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="field-row">
                                        <td class="ps-4">
                                            <input type="text" name="fields[0][label]" class="form-control" required placeholder="مثلاً: الاسم الكامل">
                                        </td>
                                        <td>
                                            <select name="fields[0][type]" class="form-select field-type-select">
                                                <option value="text">بيانات (نص قصير)</option>
                                                <option value="textarea">نص (نص طويل)</option>
                                                <option value="date">تاريخ</option>
                                                <option value="datetime">تاريخ ووقت</option>
                                                <option value="number">أرقام (Int/Float/Currency)</option>
                                                <option value="checkbox">تحقق (نعم/لا)</option>
                                                <option value="select">قائمة منسدلة (خيارات)</option>
                                                <option value="link">ارتباط بصفحة أخرى</option>
                                                <option value="table">جدول فرعي (Child Table)</option>
                                                <option value="file">مرفقات / صور</option>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check d-inline-block">
                                                <input class="form-check-input" type="checkbox" name="fields[0][is_required]" value="1">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="extra-settings">
                                                <!-- options for select -->
                                                <input type="text" name="fields[0][options]" class="form-control options-input d-none" placeholder="خيار1, خيار2, خيار3">
                                                
                                                <!-- page selection for link -->
                                                <select name="fields[0][metadata][target_page_id]" class="form-select link-page-select d-none">
                                                    <option value="" disabled selected>اختر الصفحة المستهدفة</option>
                                                    @foreach($pages as $p)
                                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                                    @endforeach
                                                </select>

                                                <!-- column names for table -->
                                                <input type="text" name="fields[0][metadata][table_columns]" class="form-control table-cols-input d-none" placeholder="أسماء الأعمدة (مثلاً: الاسم, السن, العنوان)">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm rounded-circle remove-field" disabled>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mb-5">
                    <a href="{{ route('system.pages.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">إلغاء</a>
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow">
                        إنشاء الصفحة <i class="fas fa-check-circle ms-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let fieldIndex = 1;
        const addBtn = document.getElementById('add-field');
        const tableBody = document.querySelector('#fields-table tbody');

        const pagesOptions = `@foreach($pages as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach`;

        addBtn.addEventListener('click', function() {
            const row = document.createElement('tr');
            row.className = 'field-row';
            row.innerHTML = `
                <td class="ps-4">
                    <input type="text" name="fields[${fieldIndex}][label]" class="form-control" required placeholder="مثلاً: ملاحظات إضافية">
                </td>
                <td>
                    <select name="fields[${fieldIndex}][type]" class="form-select field-type-select">
                        <option value="text">بيانات (نص قصير)</option>
                        <option value="textarea">نص (نص طويل)</option>
                        <option value="date">تاريخ</option>
                        <option value="datetime">تاريخ ووقت</option>
                        <option value="number">أرقام (Int/Float/Currency)</option>
                        <option value="checkbox">تحقق (نعم/لا)</option>
                        <option value="select">قائمة منسدلة (خيارات)</option>
                        <option value="link">ارتباط بصفحة أخرى</option>
                        <option value="table">جدول فرعي (Child Table)</option>
                        <option value="file">مرفقات / صور</option>
                    </select>
                </td>
                <td class="text-center">
                    <div class="form-check d-inline-block">
                        <input class="form-check-input" type="checkbox" name="fields[${fieldIndex}][is_required]" value="1">
                    </div>
                </td>
                <td>
                    <div class="extra-settings">
                        <input type="text" name="fields[${fieldIndex}][options]" class="form-control options-input d-none" placeholder="خيار1, خيار2, خيار3">
                        <select name="fields[${fieldIndex}][metadata][target_page_id]" class="form-select link-page-select d-none">
                            <option value="" disabled selected>اختر الصفحة المستهدفة</option>
                            ${pagesOptions}
                        </select>
                        <input type="text" name="fields[${fieldIndex}][metadata][table_columns]" class="form-control table-cols-input d-none" placeholder="أسماء الأعمدة (مثلاً: الاسم, السن, العنوان)">
                    </div>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm rounded-circle remove-field">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            tableBody.appendChild(row);
            fieldIndex++;
            updateRemoveButtons();
        });

        tableBody.addEventListener('change', function(e) {
            if (e.target.classList.contains('field-type-select')) {
                const tr = e.target.closest('tr');
                const optionsInput = tr.querySelector('.options-input');
                const linkPageSelect = tr.querySelector('.link-page-select');
                const tableColsInput = tr.querySelector('.table-cols-input');
                
                // Hide all first
                optionsInput.classList.add('d-none');
                linkPageSelect.classList.add('d-none');
                tableColsInput.classList.add('d-none');

                // Show based on type
                if (e.target.value === 'select') {
                    optionsInput.classList.remove('d-none');
                } else if (e.target.value === 'link') {
                    linkPageSelect.classList.remove('d-none');
                } else if (e.target.value === 'table') {
                    tableColsInput.classList.remove('d-none');
                }
            }
        });

        tableBody.addEventListener('click', function(e) {
            if (e.target.closest('.remove-field')) {
                const row = e.target.closest('tr');
                if (document.querySelectorAll('.field-row').length > 1) {
                    row.remove();
                    updateRemoveButtons();
                }
            }
        });

        function updateRemoveButtons() {
            const buttons = document.querySelectorAll('.remove-field');
            const rowCount = buttons.length;
            buttons.forEach(btn => {
                btn.disabled = (rowCount <= 1);
            });
        }
    });
</script>
@endpush
@endsection
