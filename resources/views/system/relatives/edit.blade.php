@extends('layouts.app')

@section('title', 'تعديل بيانات القريب')
@section('page_title', 'النظام')

@section('content')
<div class="container-fluid mx-auto px-4 py-8" dir="rtl">
    {{-- رأس الصفحة --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8 max-w-5xl mx-auto">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-amber-500 rounded-3xl shadow-xl shadow-amber-100 flex items-center justify-center text-white">
                <i class="fas fa-user-edit text-2xl"></i>
            </div>
            <div>
                <h2 class="text-3xl font-extrabold text-slate-800 header-font mb-1">تعديل بيانات القريب</h2>
                <p class="text-slate-500 text-sm">تحديث معلومات القريب: <span class="text-amber-600 font-bold">{{ $relative->full_name }}</span></p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('system.relatives.show', $relative->id) }}"
                class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2 text-sm">
                <i class="fas fa-eye"></i> عرض التفاصيل
            </a>
            <a href="{{ route('system.relatives.index') }}"
                class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2 text-sm">
                <i class="fas fa-arrow-right"></i> عودة للقائمة
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="max-w-5xl mx-auto mb-8 p-6 bg-rose-50 border border-rose-200 rounded-3xl text-rose-700">
            <div class="flex items-center gap-3 mb-2 font-bold text-base">
                <i class="fas fa-exclamation-circle text-rose-500 text-lg"></i>
                يرجى تصحيح الأخطاء التالية:
            </div>
            <ul class="list-disc list-inside text-sm space-y-1 pr-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('system.relatives.update', $relative->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="max-w-5xl mx-auto space-y-8">
            {{-- البيانات الأساسية --}}
            <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50">
                    <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                        <span class="w-2 h-6 bg-amber-500 rounded-full"></span>
                        البيانات الأساسية للقريب
                    </h5>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        {{-- اختيار الفرد --}}
                        <div class="md:col-span-2">
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">الفرد الأساسي *</label>
                            <select id="personal_data_id" name="personal_data_id" required 
                                class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 select2-selection">
                                <option value="">-- اختر الفرد --</option>
                                @foreach($personalDataList as $id => $name)
                                    <option value="{{ $id }}" {{ (old('personal_data_id') ?? $relative->personal_data_id) == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- اسم القريب الكامل --}}
                        <div class="md:col-span-1">
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">الاسم الكامل للقريب *</label>
                            <input type="text" name="full_name" required 
                                class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700" 
                                placeholder="الاسم الكامل للقريب..." value="{{ old('full_name') ?? $relative->full_name }}">
                        </div>

                        {{-- العمر --}}
                        <div class="md:col-span-1">
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">العمر</label>
                            <input type="number" name="age" min="0" max="150" 
                                class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700" 
                                placeholder="أدخل العمر بالسنوات" value="{{ old('age') ?? $relative->age }}">
                        </div>

                        {{-- صلة القرابة --}}
                        <div class="md:col-span-1">
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">صلة القرابة</label>
                            <select name="kinship_id" 
                                class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700">
                                <option value="">-- اختر صلة القرابة --</option>
                                @foreach($kinships as $id => $name)
                                    <option value="{{ $id }}" {{ (old('kinship_id') ?? $relative->kinship_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- العمل الحالي --}}
                        <div class="md:col-span-1">
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">العمل الحالي</label>
                            <input type="text" name="current_job" 
                                class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700" 
                                placeholder="المهنة أو العمل الحالي..." value="{{ old('current_job') ?? $relative->current_job }}">
                        </div>

                        {{-- الانتماء العقائدي --}}
                        <div class="md:col-span-1">
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">الانتماء العقائدي</label>
                            <select name="ideological_affiliation_id" 
                                class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700">
                                <option value="">-- اختر الانتماء العقائدي --</option>
                                @foreach($ideologicalAffiliations as $id => $name)
                                    <option value="{{ $id }}" {{ (old('ideological_affiliation_id') ?? $relative->ideological_affiliation_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- الانتماء السياسي --}}
                        <div class="md:col-span-1">
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">الانتماء السياسي</label>
                            <select name="political_affiliation_id" 
                                class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700">
                                <option value="">-- اختر الانتماء السياسي --</option>
                                @foreach($politicalAffiliations as $id => $name)
                                    <option value="{{ $id }}" {{ (old('political_affiliation_id') ?? $relative->political_affiliation_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- ملاحظات --}}
                        <div class="md:col-span-2">
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">ملاحظات إضافية</label>
                            <textarea name="notes" rows="3"
                                class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700" 
                                placeholder="أدخل أي ملاحظات إضافية...">{{ old('notes') ?? $relative->notes }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- أزرار الإجراءات --}}
            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('system.relatives.index') }}" class="px-8 py-3 text-slate-400 font-bold hover:text-slate-600 transition-colors">إلغاء</a>
                <button type="submit"
                    class="px-10 py-4 bg-amber-500 text-white rounded-[2rem] font-bold hover:bg-amber-600 transition-all shadow-xl shadow-amber-100 flex items-center gap-2">
                    <i class="fas fa-save"></i> حفظ التعديلات
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    $(document).ready(function () {
        if ($.fn.select2) {
            $('.select2-selection').select2({
                theme: 'bootstrap-5',
                dir: 'rtl',
                width: '100%'
            });
        }
    });
</script>
@endpush
@endsection
