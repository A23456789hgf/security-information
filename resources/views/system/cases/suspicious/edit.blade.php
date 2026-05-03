@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h2 class="mb-2">تعديل بيانات المشتبه به</h2>
                    <p class="text-muted">تعديل معلومات المشتبه به: {{ $suspicious->personalData->name ?? 'غير محدد' }}</p>
                </div>
                <div>
                    <button type="submit" form="suspectForm" class="btn btn-warning">
                        <i class="fas fa-save me-1"></i> تحديث البيانات
                    </button>
                    <a href="{{ route('system.suspicious.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i> إلغاء
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="suspectForm" action="{{ route('system.suspicious.update', $suspicious) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Section 1: Personal Data (Auto-populated) -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>البيانات الشخصية (تُحدث تلقائيًا عند اختيار الاسم)</h5>
            </div>
            <div class="card-body bg-light">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="personal_data_id" class="form-label">الاسم <span class="text-danger">*</span></label>
                        <input type="text" id="personal_data_search" class="form-control mb-2" placeholder="ابحث عن الاسم..." autocomplete="off">
                        <select class="form-select @error('personal_data_id') is-invalid @enderror" 
                                id="personal_data_id" name="personal_data_id" required>
                            <option value="">-- اختر الاسم --</option>
                            @foreach($personalDataList as $id => $name)
                                <option value="{{ $id }}" @selected(old('personal_data_id', $suspicious->personal_data_id) == $id)>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('personal_data_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                        <!-- Standardized Info-Card -->
                        <div id="personal_info_box" class="mt-4 d-none">
                            <div class="bg-blue-50 border border-blue-100 rounded-[2rem] p-6 text-xs">
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
                    </div>
                </div>
            </div>

        <!-- Section 2: Suspicion Details -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-user-secret me-2"></i>تفاصيل الاشتباه</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="suspect_number" class="form-label">رقم المشتبه به</label>
                        <input type="text" class="form-control @error('suspect_number') is-invalid @enderror" 
                               id="suspect_number" name="suspect_number" value="{{ old('suspect_number', $suspicious->suspect_number) }}">
                        @error('suspect_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="suspicion_date" class="form-label">تاريخ الاشتباه</label>
                        <input type="date" class="form-control @error('suspicion_date') is-invalid @enderror" 
                               id="suspicion_date" name="suspicion_date" value="{{ old('suspicion_date', $suspicious->suspicion_date ? $suspicious->suspicion_date->format('Y-m-d') : '') }}">
                        @error('suspicion_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="suspicion_type" class="form-label">نوع الاشتباه</label>
                        <input type="text" class="form-control @error('suspicion_type') is-invalid @enderror" 
                               id="suspicion_type" name="suspicion_type" value="{{ old('suspicion_type', $suspicious->suspicion_type) }}">
                        @error('suspicion_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="suspicion_location" class="form-label">موقع الاشتباه</label>
                        <input type="text" class="form-control @error('suspicion_location') is-invalid @enderror" 
                               id="suspicion_location" name="suspicion_location" value="{{ old('suspicion_location', $suspicious->suspicion_location) }}">
                        @error('suspicion_location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="suspicion_level" class="form-label">مستوى الاشتباه</label>
                        <select class="form-select @error('suspicion_level') is-invalid @enderror" id="suspicion_level" name="suspicion_level">
                            <option value="">-- اختر المستوى --</option>
                            <option value="منخفض" @selected(old('suspicion_level', $suspicious->suspicion_level) == 'منخفض')>منخفض</option>
                            <option value="متوسط" @selected(old('suspicion_level', $suspicious->suspicion_level) == 'متوسط')>متوسط</option>
                            <option value="عالي" @selected(old('suspicion_level', $suspicious->suspicion_level) == 'عالي')>عالي</option>
                            <option value="خطير جداً" @selected(old('suspicion_level', $suspicious->suspicion_level) == 'خطير جداً')>خطير جداً</option>
                        </select>
                        @error('suspicion_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="information_source" class="form-label">مصدر المعلومات</label>
                        <input type="text" class="form-control @error('information_source') is-invalid @enderror" 
                               id="information_source" name="information_source" value="{{ old('information_source', $suspicious->information_source) }}">
                        @error('information_source') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="is_arrested" class="form-label">هل تم القبض عليه؟</label>
                        <select class="form-select @error('is_arrested') is-invalid @enderror" id="is_arrested" name="is_arrested">
                            <option value="0" @selected(old('is_arrested', $suspicious->is_arrested) == '0')>لا</option>
                            <option value="1" @selected(old('is_arrested', $suspicious->is_arrested) == '1')>نعم</option>
                        </select>
                        @error('is_arrested') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="suspicion_follower" class="form-label">تابِع الاشتباه</label>
                        <input type="text" class="form-control @error('suspicion_follower') is-invalid @enderror" 
                               id="suspicion_follower" name="suspicion_follower" value="{{ old('suspicion_follower', $suspicious->suspicion_follower) }}">
                        @error('suspicion_follower') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="suspicion_report" class="form-label">تقرير الاشتباه</label>
                        <input type="text" class="form-control @error('suspicion_report') is-invalid @enderror" 
                               id="suspicion_report" name="suspicion_report" value="{{ old('suspicion_report', $suspicious->suspicion_report) }}">
                        @error('suspicion_report') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="suspicion_details" class="form-label">تفاصيل الاشتباه</label>
                        <textarea class="form-control @error('suspicion_details') is-invalid @enderror" 
                                  id="suspicion_details" name="suspicion_details" rows="3">{{ old('suspicion_details', $suspicious->suspicion_details) }}</textarea>
                        @error('suspicion_details') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="monitoring_results" class="form-label">نتائج مراقبة الفرد المشتبه به</label>
                        <textarea class="form-control @error('monitoring_results') is-invalid @enderror" 
                                  id="monitoring_results" name="monitoring_results" rows="3">{{ old('monitoring_results', $suspicious->monitoring_results) }}</textarea>
                        @error('monitoring_results') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="observations" class="form-label">ملاحظات</label>
                        <textarea class="form-control @error('observations') is-invalid @enderror" 
                                  id="observations" name="observations" rows="3">{{ old('observations', $suspicious->observations) }}</textarea>
                        @error('observations') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="attachments" class="form-label">المرفقات الجديدة</label>
                        <input type="file" class="form-control @error('attachments.*') is-invalid @enderror" 
                               id="attachments" name="attachments[]" multiple>
                        <small class="text-muted">يمكنك رفع ملفات إضافية (PDF, DOC, DOCX, JPG, PNG, XLS, XLSX - حد أقصى 10MB لكل ملف)</small>
                        @error('attachments.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>

                @if($suspicious->attachments && count($suspicious->attachments) > 0)
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label d-block">المرفقات الحالية</label>
                            <div class="list-group">
                                @foreach($suspicious->attachments as $index => $path)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <a href="{{ Storage::url($path) }}" target="_blank">
                                            <i class="fas fa-file-alt me-2"></i> {{ basename($path) }}
                                        </a>
                                        <form action="{{ route('system.suspicious.delete-attachment', $suspicious) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="file_index" value="{{ $index }}">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('هل أنت متأكد من حذف هذا المرفق؟')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-12 text-end">
                <button type="submit" class="btn btn-warning btn-lg shadow-sm">
                    <i class="fas fa-save me-2"></i> تحديث البيانات
                </button>
                <a href="{{ route('system.suspicious.index') }}" class="btn btn-outline-secondary btn-lg shadow-sm">
                    <i class="fas fa-times me-2"></i> إلغاء
                </a>
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
        
        selectBox.innerHTML = '<option value="">-- اختر الاسم --</option>';
        
        options.forEach(option => {
            if (option.text.toLowerCase().includes(searchTerm)) {
                selectBox.appendChild(option.cloneNode(true));
            }
        });
    });

    searchInput.addEventListener('focus', function() {
        if (this.value === '') {
            selectBox.innerHTML = '<option value="">-- اختر الاسم --</option>';
            options.forEach(option => {
                selectBox.appendChild(option.cloneNode(true));
            });
        }
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
                
                infoBox.classList.remove('d-none');
            })
            .catch(err => {
                console.error('Error:', err);
                infoBox.classList.add('d-none');
            });
    });

    // Trigger on load
    if (selectBox.value) {
        selectBox.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
