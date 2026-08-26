@extends('layouts.app')

@section('title', 'عرض البيانات الشخصية')
@section('page_title', 'السجلات الأساسية')

@section('content')
    <div class="container-fluid mx-auto px-4 py-8" dir="rtl">
        {{-- الرأس مع أزرار الإجراءات --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 no-print">
            <div class="flex items-center gap-4">
                <div
                    class="w-16 h-16 bg-blue-600 rounded-3xl shadow-xl shadow-blue-100 flex items-center justify-center text-white">
                    <!-- أيقونة مستخدم -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-3xl font-extrabold text-slate-800 header-font mb-1">{{ $personalData->name }}</h2>
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-bold border border-blue-100">
                                {{ $personalData->jihadi_name ?? 'لا يوجد اسم جهادي' }}
                            </span>
                            <span class="text-slate-400 text-xs flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4.13-5.68z" clip-rule="evenodd" />
                                </svg>
                                ملف رقم: {{ $personalData->id }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button onclick="window.print()" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        طباعة الوثيقة
                    </button>
                    <a href="{{ route('system.personal-data.edit', $personalData) }}" class="px-5 py-2.5 bg-amber-500 text-white rounded-2xl font-bold hover:bg-amber-600 transition-all shadow-lg shadow-amber-100 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                        تعديل البيانات
                    </a>
                    <a href="{{ route('system.personal-data.index') }}" class="px-5 py-2.5 bg-slate-800 text-white rounded-2xl font-bold hover:bg-slate-900 transition-all shadow-lg shadow-slate-200 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                        عودة للقائمة
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                {{-- الجانب الأيمن: البطاقة الشخصية --}}
                <div class="lg:col-span-4 space-y-8">
                    <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 p-8 text-center relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-full -mr-16 -mt-16 opacity-50"></div>

                        <div class="relative z-10">
                            <div class="w-48 h-48 mx-auto mb-6 relative">
                                @if($personalData->main_profile_photo)
                                    <img src="{{ asset('storage/' . $personalData->main_profile_photo) }}" 
                                         class="w-full h-full object-cover rounded-[2rem] border-4 border-white shadow-2xl">
                                @else
                                    <div class="w-full h-full bg-slate-50 rounded-[2rem] border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-20 h-20">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="text-[10px] font-bold uppercase">لا توجد صورة</span>
                                    </div>
                                @endif
                                <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-emerald-500 text-white rounded-2xl border-4 border-white flex items-center justify-center shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                </div>
                            </div>

                            <h3 class="text-xl font-extrabold text-slate-800 header-font mb-6 px-4">{{ $personalData->name }}</h3>

                            <div class="space-y-4 text-right px-2">
                                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-2xl border border-slate-100">
                                    <span class="text-xs text-slate-400 font-bold">الرقم القومي</span>
                                    <span class="text-sm font-extrabold text-slate-700">{{ $personalData->national_id_number ?? '-' }}</span>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-blue-50/50 rounded-2xl border border-blue-100">
                                    <span class="text-xs text-blue-400 font-bold">الرقم العسكري</span>
                                    <span class="text-sm font-extrabold text-blue-700">{{ $personalData->military_number ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- بيانات الاتصال --}}
                    <div class="bg-slate-800 rounded-[2.5rem] p-8 text-white shadow-xl">
                        <h5 class="text-lg font-bold mb-6 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                            </svg>
                            بيانات الاتصال
                        </h5>
                        <div class="space-y-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-400">رقم الهاتف الأساسي</p>
                                    <p class="text-sm font-bold">{{ $personalData->phone_1 ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- الجانب الأيسر: التفاصيل --}}
                <div class="lg:col-span-8 space-y-8">
                    <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                        <div class="px-8 py-6 border-b border-slate-50 flex items-center bg-slate-50/50">
                            <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                                <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                                المعلومات المدنية والشخصية
                            </h5>
                        </div>
                        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-400 font-bold">فصيلة الدم</p>
                                    <p class="text-base font-extrabold text-slate-700">{{ $personalData->bloodType->type ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-400 font-bold">المؤهل العلمي</p>
                                    <p class="text-base font-extrabold text-slate-700">{{ $personalData->qualification->name ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- السجل العسكري --}}
                    <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                        <div class="px-8 py-6 border-b border-slate-50 flex items-center bg-slate-50/50">
                            <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                                <span class="w-2 h-6 bg-emerald-600 rounded-full"></span>
                                السجل العسكري والمهني
                            </h5>
                        </div>
                        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold mb-1 uppercase">الرتبة العسكرية</p>
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-amber-500">
                                        <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-base font-extrabold text-slate-700">{{ $personalData->rank->rank ?? '-' }}</span>
                                </div>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold mb-1 uppercase">الجهة / الوحدة</p>
                                <p class="text-base font-extrabold text-slate-700">{{ $personalData->unit ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- الأهل والأقارب --}}
                    @if($personalData->relatives && $personalData->relatives->count() > 0)
                        <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                            <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                                <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                                    <span class="w-2 h-6 bg-indigo-600 rounded-full"></span>
                                    الأهل والأقارب ({{ $personalData->relatives->count() }})
                                </h5>
                                <a href="{{ route('system.relatives.create', ['personal_data_id' => $personalData->id]) }}"
                                    class="text-xs text-indigo-600 hover:text-indigo-800 font-bold flex items-center gap-1">
                                    <i class="fas fa-plus"></i> إضافة
                                </a>
                            </div>
                            <div class="p-6">
                                <div class="overflow-x-auto">
                                    <table class="w-full text-right text-xs">
                                        <thead>
                                            <tr class="border-b border-slate-100 text-slate-400 font-bold">
                                                <th class="py-2.5 px-3">الاسم الكامل</th>
                                                <th class="py-2.5 px-3">العمر</th>
                                                <th class="py-2.5 px-3">صلة القرابة</th>
                                                <th class="py-2.5 px-3">العمل الحالي</th>
                                                <th class="py-2.5 px-3">العقائدي</th>
                                                <th class="py-2.5 px-3">الانتماء السياسي</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-50">
                                            @foreach($personalData->relatives as $rel)
                                                <tr class="hover:bg-slate-50/50">
                                                    <td class="py-3 px-3 font-bold text-slate-800">{{ $rel->full_name }}</td>
                                                    <td class="py-3 px-3 text-slate-600">{{ $rel->age ? $rel->age . ' سنة' : '-' }}</td>
                                                    <td class="py-3 px-3">
                                                        <span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 font-bold text-[11px]">
                                                            {{ $rel->kinship->name ?? '-' }}
                                                        </span>
                                                    </td>
                                                    <td class="py-3 px-3 text-slate-600">{{ $rel->current_job ?? '-' }}</td>
                                                    <td class="py-3 px-3">
                                                        <span class="px-2.5 py-1 rounded-full bg-purple-50 text-purple-700 font-bold text-[11px]">
                                                            {{ $rel->ideologicalAffiliation->name ?? '-' }}
                                                        </span>
                                                    </td>
                                                    <td class="py-3 px-3">
                                                        <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[11px]">
                                                            {{ $rel->politicalAffiliation->name ?? '-' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- المرفقات --}}
            @if($personalData->photos->count() > 0)
                <div class="mt-8 bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-50">
                        <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                            <span class="w-2 h-6 bg-purple-600 rounded-full"></span>
                            الوثائق المرفقة
                        </h5>
                    </div>
                    <div class="p-8 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                        @foreach($personalData->photos as $photo)
                            <div class="group relative aspect-square overflow-hidden rounded-[2rem] border-2 border-slate-50 shadow-sm hover:shadow-xl transition-all">
                                <img src="{{ asset('storage/' . $photo->photo_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                                    <a href="{{ asset('storage/' . $photo->photo_path) }}" target="_blank" class="w-10 h-10 bg-white text-slate-800 rounded-xl flex items-center justify-center shadow-lg transition-transform hover:scale-110">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
@endsection

@push('styles')
    <style>
        @media print {
            body { background: white !important; }
            .no-print { display: none !important; }
            .container-fluid { width: 100% !important; max-width: 100% !important; padding: 0 !important; }
            .soft-shadow { box-shadow: none !important; border: 1px solid #eee !important; }
            .rounded-\[2\.5rem\] { border-radius: 1rem !important; }
        }
    </style>
@endpush