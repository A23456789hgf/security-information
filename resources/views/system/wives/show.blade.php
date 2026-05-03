@extends('layouts.app')

@section('title', 'عرض بيانات الزوجة')
@section('page_title', 'النظام')

@section('content')
    <div class="container-fluid mx-auto px-4 py-8" dir="rtl">
        {{-- رأس الصفحة --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-blue-600 rounded-3xl shadow-xl shadow-blue-100 flex items-center justify-center text-white">
                    <i class="fas fa-user-circle text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 header-font mb-1">بيانات الزوجة</h2>
                    <p class="text-slate-500 text-sm">عرض تفاصيل الزوجة: <span class="text-blue-600 font-bold">{{ $wife->name }}</span></p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('system.wives.edit', $wife->id) }}"
                    class="px-6 py-3 bg-amber-500 text-white rounded-2xl font-bold hover:bg-amber-600 transition-all shadow-xl shadow-amber-100 flex items-center gap-2">
                    <i class="fas fa-edit"></i> تعديل
                </a>
                <a href="{{ route('system.wives.index') }}"
                    class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
                    <i class="fas fa-arrow-right"></i> عودة للقائمة
                </a>
            </div>
        </div>

        <div class="max-w-4xl mx-auto space-y-8">
            {{-- البيانات الأساسية --}}
            <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50">
                    <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                        <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                        البيانات الأساسية
                    </h5>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 flex flex-col gap-1">
                            <span class="text-xs font-bold text-slate-400">الزوج</span>
                            <span class="font-bold text-slate-700">
                                @if($wife->personalData)
                                    <a href="{{ route('system.personal-data.show', $wife->personalData->id) }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                                        {{ $wife->personalData->name }}
                                    </a>
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 flex flex-col gap-1">
                            <span class="text-xs font-bold text-slate-400">اسم الزوجة</span>
                            <span class="font-bold text-slate-700">{{ $wife->wife_name ?? $wife->name ?? '-' }}</span>
                        </div>
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 flex flex-col gap-1">
                            <span class="text-xs font-bold text-slate-400">رقم الهوية</span>
                            <span class="font-bold text-slate-700 font-mono">{{ $wife->wife_id_number ?? '-' }}</span>
                        </div>
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 flex flex-col gap-1">
                            <span class="text-xs font-bold text-slate-400">حالة الزوجة</span>
                            <div>
                                @php
                                    $statusClass = match($wife->status) {
                                        'مستمرة' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        'متوفية' => 'bg-slate-100 text-slate-700 border-slate-200',
                                        'مطلقة' => 'bg-rose-100 text-rose-700 border-rose-200',
                                        default => 'bg-blue-100 text-blue-700 border-blue-200',
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-lg text-xs font-bold border inline-block mt-1 {{ $statusClass }}">
                                    {{ $wife->status ?? 'مستمرة' }}
                                </span>
                            </div>
                        </div>
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 flex flex-col gap-1">
                            <span class="text-xs font-bold text-slate-400">تاريخ الميلاد</span>
                            <span class="font-bold text-slate-700">{{ $wife->wife_birth_date ?? '-' }}</span>
                        </div>
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 flex flex-col gap-1">
                            <span class="text-xs font-bold text-slate-400">رقم الهاتف</span>
                            <span class="font-bold text-slate-700 font-mono">{{ $wife->wife_phone ?? '-' }}</span>
                        </div>
                        <div class="md:col-span-2 bg-slate-50 rounded-2xl p-4 border border-slate-100 flex flex-col gap-1">
                            <span class="text-xs font-bold text-slate-400">العنوان</span>
                            <span class="font-bold text-slate-700">{{ $wife->wife_address ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- معلومات الموقع --}}
            <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50">
                    <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                        <span class="w-2 h-6 bg-emerald-500 rounded-full"></span>
                        معلومات الموقع
                    </h5>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 flex flex-col gap-1">
                            <span class="text-xs font-bold text-slate-400">المحافظة</span>
                            <span class="font-bold text-slate-700">{{ $wife->governorate->name ?? '-' }}</span>
                        </div>
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 flex flex-col gap-1">
                            <span class="text-xs font-bold text-slate-400">المديرية</span>
                            <span class="font-bold text-slate-700">{{ $wife->directorate->name ?? '-' }}</span>
                        </div>
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 flex flex-col gap-1">
                            <span class="text-xs font-bold text-slate-400">العزلة / المنطقة</span>
                            <span class="font-bold text-slate-700">{{ $wife->subArea->name ?? '-' }}</span>
                        </div>
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 flex flex-col gap-1">
                            <span class="text-xs font-bold text-slate-400">القرية / الحي</span>
                            <span class="font-bold text-slate-700">{{ $wife->village->name ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- معلومات النظام --}}
            <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50">
                    <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                        <span class="w-2 h-6 bg-slate-400 rounded-full"></span>
                        معلومات النظام
                    </h5>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 flex flex-col gap-1">
                            <span class="text-xs font-bold text-slate-400">تاريخ الإضافة</span>
                            <span class="font-bold text-slate-700">{{ $wife->created_at->format('Y-m-d H:i') }}</span>
                            <span class="text-xs text-slate-400">{{ $wife->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 flex flex-col gap-1">
                            <span class="text-xs font-bold text-slate-400">آخر تحديث</span>
                            <span class="font-bold text-slate-700">{{ $wife->updated_at->format('Y-m-d H:i') }}</span>
                            <span class="text-xs text-slate-400">{{ $wife->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- أزرار الإجراءات --}}
            <div class="flex items-center justify-end gap-4 mt-8 mb-10">
                <a href="{{ route('system.wives.index') }}" class="px-8 py-3 text-slate-400 font-bold hover:text-slate-600 transition-colors">عودة</a>
                <a href="{{ route('system.wives.edit', $wife->id) }}" class="px-8 py-4 bg-amber-500 text-white rounded-[2rem] font-bold hover:bg-amber-600 transition-all shadow-xl shadow-amber-100 flex items-center gap-2">
                    <i class="fas fa-edit"></i> تعديل
                </a>
                <form action="{{ route('system.wives.destroy', $wife->id) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-8 py-4 bg-rose-500 text-white rounded-[2rem] font-bold hover:bg-rose-600 transition-all shadow-xl shadow-rose-100 flex items-center gap-2">
                        <i class="fas fa-trash"></i> حذف
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
