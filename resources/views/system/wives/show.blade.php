@extends('layouts.app')

@section('title', 'عرض بيانات الزوجة')
@section('page_title', 'السجلات الأساسية')

@section('content')
<div class="space-y-6" dir="rtl">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-3">
                <div class="p-2.5 bg-pink-500/10 text-pink-600 dark:text-pink-400 rounded-xl">
                    <i class="fas fa-female text-xl"></i>
                </div>
                بيانات الزوجة: {{ $wife->name }}
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                عرض تفاصيل الزوجة والارتباط العائلي والموقع الجغرافي
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('system.wives.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-sm font-semibold rounded-xl transition-all shadow-sm">
                <i class="fas fa-arrow-right"></i>
                عودة للقائمة
            </a>
            <a href="{{ route('system.wives.edit', $wife->id) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all">
                <i class="fas fa-edit"></i>
                تعديل
            </a>
            <form action="{{ route('system.wives.destroy', $wife->id) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all">
                    <i class="fas fa-trash"></i>
                    حذف
                </button>
            </form>
        </div>
    </div>

    {{-- Details Grid: Right Summary bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden mb-4 + Left Detail Sections --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Right Column: Summary bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden mb-4 --}}
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm text-center">
                <div class="w-20 h-20 mx-auto rounded-2xl bg-pink-500/10 border border-pink-500/20 text-pink-600 dark:text-pink-400 flex items-center justify-center font-bold text-3xl mb-4">
                    <i class="fas fa-female"></i>
                </div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-1">{{ $wife->wife_name ?? $wife->name ?? '-' }}</h2>
                
                <div class="mb-4">
                    @if($wife->status == 'على ذمته' || $wife->status == 'نشط' || $wife->status == 'مستمرة')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            {{ $wife->status ?? 'مستمرة' }}
                        </span>
                    @elseif($wife->status == 'مطلقة')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            {{ $wife->status }}
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-slate-500/10 text-slate-600 dark:text-slate-400 border border-slate-500/20">
                            {{ $wife->status ?? '-' }}
                        </span>
                    @endif
                </div>

                <div class="border-t border-slate-100 dark:border-slate-800 pt-4 space-y-3 text-right text-xs">
                    <div>
                        <span class="text-slate-400 block mb-0.5">الزوج المرتبط</span>
                        @if($wife->personalData)
                            <a href="{{ route('system.personal-data.show', $wife->personalData->id) }}" class="font-bold text-brand-600 dark:text-brand-400 hover:underline flex items-center gap-1.5">
                                <i class="fas fa-user"></i>
                                {{ $wife->personalData->name }}
                            </a>
                        @else
                            <span class="font-semibold text-slate-500">-</span>
                        @endif
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-0.5">رقم الهوية</span>
                        <span class="font-mono font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200">{{ $wife->wife_id_number ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-0.5">رقم الهاتف</span>
                        <span class="font-mono font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200">{{ $wife->wife_phone ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-0.5">تاريخ الميلاد</span>
                        <span class="font-semibold text-slate-800 dark:text-slate-200 dark:text-slate-200">{{ $wife->wife_birth_date ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Left Column: Detail Cards --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Location bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden mb-4 --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
                <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <span class="w-2 h-5 bg-emerald-500 rounded-full"></span>
                    معلومات الموقع الجغرافي
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="bg-slate-50 dark:bg-slate-950/50 p-4 rounded-xl border border-slate-200 dark:border-slate-800">
                        <span class="text-slate-400 block mb-1">المحافظة</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200 text-sm">{{ $wife->governorate->name ?? '-' }}</span>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-950/50 p-4 rounded-xl border border-slate-200 dark:border-slate-800">
                        <span class="text-slate-400 block mb-1">المديرية</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200 text-sm">{{ $wife->directorate->name ?? '-' }}</span>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-950/50 p-4 rounded-xl border border-slate-200 dark:border-slate-800">
                        <span class="text-slate-400 block mb-1">العزلة / المنطقة</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200 text-sm">{{ $wife->subArea->name ?? '-' }}</span>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-950/50 p-4 rounded-xl border border-slate-200 dark:border-slate-800">
                        <span class="text-slate-400 block mb-1">القرية / الحي</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200 text-sm">{{ $wife->village->name ?? '-' }}</span>
                    </div>
                    <div class="sm:col-span-2 bg-slate-50 dark:bg-slate-950/50 p-4 rounded-xl border border-slate-200 dark:border-slate-800">
                        <span class="text-slate-400 block mb-1">العنوان التفصيلي</span>
                        <span class="font-semibold text-slate-800 dark:text-slate-200 dark:text-slate-200 text-sm">{{ $wife->wife_address ?? '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- Audit bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden mb-4 --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
                <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <span class="w-2 h-5 bg-slate-400 rounded-full"></span>
                    معلومات النظام والتدقيق
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="bg-slate-50 dark:bg-slate-950/50 p-4 rounded-xl border border-slate-200 dark:border-slate-800">
                        <span class="text-slate-400 block mb-1">تاريخ الإنشاء</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200">{{ $wife->created_at->format('Y-m-d H:i') }}</span>
                        <span class="text-slate-400 block mt-0.5 text-[11px]">{{ $wife->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-950/50 p-4 rounded-xl border border-slate-200 dark:border-slate-800">
                        <span class="text-slate-400 block mb-1">آخر تحديث</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200">{{ $wife->updated_at->format('Y-m-d H:i') }}</span>
                        <span class="text-slate-400 block mt-0.5 text-[11px]">{{ $wife->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

