@extends('layouts.app')

@section('title', 'تعديل البلاغ الأمني')

@section('content')
<div class="container-fluid mt-4">
    <form action="{{ route('system.security-reports.update', $securityReport) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded shadow-sm">
                    <div>
                        <h2 class="mb-0 text-warning"><i class="fas fa-edit me-2"></i>تعديل البلاغ الأمني</h2>
                        <p class="text-muted mb-0">تعديل بيانات البلاغ الخاص بـ: <strong>{{ $securityReport->full_name }}</strong></p>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning px-4">
                            <i class="fas fa-save me-1"></i> تحديث البيانات
                        </button>
                        <a href="{{ route('system.security-reports.index') }}" class="btn btn-outline-secondary px-4">
                            <i class="fas fa-times me-1"></i> إلغاء
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger shadow-sm mb-4">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Section 1: البيانات الأساسية -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>البيانات الأساسية وتفاصيل البلاغ</h5>
            </div>
            <div class="card-body bg-light/30">
                <div class="row g-3 mb-4">
                    <div class="col-md-12">
                        <label class="form-label fw-bold">البحث عن شخص واختياره</label>
                        <input type="text" id="personal_data_search" class="form-control mb-2" placeholder="ابحث عن الاسم..." autocomplete="off">
                        <select id="personal_data_id" class="form-select fw-bold">
                            <option value="">-- اختر الشخص لعرض بياناته وتعبئة الحقول --</option>
                            @foreach($personalDataList as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Standardized Info-Card -->
                <div id="personal_info_box" class="mt-4 d-none">
                    <div class="bg-blue-50 border border-blue-100 rounded-[2rem] p-6 text-xs mb-4">
                        <h6 class="font-bold text-blue-700 mb-4 flex items-center gap-2">
                            <i class="fas fa-info-circle"></i> بيانات الشخص المختار:
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 text-slate-700">
                            <div class="flex flex-col"><span class="text-slate-400">الاسم الجهادي:</span> <span id="info_jihadi_name" class="font-bold">-</span></div>
                            <div class="flex flex-col"><span class="text-slate-400">الرقم العسكري:</span> <span id="info_military_number" class="font-bold">-</span></div>
                            <div class="flex flex-col"><span class="text-slate-400">رقم الهوية:</span> <span id="info_id_number" class="font-bold">-</span></div>
                            <div class="flex flex-col"><span class="text-slate-400">فصيلة الدم:</span> <span id="info_blood_type" class="text-rose-600 font-extrabold">-</span></div>
                            <div class="flex flex-col"><span class="text-slate-400">تاريخ الميلاد:</span> <span id="info_dob" class="font-bold">-</span></div>
                            <div class="flex flex-col"><span class="text-slate-400">الحالة الاجتماعية:</span> <span id="info_marital_status" class="font-bold">-</span></div>
                            <div class="lg:col-span-2 flex flex-col"><span class="text-slate-400">مكان الميلاد:</span> <span id="info_pob" class="font-bold">-</span></div>
                            <div class="lg:col-span-2 flex flex-col"><span class="text-slate-400">رقم الهاتف 1:</span> <span id="info_phone_1" class="font-bold">-</span></div>
                            <div class="lg:col-span-2 flex flex-col"><span class="text-slate-400">رقم الهاتف 2:</span> <span id="info_phone_2" class="font-bold">-</span></div>
                            <div class="md:col-span-3 lg:col-span-4 border-top pt-2 flex flex-col"><span class="text-slate-400">العنوان:</span> <span id="info_address" class="font-bold">-</span></div>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">الاسم الكامل واللقب <span class="text-danger">*</span></label>
                        <input type="text" name="full_name" id="full_name" class="form-control" required value="{{ old('full_name', $securityReport->full_name) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">اسم البلاغ / الموضوع <span class="text-danger">*</span></label>
                        <input type="text" name="report_name" class="form-control" required value="{{ old('report_name', $securityReport->report_name) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">نوع البلاغ</label>
                        <input type="text" name="report_type" class="form-control" value="{{ old('report_type', $securityReport->report_type) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">تاريخ البلاغ</label>
                        <input type="date" name="report_date" class="form-control" value="{{ old('report_date', $securityReport->report_date ? $securityReport->report_date->format('Y-m-d') : '') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">الرقم العسكري</label>
                        <input type="text" name="military_number" id="military_number" class="form-control" value="{{ old('military_number', $securityReport->military_number) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">رقم البطاقة</label>
                        <input type="text" name="id_number" id="id_number" class="form-control" value="{{ old('id_number', $securityReport->id_number) }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: الوحدة والعمل -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0"><i class="fas fa-sitemap me-2"></i>بيانات الوحدة والجهة</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">الوحدة</label>
                        <input type="text" name="unit" class="form-control" value="{{ old('unit', $securityReport->unit) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">الوحدة الفرعية</label>
                        <input type="text" name="sub_unit" class="form-control" value="{{ old('sub_unit', $securityReport->sub_unit) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">الوحدة التفصيلية</label>
                        <input type="text" name="detailed_unit" class="form-control" value="{{ old('detailed_unit', $securityReport->detailed_unit) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">الرتبة</label>
                        <input type="text" name="rank" class="form-control" value="{{ old('rank', $securityReport->rank) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">التعيين</label>
                        <input type="text" name="designation" class="form-control" value="{{ old('designation', $securityReport->designation) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">المسمى الوظيفي</label>
                        <input type="text" name="job_title" class="form-control" value="{{ old('job_title', $securityReport->job_title) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">الراتب</label>
                        <input type="text" name="salary" class="form-control" value="{{ old('salary', $securityReport->salary) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">العمل / الجهة</label>
                        <select name="internal_entity_id" class="form-select">
                            <option value="">-- اختر الجهة --</option>
                            @foreach($entities as $entity)
                                <option value="{{ $entity->id }}" @selected(old('internal_entity_id', $securityReport->internal_entity_id) == $entity->id)>{{ $entity->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">الانتماء</label>
                        <input type="text" name="affiliation" class="form-control" value="{{ old('affiliation', $securityReport->affiliation) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">الهاتف</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $securityReport->phone) }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: السكن والأصل -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-success text-white py-3">
                <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>بيانات الأصل والسكن</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <p class="fw-bold border-bottom pb-2">بيانات الأصل</p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">الأصل / المحافظة</label>
                        <input type="text" name="original_governorate" class="form-control" value="{{ old('original_governorate', $securityReport->original_governorate) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">الأصل / المديرية</label>
                        <input type="text" name="original_directorate" class="form-control" value="{{ old('original_directorate', $securityReport->original_directorate) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">الأصل / القرية</label>
                        <input type="text" name="original_village" class="form-control" value="{{ old('original_village', $securityReport->original_village) }}">
                    </div>

                    <div class="col-md-12 mt-4">
                        <p class="fw-bold border-bottom pb-2">بيانات السكن</p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">السكن / المحافظة</label>
                        <input type="text" name="residence_governorate" class="form-control" value="{{ old('residence_governorate', $securityReport->residence_governorate) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">السكن / المديرية</label>
                        <input type="text" name="residence_directorate" class="form-control" value="{{ old('residence_directorate', $securityReport->residence_directorate) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">السكن / القرية</label>
                        <input type="text" name="residence_village" class="form-control" value="{{ old('residence_village', $securityReport->residence_village) }}">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-bold">المنطقة</label>
                        <input type="text" name="area" class="form-control" value="{{ old('area', $securityReport->area) }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 4: بيانات شخصية إضافية -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-secondary text-white py-3">
                <h5 class="mb-0"><i class="fas fa-user-tag me-2"></i>بيانات شخصية واجتماعية</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">تاريخ الميلاد</label>
                        <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $securityReport->date_of_birth ? $securityReport->date_of_birth->format('Y-m-d') : '') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">العمر</label>
                        <input type="text" name="age" class="form-control" value="{{ old('age', $securityReport->age) }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">الجنس</label>
                        <select name="gender" class="form-select">
                            <option value="">-- اختر --</option>
                            <option value="ذكـر" @selected(old('gender', $securityReport->gender) == 'ذكـر')>ذكـر</option>
                            <option value="أنثى" @selected(old('gender', $securityReport->gender) == 'أنثى')>أنثى</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">الحالة الاجتماعية</label>
                        <input type="text" name="marital_status" class="form-control" value="{{ old('marital_status', $securityReport->marital_status) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">عدد الأطفال</label>
                        <input type="text" name="number_of_children" class="form-control" value="{{ old('number_of_children', $securityReport->number_of_children) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">المؤهل الدراسي</label>
                        <input type="text" name="qualification" class="form-control" value="{{ old('qualification', $securityReport->qualification) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">الحالة التعليمية</label>
                        <input type="text" name="educational_status" class="form-control" value="{{ old('educational_status', $securityReport->educational_status) }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 5: الحالة الجنائية والإجرائية -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-danger text-white py-3">
                <h5 class="mb-0"><i class="fas fa-balance-scale me-2"></i>الحالة القانونية والإجراءات</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">حالة القبض</label>
                        <input type="text" name="arrest_status" class="form-control" value="{{ old('arrest_status', $securityReport->arrest_status ?: $securityReport->status_of_arrest) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">تاريخ القبض</label>
                        <input type="date" name="arrest_date" class="form-control" value="{{ old('arrest_date', $securityReport->arrest_date ? $securityReport->arrest_date->format('Y-m-d') : '') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">تاريخ الإيداع</label>
                        <input type="date" name="deposit_date" class="form-control" value="{{ old('deposit_date', $securityReport->deposit_date ? $securityReport->deposit_date->format('Y-m-d') : '') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">تصنيف القضية</label>
                        <input type="text" name="case_classification" class="form-control" value="{{ old('case_classification', $securityReport->case_classification) }}">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label fw-bold">موافاة القضية</label>
                        <input type="text" name="case_fate" class="form-control" value="{{ old('case_fate', $securityReport->case_fate) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">التهمة</label>
                        <textarea name="charge" class="form-control" rows="2">{{ old('charge', $securityReport->charge) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">الإجراءات</label>
                        <textarea name="procedures" class="form-control" rows="2">{{ old('procedures', $securityReport->procedures) }}</textarea>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">القسم / المركز</label>
                        <input type="text" name="police_station" class="form-control" value="{{ old('police_station', $securityReport->police_station) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">جهة الإطلاق</label>
                        <input type="text" name="source_of_release" class="form-control" value="{{ old('source_of_release', $securityReport->source_of_release) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">تاريخ الإطلاق</label>
                        <input type="date" name="date_of_release" class="form-control" value="{{ old('date_of_release', $securityReport->date_of_release ? $securityReport->date_of_release->format('Y-m-d') : '') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">الحالة</label>
                        <input type="text" name="status" class="form-control" value="{{ old('status', $securityReport->status) }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 6: النشاط والأصول والوسيط -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-info text-white py-3">
                <h5 class="mb-0"><i class="fas fa-briefcase me-2"></i>النشاط والأصول المتموضعة</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">النشاط الحالي</label>
                        <textarea name="current_activity" class="form-control" rows="2">{{ old('current_activity', $securityReport->current_activity) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">الأصول المتموضعة</label>
                        <textarea name="positional_assets" class="form-control" rows="2">{{ old('positional_assets', $securityReport->positional_assets) }}</textarea>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">اسم الوسيط</label>
                        <input type="text" name="broker_name" class="form-control" value="{{ old('broker_name', $securityReport->broker_name) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">رقم الوسيط</label>
                        <input type="text" name="broker_number" class="form-control" value="{{ old('broker_number', $securityReport->broker_number) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">جهة العمليات</label>
                        <input type="text" name="front_of_operation" class="form-control" value="{{ old('front_of_operation', $securityReport->front_of_operation) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">الموقع لدى العدو</label>
                        <input type="text" name="position_with_enemy" class="form-control" value="{{ old('position_with_enemy', $securityReport->position_with_enemy) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">التصنيف</label>
                        <input type="text" name="classification" class="form-control" value="{{ old('classification', $securityReport->classification) }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 7: ملاحظات -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-warning py-3">
                <h5 class="mb-0"><i class="fas fa-sticky-note me-2"></i>ملاحظات إضافية</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">ملاحظات (1)</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $securityReport->notes) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">ملاحظات (2)</label>
                        <textarea name="additional_notes" class="form-control" rows="3">{{ old('additional_notes', $securityReport->additional_notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-md-12 text-end">
                <button type="submit" class="btn btn-warning btn-lg px-5 shadow-sm">
                    <i class="fas fa-save me-2"></i> تحديث البلاغ
                </button>
            </div>
        </div>
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality for personal data dropdown
    const searchInput = document.getElementById('personal_data_search');
    const selectBox = document.getElementById('personal_data_id');
    const options = Array.from(selectBox.options).slice(1);

    searchInput.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        selectBox.innerHTML = '<option value="">-- اختر الشخص لعرض بياناته وتعبئة الحقول --</option>';
        options.forEach(option => {
            if (option.text.toLowerCase().includes(searchTerm)) {
                selectBox.appendChild(option.cloneNode(true));
            }
        });
    });

    // Auto-populate personal data when selecting a person
    selectBox.addEventListener('change', function() {
        const personId = this.value;
        const infoBox = document.getElementById('personal_info_box');
        
        if (!personId) {
            infoBox.classList.add('d-none');
            return;
        }

        // Standardized fetch
        fetch(`/system/personal-data/details/${personId}`)
            .then(res => res.json())
            .then(data => {
                // Populate Info Card
                document.getElementById('info_jihadi_name').textContent = data.jihadi_name || '-';
                document.getElementById('info_military_number').textContent = data.military_number || '-';
                document.getElementById('info_id_number').textContent = data.national_id_number || '-';
                document.getElementById('info_blood_type').textContent = data.blood_type || '-';
                document.getElementById('info_dob').textContent = data.date_of_birth || '-';
                document.getElementById('info_marital_status').textContent = data.marital_status || '-';
                document.getElementById('info_pob').textContent = data.place_of_birth || '-';
                document.getElementById('info_phone_1').textContent = data.phone_1 || '-';
                document.getElementById('info_phone_2').textContent = data.phone_2 || '-';
                document.getElementById('info_address').textContent = data.address || '-';
                
                // Auto-fill report fields
                document.getElementById('full_name').value = data.name || '';
                document.getElementById('military_number').value = data.military_number || '';
                document.getElementById('id_number').value = data.national_id_number || '';
                
                infoBox.classList.remove('d-none');
            })
            .catch(err => {
                console.error('Error:', err);
                infoBox.classList.add('d-none');
            });
    });
});
</script>
@endsection
