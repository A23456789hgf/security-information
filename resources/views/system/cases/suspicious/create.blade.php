@extends('layouts.app')

@section('content')
<div class="container-fluid mx-auto px-4 py-8" dir="rtl">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-rose-600 rounded-3xl shadow-xl shadow-rose-100 flex items-center justify-center text-white">
                <i class="fas fa-user-secret text-2xl"></i>
            </div>
            <div>
                <h2 class="text-3xl font-extrabold text-slate-800 header-font mb-1">إضافة مشتبه به</h2>
                <p class="text-slate-500 text-sm">تسجيل بيانات حالة اشتباه جديدة وربطها بملف شخصي.</p>
            </div>
        </div>
        <a href="{{ route('system.suspicious.index') }}"
            class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
            <i class="fas fa-arrow-right"></i> عودة للقائمة
        </a>
    </div>

    <form id="suspectForm" action="{{ route('system.suspicious.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                {{-- Right Column: Person Selection & Info Card --}}
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                        <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50">
                            <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                                <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                                اختيار الشخص
                            </h5>
                        </div>
                        <div class="p-8">
                            <div class="mb-6">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">ابحث عن الاسم واختاره *</label>
                                <select id="personal_data_id" name="personal_data_id" required
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-bold text-slate-700 select2-selection">
                                    <option value="">-- اختر الاسم --</option>
                                    @foreach($personalDataList as $id => $name)
                                        <option value="{{ $id }}" @selected(old('personal_data_id') == $id)>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Standardized Info-Card --}}
                            <div id="personal_info_box" class="hidden">
                                <div class="bg-blue-50 border border-blue-100 rounded-[2rem] p-6 text-xs mb-4">
                                    <h6 class="font-bold text-blue-700 mb-4 flex items-center gap-2">
                                        <i class="fas fa-info-circle"></i> تفاصيل الشخص المختار:
                                    </h6>
                                    <div class="grid grid-cols-1 gap-y-3 text-slate-700">
                                        <div class="flex items-center justify-between"><span class="text-slate-400">الاسم الجهادي:</span> <span id="info_jihadi_name" class="font-bold">-</span></div>
                                        <div class="flex items-center justify-between"><span class="text-slate-400">الرقم العسكري:</span> <span id="info_military_number" class="font-bold">-</span></div>
                                        <div class="flex items-center justify-between"><span class="text-slate-400">رقم الهوية:</span> <span id="info_id_number" class="font-bold">-</span></div>
                                        <div class="flex items-center justify-between"><span class="text-slate-400">فصيلة الدم:</span> <span id="info_blood_type" class="text-rose-600 font-extrabold">-</span></div>
                                        <div class="flex items-center justify-between"><span class="text-slate-400">تاريخ الميلاد:</span> <span id="info_dob" class="font-bold">-</span></div>
                                        <div class="flex items-center justify-between"><span class="text-slate-400">الحالة الاجتماعية:</span> <span id="info_marital_status" class="font-bold">-</span></div>
                                        <div class="flex items-center justify-between border-t pt-2"><span class="text-slate-400">هاتف 1:</span> <span id="info_phone_1" class="font-bold">-</span></div>
                                        <div class="flex flex-col border-t pt-2"><span class="text-slate-400">العنوان:</span> <span id="info_address" class="font-bold">-</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Left Column: Suspicion Details --}}
                <div class="lg:col-span-7 space-y-6">
                    <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                        <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50">
                            <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                                <span class="w-2 h-6 bg-rose-500 rounded-full"></span>
                                تفاصيل حالة الاشتباه
                            </h5>
                        </div>
                        <div class="p-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block px-2">رقم المشتبه به</label>
                                    <input type="text" name="suspect_number" 
                                        class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-rose-100 transition-all font-bold text-slate-700"
                                        placeholder="رقم تلقائي أو يدوي" value="{{ old('suspect_number') }}">
                                </div>

                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block px-2">تاريخ الاشتباه</label>
                                    <input type="date" name="suspicion_date" 
                                        class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-rose-100 transition-all font-bold text-slate-700"
                                        value="{{ old('suspicion_date') }}">
                                </div>

                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block px-2">نوع الاشتباه</label>
                                    <input type="text" name="suspicion_type" 
                                        class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-rose-100 transition-all font-bold text-slate-700"
                                        placeholder="مثال: انتماء، تعاون..." value="{{ old('suspicion_type') }}">
                                </div>

                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block px-2">مستوى الاشتباه</label>
                                    <select name="suspicion_level" 
                                        class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-rose-100 transition-all font-bold text-slate-700">
                                        <option value="">-- اختر المستوى --</option>
                                        <option value="منخفض" @selected(old('suspicion_level') == 'منخفض')>منخفض</option>
                                        <option value="متوسط" @selected(old('suspicion_level') == 'متوسط')>متوسط</option>
                                        <option value="عالي" @selected(old('suspicion_level') == 'عالي')>عالي</option>
                                        <option value="خطير جداً" @selected(old('suspicion_level') == 'خطير جداً')>خطير جداً</option>
                                    </select>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="text-xs font-bold text-slate-400 mb-2 block px-2">موقع الاشتباه</label>
                                    <input type="text" name="suspicion_location" 
                                        class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-rose-100 transition-all font-bold text-slate-700"
                                        placeholder="المنطقة أو الإحداثيات" value="{{ old('suspicion_location') }}">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="text-xs font-bold text-slate-400 mb-2 block px-2">مصدر المعلومات</label>
                                    <input type="text" name="information_source" 
                                        class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-rose-100 transition-all font-bold text-slate-700"
                                        placeholder="جهة أو شخص" value="{{ old('information_source') }}">
                                </div>

                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block px-2">هل تم القبض عليه؟</label>
                                    <select name="is_arrested" required
                                        class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-rose-100 transition-all font-bold text-slate-700">
                                        <option value="0" @selected(old('is_arrested') == '0')>لا</option>
                                        <option value="1" @selected(old('is_arrested') == '1')>نعم</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block px-2">تابِع الاشتباه</label>
                                    <input type="text" name="suspicion_follower" 
                                        class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-rose-100 transition-all font-bold text-slate-700"
                                        placeholder="المكلف بالمتابعة" value="{{ old('suspicion_follower') }}">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="text-xs font-bold text-slate-400 mb-2 block px-2">تفاصيل الاشتباه</label>
                                    <textarea name="suspicion_details" rows="3"
                                        class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-rose-100 transition-all font-bold text-slate-700"
                                        placeholder="شرح مفصل لحالة الاشتباه...">{{ old('suspicion_details') }}</textarea>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="text-xs font-bold text-slate-400 mb-2 block px-2">نتائج المراقبة</label>
                                    <textarea name="monitoring_results" rows="3"
                                        class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-rose-100 transition-all font-bold text-slate-700"
                                        placeholder="ما تم رصده خلال فترة المتابعة...">{{ old('monitoring_results') }}</textarea>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="text-xs font-bold text-slate-400 mb-2 block px-2">المرفقات (صور، مستندات)</label>
                                    <div class="relative group">
                                        <input type="file" name="attachments[]" multiple
                                            class="w-full px-6 py-4 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl group-hover:border-rose-300 transition-all cursor-pointer">
                                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none text-slate-400 text-sm font-bold">
                                            <i class="fas fa-cloud-upload-alt me-2 text-rose-400"></i> اضغط أو اسحب الملفات هنا
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-2 px-2 italic">PDF, JPG, PNG, DOCX (Max 10MB)</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-8 border-top bg-slate-50/30 text-end">
                            <button type="submit"
                                class="px-10 py-4 bg-rose-600 text-white rounded-[2rem] font-bold hover:bg-rose-700 transition-all shadow-xl shadow-rose-100 flex items-center gap-2 ms-auto text-lg">
                                <i class="fas fa-check-circle"></i> حفظ بيانات المشتبه به
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        const personSelect = $('#personal_data_id');
        const infoBox = $('#personal_info_box');

        if ($.fn.select2) {
            $('.select2-selection').select2({
                theme: 'bootstrap-5',
                dir: 'rtl',
                width: '100%'
            });
        }

        personSelect.on('change', function() {
            const personId = $(this).val();
            if (!personId) {
                infoBox.addClass('hidden');
                return;
            }

            fetch(`/system/personal-data/details/${personId}`)
                .then(res => res.json())
                .then(data => {
                    $('#info_jihadi_name').text(data.jihadi_name || '-');
                    $('#info_military_number').text(data.military_number || '-');
                    $('#info_id_number').text(data.national_id_number || '-');
                    $('#info_blood_type').text(data.blood_type || '-');
                    $('#info_dob').text(data.date_of_birth || '-');
                    $('#info_marital_status').text(data.marital_status || '-');
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
@endsection
