@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold">
                <i class="fas fa-edit text-primary me-2"></i>تعديل البيانات الثانوية
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('system.secondary-data.index') }}">البيانات الثانوية</a></li>
                    <li class="breadcrumb-item active" aria-current="page">تعديل</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('system.secondary-data.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="fas fa-arrow-right me-1"></i>رجوع للكل
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger rounded-4 shadow-sm border-0 mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('system.secondary-data.update', $secondaryData) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <!-- Sidebar Area for Primary Data Selection and Info Card -->
            <div class="col-lg-5">
                <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden mb-6">
                    <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50">
                        <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                            <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                            تعديل الشخص المرتبط
                        </h5>
                    </div>
                    <div class="p-8">
                        <div class="mb-6">
                            <label for="personal_data_id" class="text-xs font-bold text-slate-400 mb-2 block px-2">الشخص المختار *</label>
                            <select name="personal_data_id" id="personal_data_id" class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-bold text-slate-700 select2-selection" required>
                                <option value="">-- اختر الشخص --</option>
                                @foreach($personalDataList as $id => $name)
                                    <option value="{{ $id }}" {{ old('personal_data_id', $secondaryData->personal_data_id) == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Standardized Info-Card -->
                        <div id="personal_info_box" class="d-none">
                            <div class="bg-blue-50 border border-blue-100 rounded-[2rem] p-6 text-xs mb-4">
                                <h6 class="font-bold text-blue-700 mb-4 flex items-center gap-2">
                                    <i class="fas fa-info-circle"></i> بيانات الشخص المختار:
                                </h6>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-slate-700">
                                    <div class="flex flex-col"><span class="text-slate-400">الاسم الجهادي:</span> <span id="info_jihadi_name" class="font-bold">-</span></div>
                                    <div class="flex flex-col"><span class="text-slate-400">الرقم العسكري:</span> <span id="info_military_number" class="font-bold">-</span></div>
                                    <div class="flex flex-col"><span class="text-slate-400">رقم الهوية:</span> <span id="info_id_number" class="font-bold">-</span></div>
                                    <div class="flex flex-col"><span class="text-slate-400">فصيلة الدم:</span> <span id="info_blood_type" class="text-rose-600 font-extrabold">-</span></div>
                                    <div class="flex flex-col"><span class="text-slate-400">تاريخ الميلاد:</span> <span id="info_dob" class="font-bold">-</span></div>
                                    <div class="flex flex-col"><span class="text-slate-400">الحالة الاجتماعية:</span> <span id="info_marital_status" class="font-bold">-</span></div>
                                    <div class="md:col-span-2 flex flex-col"><span class="text-slate-400">مكان الميلاد:</span> <span id="info_pob" class="font-bold">-</span></div>
                                    <div class="flex flex-col"><span class="text-slate-400">هاتف 1:</span> <span id="info_phone_1" class="font-bold">-</span></div>
                                    <div class="flex flex-col"><span class="text-slate-400">هاتف 2:</span> <span id="info_phone_2" class="font-bold">-</span></div>
                                    <div class="md:col-span-2 border-top pt-2 flex flex-col"><span class="text-slate-400">العنوان:</span> <span id="info_address" class="font-bold">-</span></div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="text-xs font-bold text-slate-400 mb-2 block px-2">تاريخ الانطلاقة</label>
                                <input type="date" name="start_date" id="start_date" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-bold text-slate-700" value="{{ old('start_date', $secondaryData->start_date ? $secondaryData->start_date->format('Y-m-d') : '') }}">
                            </div>
                            <div>
                                <label for="direct_supervisor" class="text-xs font-bold text-slate-400 mb-2 block px-2">المسؤول المباشر</label>
                                <input type="text" name="direct_supervisor" id="direct_supervisor" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-bold text-slate-700" value="{{ old('direct_supervisor', $secondaryData->direct_supervisor) }}" placeholder="اسم المسؤول">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Master-Details Weapons Table -->
            <div class="col-lg-7">
                <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden mb-4">
                    <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50 d-flex justify-content-between align-items-center">
                        <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                            <span class="w-2 h-6 bg-emerald-600 rounded-full"></span>
                            بيانات الأسلحة والعهدة
                        </h5>
                        <button type="button" class="btn btn-emerald-soft rounded-pill px-4 btn-sm fw-bold" id="add-weapon-row">
                            <i class="fas fa-plus me-1"></i>إضافة سلاح
                        </button>
                    </div>
                    <div class="p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="weapons-table">
                                <thead class="bg-slate-50 text-slate-400 text-xs uppercase tracking-wider">
                                    <tr>
                                        <th class="ps-8 py-4">نوع السلاح</th>
                                        <th class="py-4">الرقم</th>
                                        <th class="py-4">الملكية</th>
                                        <th class="py-4">التعقب</th>
                                        <th class="text-center py-4" style="width: 80px;"></th>
                                    </tr>
                                </thead>
                                <tbody class="border-top-0">
                                    @php
                                        $weaponsCount = count($secondaryData->weapons);
                                        $loopCount = $weaponsCount > 0 ? $weaponsCount : 1;
                                    @endphp
                                    
                                    @for($i = 0; $i < $loopCount; $i++)
                                        @php
                                            $weapon = isset($secondaryData->weapons[$i]) ? $secondaryData->weapons[$i] : null;
                                        @endphp
                                        <tr class="weapon-row">
                                            <td class="ps-8">
                                                <input type="text" name="weapons[{{$i}}][weapon_type]" value="{{ $weapon ? $weapon->weapon_type : '' }}" class="form-control border-0 bg-transparent shadow-none font-bold text-slate-700" placeholder="نوع السلاح...">
                                            </td>
                                            <td>
                                                <input type="text" name="weapons[{{$i}}][number]" value="{{ $weapon ? $weapon->number : '' }}" class="form-control border-0 bg-transparent shadow-none font-bold text-slate-700" placeholder="رقم السلاح">
                                            </td>
                                            <td>
                                                <input type="text" name="weapons[{{$i}}][ownership]" value="{{ $weapon ? $weapon->ownership : '' }}" class="form-control border-0 bg-transparent shadow-none font-bold text-slate-700" placeholder="الملكية">
                                            </td>
                                            <td>
                                                <input type="text" name="weapons[{{$i}}][tracking]" value="{{ $weapon ? $weapon->tracking : '' }}" class="form-control border-0 bg-transparent shadow-none font-bold text-slate-700" placeholder="التعقب">
                                            </td>
                                            <td class="text-center pe-4">
                                                <button type="button" class="btn btn-link text-rose-400 remove-row" style="{{ $loopCount > 1 ? '' : 'display: none;' }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50">
                        <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                            <span class="w-2 h-6 bg-amber-500 rounded-full"></span>
                            النشاط الجهادي
                        </h5>
                    </div>
                    <div class="p-8">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="previous_jihadist_activity" class="text-xs font-bold text-slate-400 mb-2 block px-2">النشاط الجهادي السابق</label>
                                <textarea name="previous_jihadist_activity" id="previous_jihadist_activity" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700" rows="4">{{ old('previous_jihadist_activity', $secondaryData->previous_jihadist_activity) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="current_jihadist_activity" class="text-xs font-bold text-slate-400 mb-2 block px-2">النشاط الجهادي الحالي</label>
                                <textarea name="current_jihadist_activity" id="current_jihadist_activity" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700" rows="4">{{ old('current_jihadist_activity', $secondaryData->current_jihadist_activity) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="p-8 border-top bg-slate-50/30 text-end">
                        <button type="submit" class="px-10 py-4 bg-blue-600 text-white rounded-[2rem] font-bold hover:bg-blue-700 transition-all shadow-xl shadow-blue-100">
                            <i class="fas fa-save me-2"></i> تحديث السجل
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2-selection').select2({
            theme: 'bootstrap-5',
            dir: 'rtl',
            placeholder: '-- اختر الشخص --'
        });

        // Person details retrieval
        $('#personal_data_id').on('change', function () {
            const personId = $(this).val();
            const infoBox = $('#personal_info_box');

            if (!personId) {
                infoBox.addClass('d-none');
                return;
            }

            fetch(`/system/personal-data/details/${personId}`)
                .then(res => res.json())
                .then(data => {
                    $('#info_jihadi_name').text(data.jihadi_name || '-');
                    $('#info_military_number').text(data.military_number || '-');
                    $('#info_blood_type').text(data.blood_type || '-');
                    $('#info_dob').text(data.date_of_birth || '-');
                    $('#info_marital_status').text(data.marital_status || '-');
                    $('#info_pob').text(data.place_of_birth || '-');
                    $('#info_id_number').text(data.national_id_number || '-');
                    $('#info_phone_1').text(data.phone_1 || '-');
                    $('#info_phone_2').text(data.phone_2 || '-');
                    $('#info_address').text(data.address || '-');
                    
                    infoBox.removeClass('d-none');
                })
                .catch(err => {
                    console.error('Error:', err);
                    infoBox.addClass('d-none');
                });
        });

        // Trigger if already selected
        if ($('#personal_data_id').val()) {
            $('#personal_data_id').trigger('change');
        }

        // Weapon dynamic rows
        let weaponIndex = {{ count($secondaryData->weapons) > 0 ? count($secondaryData->weapons) : 1 }};
        $('#add-weapon-row').click(function() {
            let newRow = `
                <tr class="weapon-row">
                    <td class="ps-8">
                        <input type="text" name="weapons[${weaponIndex}][weapon_type]" class="form-control border-0 bg-transparent shadow-none font-bold text-slate-700" placeholder="نوع السلاح...">
                    </td>
                    <td>
                        <input type="text" name="weapons[${weaponIndex}][number]" class="form-control border-0 bg-transparent shadow-none font-bold text-slate-700" placeholder="رقم السلاح">
                    </td>
                    <td>
                        <input type="text" name="weapons[${weaponIndex}][ownership]" class="form-control border-0 bg-transparent shadow-none font-bold text-slate-700" placeholder="الملكية">
                    </td>
                    <td>
                        <input type="text" name="weapons[${weaponIndex}][tracking]" class="form-control border-0 bg-transparent shadow-none font-bold text-slate-700" placeholder="التعقب">
                    </td>
                    <td class="text-center pe-4">
                        <button type="button" class="btn btn-link text-rose-400 remove-row">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#weapons-table tbody').append(newRow);
            weaponIndex++;
            updateRemoveButtons();
        });

        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
            updateRemoveButtons();
        });

        function updateRemoveButtons() {
            $('.remove-row').toggle($('.weapon-row').length > 1);
        }
    });
</script>
@endpush
