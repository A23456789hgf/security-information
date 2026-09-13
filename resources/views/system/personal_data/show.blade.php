@extends('layouts.app')

@section('title', 'تفاصيل السجل الشخصي')
@section('page_title', 'السجلات الأساسية')

@section('content')
<div class="space-y-6" dir="rtl">
    {{-- Breadcrumbs --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-2 print:hidden">
        <a href="{{ route('dashboard') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">الرئيسية</a>
        <span>/</span>
        <a href="{{ route('system.personal-data.index') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">إدارة البيانات الشخصية</a>
        <span>/</span>
        <span class="text-slate-900 dark:text-white font-semibold">تفاصيل السجل</span>
    </nav>

    {{-- Action Toolbar --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4 print:hidden">
        <div>
            <h1 class="font-display font-bold text-2xl text-slate-900 dark:text-white m-0">{{ $personalData->name }}</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">ملف رقم #{{ str_pad($personalData->id, 5, '0', STR_PAD_LEFT) }}</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-2.5">
            <button onclick="window.print()"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors text-sm font-bold shadow-sm">
                <i class="fas fa-print text-slate-400"></i>
                <span>طباعة التقرير</span>
            </button>
            <a href="{{ route('system.personal-data.edit', $personalData) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 text-amber-700 dark:text-amber-400 rounded-xl hover:bg-amber-100 dark:hover:bg-amber-500/20 transition-colors text-sm font-bold shadow-sm">
                <i class="fas fa-edit"></i>
                <span>تعديل السجل</span>
            </a>
            <a href="{{ route('system.personal-data.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors text-sm font-bold">
                <i class="fas fa-arrow-right"></i>
                <span>عودة للقائمة</span>
            </a>
        </div>
    </div>

    {{-- Main Grid Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Right Column: Profile Summary (Sticky) --}}
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 lg:sticky lg:top-24 transition-colors print:shadow-none print:border-slate-300">
                <div class="text-center mb-6">
                    <div class="w-32 h-32 rounded-2xl bg-slate-50 dark:bg-slate-800 border-2 border-dashed border-slate-200 dark:border-slate-700 mx-auto mb-4 overflow-hidden flex items-center justify-center shadow-inner">
                        @if($personalData->main_profile_photo)
                            <img src="{{ asset('storage/' . $personalData->main_profile_photo) }}" alt="{{ $personalData->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="text-slate-300 dark:text-slate-600 flex flex-col items-center justify-center">
                                <i class="fas fa-user text-4xl mb-1"></i>
                                <span class="text-[10px] font-bold">بدون صورة</span>
                            </div>
                        @endif
                    </div>
                    
                    <h3 class="font-display font-bold text-xl text-slate-900 dark:text-white mb-2">{{ $personalData->name }}</h3>
                    
                    <div class="flex items-center justify-center gap-2 mb-3">
                        @if($personalData->jihadi_name)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-brand-50 dark:bg-brand-500/10 text-brand-700 dark:text-brand-300 border border-brand-200/50 dark:border-brand-500/20">
                                {{ $personalData->jihadi_name }}
                            </span>
                        @endif
                        <x-security-badge level="resolved" label="سجل نشط" />
                    </div>
                </div>

                <hr class="border-slate-100 dark:border-slate-800 my-6" />

                <div class="space-y-4">
                    <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800">
                        <span class="block text-xs font-bold text-slate-400 dark:text-slate-500 mb-1">الرقم القومي (الهوية)</span>
                        <div class="font-mono font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200 flex items-center gap-2 text-sm">
                            <i class="fas fa-id-card text-slate-400 text-xs"></i>
                            {{ $personalData->national_id_number ?? 'غير مسجل' }}
                        </div>
                    </div>

                    <div class="p-3.5 rounded-xl bg-brand-50/50 dark:bg-brand-500/5 border border-brand-100 dark:border-brand-500/20">
                        <span class="block text-xs font-bold text-brand-600 dark:text-brand-400 mb-1">الرقم العسكري</span>
                        <div class="font-mono font-bold text-brand-700 dark:text-brand-300 flex items-center gap-2 text-sm">
                            <i class="fas fa-shield-alt text-brand-500 text-xs"></i>
                            {{ $personalData->military_number ?? 'غير مسجل' }}
                        </div>
                    </div>

                    @if($personalData->phone_1 || $personalData->phone_2)
                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800">
                            <span class="block text-xs font-bold text-slate-400 dark:text-slate-500 mb-1">أرقام الاتصال</span>
                            <div class="space-y-1.5 text-xs font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200">
                                @if($personalData->phone_1)
                                    <div class="flex items-center gap-2 font-mono">
                                        <i class="fas fa-phone-alt text-slate-400 text-[10px]"></i>
                                        <span>{{ $personalData->phone_1 }}</span>
                                    </div>
                                @endif
                                @if($personalData->phone_2)
                                    <div class="flex items-center gap-2 font-mono text-slate-500">
                                        <i class="fas fa-phone text-slate-400 text-[10px]"></i>
                                        <span>{{ $personalData->phone_2 }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Left Column: Details Cards --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Section 1: Civilian and Personal Data --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors print:shadow-none print:border-slate-300">
                <div class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 px-5 py-4 flex items-center gap-3">
                    <div class="w-2 h-5 bg-brand-600 rounded-full"></div>
                    <h2 class="font-display font-bold text-base text-slate-900 dark:text-white m-0">المعلومات المدنية والشخصية</h2>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <span class="block text-xs font-bold text-slate-400 dark:text-slate-500 mb-1">فصيلة الدم</span>
                        <div class="text-sm font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200">
                            {{ $personalData->bloodType->type ?? '-' }}
                        </div>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-slate-400 dark:text-slate-500 mb-1">المؤهل الدراسي</span>
                        <div class="text-sm font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200">
                            {{ $personalData->qualification->name ?? '-' }}
                        </div>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-slate-400 dark:text-slate-500 mb-1">الحالة الاجتماعية</span>
                        <div class="text-sm font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200">
                            {{ $personalData->maritalStatus->name ?? '-' }}
                        </div>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-slate-400 dark:text-slate-500 mb-1">تاريخ الميلاد</span>
                        <div class="text-sm font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200">
                            {{ $personalData->date_of_birth_gregorian ? $personalData->date_of_birth_gregorian->format('Y-m-d') : '-' }}
                        </div>
                    </div>

                    @if($personalData->placeOfBirthGovernorate || $personalData->placeOfBirthDirectorate || $personalData->placeOfBirthSubArea || $personalData->placeOfBirthVillage)
                        <div>
                            <span class="block text-xs font-bold text-slate-400 dark:text-slate-500 mb-1">مكان الميلاد (محافظة / مديرية / عزلة / قرية)</span>
                            <div class="text-sm font-bold text-slate-800 dark:text-slate-200">
                                {{ implode(' / ', array_filter([
                                    $personalData->placeOfBirthGovernorate->name ?? null,
                                    $personalData->placeOfBirthDirectorate->name ?? null,
                                    $personalData->placeOfBirthSubArea->name ?? null,
                                    $personalData->placeOfBirthVillage->name ?? null,
                                ])) ?: '-' }}
                            </div>
                        </div>
                    @endif

                    @if($personalData->governorate || $personalData->directorate || $personalData->subArea || $personalData->village)
                        <div>
                            <span class="block text-xs font-bold text-slate-400 dark:text-slate-500 mb-1">عنوان السكن (محافظة / مديرية / عزلة / قرية)</span>
                            <div class="text-sm font-bold text-slate-800 dark:text-slate-200">
                                {{ implode(' / ', array_filter([
                                    $personalData->governorate->name ?? null,
                                    $personalData->directorate->name ?? null,
                                    $personalData->subArea->name ?? null,
                                    $personalData->village->name ?? null,
                                ])) ?: '-' }}
                            </div>
                        </div>
                    @endif

                    @if($personalData->current_address)
                        <div>
                            <span class="block text-xs font-bold text-slate-400 dark:text-slate-500 mb-1">تفاصيل إضافية للسكن</span>
                            <div class="text-sm font-bold text-slate-800 dark:text-slate-200">
                                {{ $personalData->current_address }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Section 2: Military & Professional Data --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors print:shadow-none print:border-slate-300">
                <div class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 px-5 py-4 flex items-center gap-3">
                    <div class="w-2 h-5 bg-emerald-600 rounded-full"></div>
                    <h2 class="font-display font-bold text-base text-slate-900 dark:text-white m-0">السجل العسكري والمهني</h2>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <span class="block text-xs font-bold text-slate-400 dark:text-slate-500 mb-1">الرتبة العسكرية</span>
                        <div class="text-sm font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200">
                            {{ $personalData->rank->rank ?? '-' }}
                        </div>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-slate-400 dark:text-slate-500 mb-1">الجهة / الوحدة العسكرية</span>
                        <div class="text-sm font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200">
                            {{ $personalData->unit ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 3: Relatives --}}
            @if($personalData->relatives && $personalData->relatives->count() > 0)
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors print:shadow-none print:border-slate-300">
                    <div class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 px-5 py-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-5 bg-indigo-600 rounded-full"></div>
                            <h2 class="font-display font-bold text-base text-slate-900 dark:text-white m-0">الأهل والأقارب ({{ $personalData->relatives->count() }})</h2>
                        </div>
                        <a href="{{ route('system.relatives.create', ['personal_data_id' => $personalData->id]) }}"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs font-bold text-brand-600 dark:text-brand-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors print:hidden">
                            <i class="fas fa-plus"></i> إضافة
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-right text-xs">
                            <thead class="bg-slate-50/50 dark:bg-slate-800/30 border-b border-slate-100 dark:border-slate-800 text-slate-500 dark:text-slate-400 font-bold">
                                <tr>
                                    <th class="py-3 px-4">الاسم الكامل</th>
                                    <th class="py-3 px-4">العمر</th>
                                    <th class="py-3 px-4">صلة القرابة</th>
                                    <th class="py-3 px-4">العمل الحالي</th>
                                    <th class="py-3 px-4">العقائدي</th>
                                    <th class="py-3 px-4">الانتماء السياسي</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach($personalData->relatives as $rel)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20">
                                        <td class="py-3 px-4 font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200">{{ $rel->full_name }}</td>
                                        <td class="py-3 px-4 text-slate-600 dark:text-slate-400">{{ $rel->age ? $rel->age . ' سنة' : '-' }}</td>
                                        <td class="py-3 px-4">
                                            <span class="inline-block px-2.5 py-1 rounded-full bg-brand-50 dark:bg-brand-500/10 text-brand-700 dark:text-brand-300 font-bold text-[11px]">
                                                {{ $rel->kinship->name ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-slate-600 dark:text-slate-400">{{ $rel->current_job ?? '-' }}</td>
                                        <td class="py-3 px-4">
                                            <span class="inline-block px-2.5 py-1 rounded-full bg-purple-50 dark:bg-purple-500/10 text-purple-700 dark:text-purple-300 font-bold text-[11px]">
                                                {{ $rel->ideologicalAffiliation->name ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="inline-block px-2.5 py-1 rounded-full bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 font-bold text-[11px]">
                                                {{ $rel->politicalAffiliation->name ?? '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- Section 4: Attached Photos/Documents --}}
            @if($personalData->photos && $personalData->photos->count() > 0)
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors print:shadow-none print:border-slate-300">
                    <div class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 px-5 py-4 flex items-center gap-3">
                        <div class="w-2 h-5 bg-purple-600 rounded-full"></div>
                        <h2 class="font-display font-bold text-base text-slate-900 dark:text-white m-0">الوثائق والصور المرفقة ({{ $personalData->photos->count() }})</h2>
                    </div>
                    
                    <div class="p-6 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @foreach($personalData->photos as $photo)
                            <div class="group relative aspect-square overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 shadow-sm">
                                <img src="{{ asset('storage/' . $photo->photo_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <a href="{{ asset('storage/' . $photo->photo_path) }}" target="_blank"
                                    class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white transition-opacity">
                                    <span class="p-2 bg-white/90 text-slate-900 rounded-2xl text-xs font-bold">
                                        <i class="fas fa-search-plus"></i> تكبير
                                    </span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection

