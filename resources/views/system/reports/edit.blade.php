@extends('layouts.app')

@section('title', 'تعديل البلاغ الأمني - ' . ($securityReport->full_name ?? $securityReport->name))
@section('page_title', 'السجلات الأساسية')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto" dir="rtl">
    {{-- Breadcrumbs --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-2 print:hidden">
        <a href="{{ route('dashboard') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">الرئيسية</a>
        <span>/</span>
        <a href="{{ route('system.security-reports.index') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">البلاغات الأمنية</a>
        <span>/</span>
        <span class="text-slate-900 dark:text-white font-semibold">تعديل البلاغ</span>
    </nav>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-2 print:hidden">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold shadow-sm shrink-0">
                <i class="fas fa-edit text-xl"></i>
            </div>
            <div>
                <h1 class="font-display font-bold text-2xl text-slate-900 dark:text-white m-0">تعديل البلاغ: {{ $securityReport->full_name }}</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">تحديث وتعديل بيانات البلاغ والجهة المسجلة.</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('system.security-reports.show', $securityReport) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 font-bold text-sm rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors shadow-sm">
                <i class="fas fa-eye text-brand-600"></i>
                <span>عرض</span>
            </a>
            <a href="{{ route('system.security-reports.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-sm rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                <i class="fas fa-arrow-right"></i>
                <span>عودة</span>
            </a>
            <button type="submit" form="reportEditForm"
                class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm shadow-sm shadow-brand-500/30 transition-all flex items-center gap-2">
                <i class="fas fa-save"></i>
                <span>حفظ التعديلات</span>
            </button>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 text-red-800 dark:text-red-300 p-4 rounded-xl text-xs">
            <ul class="mb-0 list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="reportEditForm" action="{{ route('system.security-reports.update', $securityReport) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Section 1: Basic Info --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center gap-3 bg-slate-50/50 dark:bg-slate-800/30">
                <div class="w-2 h-5 bg-brand-600 rounded-full"></div>
                <h2 class="font-display font-bold text-base text-slate-900 dark:text-white m-0">البيانات الأساسية وتفاصيل البلاغ</h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">الاسم الكامل واللقب <span class="text-red-500">*</span></label>
                    <input type="text" name="full_name" id="full_name" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white" required value="{{ old('full_name', $securityReport->full_name) }}">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">موضوع البلاغ / العنوان <span class="text-red-500">*</span></label>
                    <input type="text" name="report_name" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white" required value="{{ old('report_name', $securityReport->report_name) }}">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">نوع البلاغ</label>
                    <input type="text" name="report_type" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white" value="{{ old('report_type', $securityReport->report_type) }}">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">تاريخ البلاغ</label>
                    <input type="date" name="report_date" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white" value="{{ old('report_date', $securityReport->report_date ? $securityReport->report_date->format('Y-m-d') : '') }}">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">الرقم العسكري</label>
                    <input type="text" name="military_number" id="military_number" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm font-mono text-slate-900 dark:text-white" value="{{ old('military_number', $securityReport->military_number) }}">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">رقم البطاقة (الهوية)</label>
                    <input type="text" name="id_number" id="id_number" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm font-mono text-slate-900 dark:text-white" value="{{ old('id_number', $securityReport->id_number) }}">
                </div>
            </div>
        </div>

        {{-- Section 2: Unit and Agency --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center gap-3 bg-slate-50/50 dark:bg-slate-800/30">
                <div class="w-2 h-5 bg-emerald-500 rounded-full"></div>
                <h2 class="font-display font-bold text-base text-slate-900 dark:text-white m-0">بيانات الوحدة والجهة</h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">الوحدة</label>
                    <input type="text" name="unit" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white" value="{{ old('unit', $securityReport->unit) }}">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">الوحدة الفرعية</label>
                    <input type="text" name="sub_unit" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white" value="{{ old('sub_unit', $securityReport->sub_unit) }}">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">الوحدة التفصيلية</label>
                    <input type="text" name="detailed_unit" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white" value="{{ old('detailed_unit', $securityReport->detailed_unit) }}">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">الرتبة</label>
                    <input type="text" name="rank" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white" value="{{ old('rank', $securityReport->rank) }}">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">التعيين</label>
                    <input type="text" name="designation" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white" value="{{ old('designation', $securityReport->designation) }}">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">المسمى الوظيفي</label>
                    <input type="text" name="job_title" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white" value="{{ old('job_title', $securityReport->job_title) }}">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">الراتب</label>
                    <input type="text" name="salary" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white" value="{{ old('salary', $securityReport->salary) }}">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">العمل / الجهة</label>
                    <select name="internal_entity_id" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white">
                        <option value="">-- اختر الجهة --</option>
                        @foreach($entities as $entity)
                            <option value="{{ $entity->id }}" @selected(old('internal_entity_id', $securityReport->internal_entity_id) == $entity->id)>{{ $entity->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">الهاتف</label>
                    <input type="text" name="phone" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm font-mono text-slate-900 dark:text-white" value="{{ old('phone', $securityReport->phone) }}">
                </div>
            </div>
        </div>

        {{-- Section 3: Origin & Residence --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center gap-3 bg-slate-50/50 dark:bg-slate-800/30">
                <div class="w-2 h-5 bg-amber-500 rounded-full"></div>
                <h2 class="font-display font-bold text-base text-slate-900 dark:text-white m-0">بيانات الأصل والسكن</h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">الأصل / المحافظة</label>
                    <input type="text" name="original_governorate" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white" value="{{ old('original_governorate', $securityReport->original_governorate) }}">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">الأصل / المديرية</label>
                    <input type="text" name="original_directorate" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white" value="{{ old('original_directorate', $securityReport->original_directorate) }}">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">الأصل / القرية</label>
                    <input type="text" name="original_village" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white" value="{{ old('original_village', $securityReport->original_village) }}">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">السكن / المحافظة</label>
                    <input type="text" name="residence_governorate" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white" value="{{ old('residence_governorate', $securityReport->residence_governorate) }}">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">السكن / المديرية</label>
                    <input type="text" name="residence_directorate" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white" value="{{ old('residence_directorate', $securityReport->residence_directorate) }}">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">السكن / القرية</label>
                    <input type="text" name="residence_village" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white" value="{{ old('residence_village', $securityReport->residence_village) }}">
                </div>
            </div>
        </div>

        {{-- Section 4: Legal Status --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center gap-3 bg-slate-50/50 dark:bg-slate-800/30">
                <div class="w-2 h-5 bg-rose-500 rounded-full"></div>
                <h2 class="font-display font-bold text-base text-slate-900 dark:text-white m-0">الحالة القانونية والإجراءات</h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">حالة القبض</label>
                    <input type="text" name="arrest_status" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white" value="{{ old('arrest_status', $securityReport->arrest_status ?: $securityReport->status_of_arrest) }}">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">تاريخ القبض</label>
                    <input type="date" name="arrest_date" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white" value="{{ old('arrest_date', $securityReport->arrest_date ? $securityReport->arrest_date->format('Y-m-d') : '') }}">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">تاريخ الإيداع</label>
                    <input type="date" name="deposit_date" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white" value="{{ old('deposit_date', $securityReport->deposit_date ? $securityReport->deposit_date->format('Y-m-d') : '') }}">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">التهمة المنسوبة</label>
                    <textarea name="charge" rows="2" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white">{{ old('charge', $securityReport->charge) }}</textarea>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">الإجراءات المتخذة</label>
                    <textarea name="procedures" rows="2" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white">{{ old('procedures', $securityReport->procedures) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
            <a href="{{ route('system.security-reports.index') }}"
                class="px-6 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 font-semibold transition-colors text-sm">
                إلغاء
            </a>
            <button type="submit"
                class="px-8 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm shadow-sm shadow-brand-500/30 transition-all flex items-center gap-2">
                <i class="fas fa-check"></i>
                <span>حفظ التعديلات</span>
            </button>
        </div>
    </form>
</div>
@endsection
