@extends('layouts.app')

@section('title', 'تعديل بيانات المشتبه به - ' . ($suspicious->personalData->name ?? ''))
@section('page_title', 'السجلات الأساسية')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto" dir="rtl">
    {{-- Breadcrumbs --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-2 print:hidden">
        <a href="{{ route('dashboard') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">الرئيسية</a>
        <span>/</span>
        <a href="{{ route('system.suspicious.index') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">المشتبه بهم</a>
        <span>/</span>
        <span class="text-slate-900 dark:text-white font-semibold">تعديل سجل المشتبه به</span>
    </nav>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-2 print:hidden">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold shadow-sm shrink-0">
                <i class="fas fa-user-edit text-xl"></i>
            </div>
            <div>
                <h1 class="font-display font-bold text-2xl text-slate-900 dark:text-white m-0">تعديل سجل: {{ $suspicious->personalData->name ?? 'غير محدد' }}</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">تحديث وتعديل بيانات الاشتباه والمتابعة الأمنية.</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('system.suspicious.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-sm rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                <i class="fas fa-arrow-right"></i>
                <span>عودة</span>
            </a>
            <button type="submit" form="suspectEditForm"
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

    <form id="suspectEditForm" action="{{ route('system.suspicious.update', $suspicious) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            {{-- Right Column: Person Info --}}
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 lg:sticky lg:top-24 transition-colors">
                    <div class="mb-5">
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">الشخص المشتبه به <span class="text-red-500">*</span></label>
                        <select id="personal_data_id" name="personal_data_id" required
                            class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none">
                            <option value="">-- اختر الشخص --</option>
                            @foreach($personalDataList as $id => $name)
                                <option value="{{ $id }}" @selected(old('personal_data_id', $suspicious->personal_data_id) == $id)>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Live Info Box --}}
                    <div id="personal_info_box" class="hidden">
                        <div class="p-4 rounded-xl bg-amber-50/50 dark:bg-amber-500/5 border border-amber-200/60 dark:border-amber-500/20 text-xs space-y-3">
                            {{-- Photo & Main Details Header --}}
                            <div class="flex items-center gap-3 p-2 bg-white dark:bg-slate-900 rounded-xl border border-amber-200/50 dark:border-amber-500/20">
                                <div class="w-14 h-14 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shrink-0 flex items-center justify-center shadow-xs">
                                    <img id="info_photo" src="" alt="الصورة الشخصية" class="w-full h-full object-cover hidden">
                                    <i id="info_photo_placeholder" class="fas fa-user text-slate-400 dark:text-slate-500 text-2xl"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <strong class="block font-bold text-amber-800 dark:text-amber-300 text-xs mb-1">
                                        <i class="fas fa-id-card me-1"></i> تفاصيل الشخص المختار:
                                    </strong>
                                    <div id="info_person_name" class="font-bold text-slate-800 dark:text-slate-200 text-xs truncate"></div>
                                    <div id="info_person_sub" class="text-[10px] text-slate-400 dark:text-slate-500 truncate"></div>
                                </div>
                            </div>

                            <div class="space-y-2 text-slate-600 dark:text-slate-400">
                                <div class="flex items-center justify-between">
                                    <span>الاسم الجهادي:</span>
                                    <span id="info_jihadi_name" class="font-bold text-slate-900 dark:text-white">-</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>الرقم العسكري:</span>
                                    <span id="info_military_number" class="font-mono font-bold text-brand-600 dark:text-brand-400">-</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>رقم الهوية:</span>
                                    <span id="info_id_number" class="font-mono font-bold text-slate-900 dark:text-white">-</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>فصيلة الدم:</span>
                                    <span id="info_blood_type" class="font-bold text-rose-600">-</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>تاريخ الميلاد:</span>
                                    <span id="info_dob" class="font-bold text-slate-900 dark:text-white">-</span>
                                </div>
                                <div class="flex items-center justify-between border-t border-amber-200/60 dark:border-amber-500/20 pt-2">
                                    <span>الهاتف:</span>
                                    <span id="info_phone_1" class="font-mono font-bold text-slate-900 dark:text-white">-</span>
                                </div>
                                <div class="border-t border-amber-200/60 dark:border-amber-500/20 pt-2">
                                    <span class="block mb-1">العنوان:</span>
                                    <span id="info_address" class="font-bold text-slate-900 dark:text-white block">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Left Column: Suspicion Details --}}
            <div class="lg:col-span-7 space-y-6">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center gap-3 bg-slate-50/50 dark:bg-slate-800/30">
                        <div class="w-2 h-5 bg-amber-500 rounded-full"></div>
                        <h2 class="font-display font-bold text-base text-slate-900 dark:text-white m-0">تفاصيل حالة الاشتباه</h2>
                    </div>
                    
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">رقم المشتبه به</label>
                            <input type="text" name="suspect_number" 
                                class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm font-mono text-slate-900 dark:text-white"
                                value="{{ old('suspect_number', $suspicious->suspect_number) }}">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">تاريخ الاشتباه</label>
                            <input type="date" name="suspicion_date" 
                                class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white"
                                value="{{ old('suspicion_date', $suspicious->suspicion_date ? $suspicious->suspicion_date->format('Y-m-d') : '') }}">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">نوع الاشتباه</label>
                            <input type="text" name="suspicion_type" 
                                class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white"
                                value="{{ old('suspicion_type', $suspicious->suspicion_type) }}">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">مستوى الخطورة</label>
                            <select name="suspicion_level" 
                                class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white">
                                <option value="">-- اختر المستوى --</option>
                                <option value="منخفض" @selected(old('suspicion_level', $suspicious->suspicion_level) == 'منخفض')>منخفض</option>
                                <option value="متوسط" @selected(old('suspicion_level', $suspicious->suspicion_level) == 'متوسط')>متوسط</option>
                                <option value="عالي" @selected(old('suspicion_level', $suspicious->suspicion_level) == 'عالي')>عالي</option>
                                <option value="خطير جداً" @selected(old('suspicion_level', $suspicious->suspicion_level) == 'خطير جداً')>خطير جداً</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">موقع الاشتباه</label>
                            <input type="text" name="suspicion_location" 
                                class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white"
                                value="{{ old('suspicion_location', $suspicious->suspicion_location) }}">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">مصدر المعلومات</label>
                            <input type="text" name="information_source" 
                                class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white"
                                value="{{ old('information_source', $suspicious->information_source) }}">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">هل تم القبض عليه؟</label>
                            <select name="is_arrested" required
                                class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white">
                                <option value="0" @selected(old('is_arrested', $suspicious->is_arrested) == '0')>لا</option>
                                <option value="1" @selected(old('is_arrested', $suspicious->is_arrested) == '1')>نعم</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">متابع الاشتباه</label>
                            <input type="text" name="suspicion_follower" 
                                class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white"
                                value="{{ old('suspicion_follower', $suspicious->suspicion_follower) }}">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">تفاصيل الاشتباه</label>
                            <textarea name="suspicion_details" rows="3"
                                class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white">{{ old('suspicion_details', $suspicious->suspicion_details) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
                    <a href="{{ route('system.suspicious.index') }}"
                        class="px-6 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 font-semibold transition-colors text-sm">
                        إلغاء
                    </a>
                    <button type="submit"
                        class="px-8 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm shadow-sm shadow-brand-500/30 transition-all flex items-center gap-2">
                        <i class="fas fa-check"></i>
                        <span>حفظ التعديلات</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        const personSelect = $('#personal_data_id');
        const infoBox = $('#personal_info_box');

        personSelect.on('change', function() {
            const personId = $(this).val();
            if (!personId) {
                infoBox.addClass('hidden');
                return;
            }

            fetch(`/system/personal-data/details/${personId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.photo_url) {
                        $('#info_photo').attr('src', data.photo_url).removeClass('hidden');
                        $('#info_photo_placeholder').addClass('hidden');
                    } else {
                        $('#info_photo').attr('src', '').addClass('hidden');
                        $('#info_photo_placeholder').removeClass('hidden');
                    }
                    $('#info_person_name').text(data.name || '-');
                    $('#info_person_sub').text((data.military_number && data.military_number !== '-' ? 'الرقم العسكري: ' + data.military_number : '') + (data.jihadi_name && data.jihadi_name !== '-' ? ' | ' + data.jihadi_name : ''));
                    $('#info_jihadi_name').text(data.jihadi_name || '-');
                    $('#info_military_number').text(data.military_number || '-');
                    $('#info_id_number').text(data.national_id_number || '-');
                    $('#info_blood_type').text(data.blood_type || '-');
                    $('#info_dob').text(data.date_of_birth || '-');
                    $('#info_phone_1').text(data.phone_1 || '-');
                    $('#info_address').text(data.address || '-');

                    infoBox.removeClass('hidden');
                })
                .catch(err => {
                    console.error('Error fetching details:', err);
                    infoBox.addClass('hidden');
                });
        });

        if (personSelect.val()) {
            personSelect.trigger('change');
        }
    });
</script>
@endpush

