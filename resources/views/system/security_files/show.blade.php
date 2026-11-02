@extends('layouts.app')

@section('title', 'عرض تفاصيل الملف الأمني')
@section('page_title', 'الملفات الأمنية')

@section('content')
<div class="space-y-6" dir="rtl">

    {{-- Header Actions --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-2 print:hidden">
        <div class="flex items-center gap-3.5">
            <a href="{{ route('system.security-files.index') }}" class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:text-brand-600 flex items-center justify-center transition-colors shadow-xs">
                <i class="fas fa-arrow-right text-sm"></i>
            </a>
            <div>
                <h1 class="font-display font-bold text-2xl text-slate-900 dark:text-white m-0">
                    ملف أمني رقم #{{ $securityFile->file_number ?? str_pad($securityFile->id, 5, '0', STR_PAD_LEFT) }}
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">خاص بالفرد: {{ $securityFile->personalData->name ?? 'غير محدد' }}</p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <x-print-button :url="route('system.security-files.print', $securityFile)" text="طباعة الملف الرسمي" variant="primary" />
            <a href="{{ route('system.security-files.edit', $securityFile) }}" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-xl shadow-xs transition-all flex items-center gap-2">
                <i class="fas fa-edit"></i> تعديل
            </a>
        </div>
    </div>

    {{-- Card 1: Person & Security File Summary --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
        <div class="flex flex-col md:flex-row gap-6 items-start">
            <div class="w-24 h-24 rounded-2xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 overflow-hidden shrink-0 flex items-center justify-center shadow-inner">
                @if($securityFile->personalData && $securityFile->personalData->photo_url)
                    <img src="{{ $securityFile->personalData->photo_url }}" alt="" class="w-full h-full object-cover">
                @else
                    <i class="fas fa-user-shield text-3xl text-slate-400"></i>
                @endif
            </div>

            <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-xs">
                <div>
                    <span class="text-slate-400 block mb-1">اسم الفرد الكامل</span>
                    <strong class="text-slate-900 dark:text-white text-sm block">{{ $securityFile->personalData->name ?? 'غير محدد' }}</strong>
                </div>

                <div>
                    <span class="text-slate-400 block mb-1">الاسم الجهادي</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $securityFile->personalData->jihadi_name ?? 'غير محدد' }}</span>
                </div>

                <div>
                    <span class="text-slate-400 block mb-1">الرقم العسكري / القومي</span>
                    <span class="font-mono font-bold text-slate-800 dark:text-slate-200">{{ $securityFile->personalData->military_number ?? '-' }} / {{ $securityFile->personalData->national_id_number ?? '-' }}</span>
                </div>

                <div>
                    <span class="text-slate-400 block mb-1">الجهة المعدة للملف</span>
                    <span class="px-2.5 py-1 bg-brand-50 dark:bg-brand-950 text-brand-700 dark:text-brand-300 font-bold rounded-lg inline-block">
                        {{ $securityFile->preparingEntity->name ?? 'غير محدد' }}
                    </span>
                </div>

                <div>
                    <span class="text-slate-400 block mb-1">المعد للملف</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $securityFile->file_preparer ?? 'غير مدون' }}</span>
                </div>

                <div>
                    <span class="text-slate-400 block mb-1">تاريخ الملف (ميلادي / هجري)</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200">
                        {{ $securityFile->file_date_gregorian ? $securityFile->file_date_gregorian->format('Y/m/d') : '-' }}
                        <span class="text-slate-400 font-mono">({{ $securityFile->file_date_hijri ?? '-' }})</span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 2: Highlight Summary & Jihadist Status --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm space-y-3">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white border-b border-slate-100 dark:border-slate-800 pb-2 flex items-center gap-2">
                <i class="fas fa-file-invoice text-brand-600"></i> أبرز البيانات الملخصة من الملف
            </h3>
            <div class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-line bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl">
                {{ $securityFile->highlight_summary ?? 'لا يوجد نص ملخص مدون.' }}
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm space-y-3">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white border-b border-slate-100 dark:border-slate-800 pb-2 flex items-center gap-2">
                <i class="fas fa-flag text-brand-600"></i> بيانات الوضع الجهادي
            </h3>
            <div class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-line bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl">
                {{ $securityFile->jihadist_status ?? 'لا توجد بيانات عن الوضع الجهادي مدونة.' }}
            </div>
        </div>
    </div>

    {{-- Card 3: Summary Details & Preparer --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
        <div>
            <span class="text-slate-400 block mb-1">معد التلخيص</span>
            <span class="font-bold text-slate-900 dark:text-white">{{ $securityFile->summary_preparer ?? 'غير مدون' }}</span>
        </div>
        <div>
            <span class="text-slate-400 block mb-1">تاريخ التلخيص الميلادي</span>
            <span class="font-bold text-slate-900 dark:text-white">{{ $securityFile->summary_date_gregorian ? $securityFile->summary_date_gregorian->format('Y/m/d') : '-' }}</span>
        </div>
        <div>
            <span class="text-slate-400 block mb-1">تاريخ التلخيص الهجري</span>
            <span class="font-bold text-slate-900 dark:text-white font-mono">{{ $securityFile->summary_date_hijri ?? '-' }}</span>
        </div>
    </div>

    {{-- Card 4: Attachments Grid --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm space-y-4">
        <h3 class="text-sm font-bold text-slate-900 dark:text-white border-b border-slate-100 dark:border-slate-800 pb-2 flex items-center gap-2">
            <i class="fas fa-paperclip text-brand-600"></i> المرفقات الرقمية والمستندات
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- File Attachments --}}
            <div>
                <h4 class="text-xs font-bold text-slate-700 dark:text-slate-300 mb-3">مرفقات الملف الأصلي</h4>
                @if(!empty($securityFile->file_attachments))
                    <div class="space-y-2">
                        @foreach($securityFile->file_attachments as $idx => $path)
                            @php
                                $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                $isImg = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                                $isPdf = $ext === 'pdf';
                            @endphp
                            <a href="{{ asset('storage/' . $path) }}" target="_blank" class="p-3 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 flex items-center justify-between text-xs hover:border-brand-500 transition-colors">
                                <span class="font-bold text-brand-600 dark:text-brand-400 flex items-center gap-2">
                                    <i class="fas {{ $isPdf ? 'fa-file-pdf text-rose-500' : ($isImg ? 'fa-file-image text-emerald-500' : 'fa-file-alt text-brand-500') }}"></i>
                                    <span>مرفق الملف #{{ $idx + 1 }}</span>
                                    <span class="text-[10px] font-mono uppercase text-slate-400">({{ $ext }})</span>
                                </span>
                                <span class="text-[11px] text-slate-400">استعراض / تحميل <i class="fas fa-external-link-alt ms-1"></i></span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-xs text-slate-400 italic">لا توجد مرفقات للملف الأصلي</div>
                @endif
            </div>

            {{-- Summary Attachments --}}
            <div>
                <h4 class="text-xs font-bold text-slate-700 dark:text-slate-300 mb-3">مرفقات الملخص</h4>
                @if(!empty($securityFile->summary_attachments))
                    <div class="space-y-2">
                        @foreach($securityFile->summary_attachments as $idx => $path)
                            @php
                                $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                $isImg = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                                $isPdf = $ext === 'pdf';
                            @endphp
                            <a href="{{ asset('storage/' . $path) }}" target="_blank" class="p-3 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 flex items-center justify-between text-xs hover:border-indigo-500 transition-colors">
                                <span class="font-bold text-indigo-600 dark:text-indigo-400 flex items-center gap-2">
                                    <i class="fas {{ $isPdf ? 'fa-file-pdf text-rose-500' : ($isImg ? 'fa-file-image text-emerald-500' : 'fa-file-alt text-indigo-500') }}"></i>
                                    <span>مرفق الملخص #{{ $idx + 1 }}</span>
                                    <span class="text-[10px] font-mono uppercase text-slate-400">({{ $ext }})</span>
                                </span>
                                <span class="text-[11px] text-slate-400">استعراض / تحميل <i class="fas fa-external-link-alt ms-1"></i></span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-xs text-slate-400 italic">لا توجد مرفقات للملخص</div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
