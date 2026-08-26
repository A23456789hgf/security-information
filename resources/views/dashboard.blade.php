@extends('layouts.app')

@section('title', 'لوحة التحكم')
@section('page_title', 'نظرة عامة')

@section('content')
<style>
    /* ═══════════════════════════════════════════════════
       COLORFUL CARDS CUSTOM STYLES (Guaranteed Rendering)
    ═══════════════════════════════════════════════════ */
    .card-welcome-gradient {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%) !important;
        color: #ffffff !important;
        border: 1px solid rgba(99, 102, 241, 0.35) !important;
        box-shadow: 0 15px 35px -5px rgba(15, 23, 42, 0.4) !important;
    }
    .card-progress-gradient {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #1d4ed8 100%) !important;
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        box-shadow: 0 15px 35px -5px rgba(79, 70, 229, 0.45) !important;
    }
    .card-personal-gradient {
        background: linear-gradient(135deg, #059669 0%, #0d9488 50%, #047857 100%) !important;
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        box-shadow: 0 12px 28px -5px rgba(16, 185, 129, 0.4) !important;
    }
    .card-gov-gradient {
        background: linear-gradient(135deg, #d97706 0%, #ea580c 50%, #b45309 100%) !important;
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        box-shadow: 0 12px 28px -5px rgba(245, 158, 11, 0.4) !important;
    }
    .card-dir-gradient {
        background: linear-gradient(135deg, #7e22ce 0%, #c026d3 50%, #4338ca 100%) !important;
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        box-shadow: 0 12px 28px -5px rgba(168, 85, 247, 0.4) !important;
    }
    .card-villages-gradient {
        background: linear-gradient(135deg, #2563eb 0%, #0891b2 50%, #1d4ed8 100%) !important;
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        box-shadow: 0 12px 28px -5px rgba(59, 130, 246, 0.4) !important;
    }
    .card-container-blue {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0e7ff 100%) !important;
        border: 1px solid #c7d2fe !important;
    }
    .card-container-emerald {
        background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%) !important;
        border: 1px solid #a7f3d0 !important;
    }
    .card-action-blue {
        background: linear-gradient(135deg, #ffffff 0%, #e0e7ff 100%) !important;
        border: 1px solid #a5b4fc !important;
        color: #1e1b4b !important;
        transition: all 0.3s ease !important;
    }
    .card-action-blue:hover {
        background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%) !important;
        color: #ffffff !important;
        border-color: transparent !important;
        transform: translateY(-3px) scale(1.02) !important;
        box-shadow: 0 12px 25px -5px rgba(37, 99, 235, 0.4) !important;
    }
    .card-action-amber {
        background: linear-gradient(135deg, #ffffff 0%, #fef3c7 100%) !important;
        border: 1px solid #fde68a !important;
        color: #78350f !important;
        transition: all 0.3s ease !important;
    }
    .card-action-amber:hover {
        background: linear-gradient(135deg, #d97706 0%, #ea580c 100%) !important;
        color: #ffffff !important;
        border-color: transparent !important;
        transform: translateY(-3px) scale(1.02) !important;
        box-shadow: 0 12px 25px -5px rgba(217, 119, 6, 0.4) !important;
    }
    .card-system-green {
        background: linear-gradient(135deg, #dcfce7 0%, #d1fae5 100%) !important;
        border: 1px solid #6ee7b7 !important;
    }
    .card-system-slate {
        background: linear-gradient(135deg, #ffffff 0%, #e2e8f0 100%) !important;
        border: 1px solid #cbd5e1 !important;
    }
    .icon-box-glass {
        background: rgba(255, 255, 255, 0.22) !important;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.35) !important;
        color: #ffffff !important;
    }
    .card-hover-effect {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }
    .card-hover-effect:hover {
        transform: translateY(-5px) scale(1.02) !important;
    }
</style>

    <div class="space-y-8">
        {{-- الترحيب والإحصائيات السريعة --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 card-welcome-gradient rounded-3xl p-8 relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold mb-4" style="background: rgba(99, 102, 241, 0.25); border: 1px solid rgba(165, 180, 252, 0.3); color: #c7d2fe;">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        نظام أمني متكامل
                    </div>
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-black text-white header-font mb-3">مرحباً بك مجدداً، {{ auth()->user()->name ?? 'أحمد' }}! 👋</h2>
                    <p class="text-slate-200 text-xs sm:text-sm max-w-md leading-relaxed" style="color: #cbd5e1;">نظام المعلومات الأمنية يعمل بشكل مثالي. لديك اليوم <span class="font-extrabold px-2 py-0.5 rounded-lg" style="background: rgba(16, 185, 129, 0.3); border: 1px solid rgba(52, 211, 153, 0.4); color: #6ee7b7;">{{ $stats['total_today'] }} سجل جديد</span> تم إضافتها بنجاح.</p>

                    <div class="mt-6 sm:mt-8 flex flex-wrap gap-3 sm:gap-4">
                        <a href="{{ route('system.personal-data.create') }}" class="px-4 sm:px-6 py-3 sm:py-3.5 text-xs sm:text-sm text-white rounded-2xl font-bold transition-all flex items-center gap-2" style="background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%); box-shadow: 0 8px 20px rgba(37, 99, 235, 0.4);">
                            <i class="fas fa-plus-circle text-base sm:text-lg"></i> إضافة سجل جديد
                        </a>
                        <a href="{{ route('system.personal-data.index') }}" class="px-4 sm:px-6 py-3 sm:py-3.5 text-xs sm:text-sm text-white rounded-2xl font-bold transition-all flex items-center gap-2" style="background: rgba(255, 255, 255, 0.12); border: 1px solid rgba(255, 255, 255, 0.2); backdrop-filter: blur(6px);">
                            <i class="fas fa-search text-base sm:text-lg"></i> استعلام سريع
                        </a>
                    </div>
                </div>
                {{-- ديكور خلفي --}}
                <div class="absolute -bottom-16 -left-16 w-80 h-80 rounded-full blur-3xl pointer-events-none" style="background: radial-gradient(circle, rgba(99, 102, 241, 0.3) 0%, transparent 70%);"></div>
            </div>

            <div class="card-progress-gradient rounded-3xl p-6 sm:p-8 relative overflow-hidden group card-hover-effect">
                <div class="relative z-10 flex flex-col h-full justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <p class="font-bold uppercase tracking-widest text-[10px] sm:text-[11px] px-3 py-1 rounded-full" style="background: rgba(255, 255, 255, 0.2); color: #e0e7ff;">معدل الإنجاز اليومي</p>
                            <span class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl flex items-center justify-center" style="background: rgba(255, 255, 255, 0.25); color: #fde047;">
                                <i class="fas fa-bolt text-sm sm:text-base"></i>
                            </span>
                        </div>
                        <h3 class="text-3xl sm:text-4xl md:text-5xl font-black header-font tracking-tight text-white drop-shadow-md">{{ round(($stats['total_today'] / 1000) * 100) }}%</h3>
                    </div>
                    <div class="mt-8">
                        <div class="w-full rounded-full h-3.5 mb-4 p-0.5" style="background: rgba(0, 0, 0, 0.25); border: 1px solid rgba(255, 255, 255, 0.2);">
                            <div class="h-full rounded-full transition-all duration-500" style="width: {{ min(($stats['total_today'] / 1000) * 100, 100) }}%; background: linear-gradient(90deg, #fde047 0%, #6ee7b7 50%, #ffffff 100%);"></div>
                        </div>
                        <p class="text-xs font-medium" style="color: #e0e7ff;">تم إنجاز <span class="font-bold text-white">{{ $stats['total_today'] }}</span> من أصل 1,000 سجل مستهدف اليوم.</p>
                    </div>
                </div>
                <i class="fas fa-bolt absolute -bottom-10 -left-10 text-white/10 text-[11rem] pointer-events-none"></i>
            </div>
        </div>

        {{-- فاصل خطي بين الصفوف --}}
        <hr class="my-8 border-t-2 border-slate-200/80 rounded-full">

        {{-- شبكة الإحصائيات التفصيلية --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- كارد البيانات الشخصية --}}
            <div class="card-personal-gradient p-6 rounded-3xl flex items-center justify-between gap-4 card-hover-effect relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-xs font-extrabold uppercase tracking-wider mb-1" style="color: #a7f3d0;">البيانات الشخصية</p>
                    <h4 class="text-3xl font-black text-white drop-shadow-sm">{{ number_format($stats['personal_data_count']) }}</h4>
                    <span class="inline-flex items-center gap-1 text-[11px] mt-2 px-2.5 py-0.5 rounded-full" style="background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(255, 255, 255, 0.2); color: #d1fae5;">
                        <i class="fas fa-arrow-up text-[9px]"></i> سجلات نشطة
                    </span>
                </div>
                <div class="w-16 h-16 icon-box-glass rounded-2xl flex items-center justify-center text-2xl shrink-0 relative z-10">
                    <i class="fas fa-user-shield"></i>
                </div>
            </div>

            {{-- كارد المحافظات --}}
            <div class="card-gov-gradient p-6 rounded-3xl flex items-center justify-between gap-4 card-hover-effect relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-xs font-extrabold uppercase tracking-wider mb-1" style="color: #fde68a;">المحافظات</p>
                    <h4 class="text-3xl font-black text-white drop-shadow-sm">{{ number_format($stats['governorates_count']) }}</h4>
                    <span class="inline-flex items-center gap-1 text-[11px] mt-2 px-2.5 py-0.5 rounded-full" style="background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(255, 255, 255, 0.2); color: #fef3c7;">
                        <i class="fas fa-map-marker-alt text-[9px]"></i> التغطية الجغرافية
                    </span>
                </div>
                <div class="w-16 h-16 icon-box-glass rounded-2xl flex items-center justify-center text-2xl shrink-0 relative z-10">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
            </div>

            {{-- كارد المديريات --}}
            <div class="card-dir-gradient p-6 rounded-3xl flex items-center justify-between gap-4 card-hover-effect relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-xs font-extrabold uppercase tracking-wider mb-1" style="color: #e9d5ff;">المديريات</p>
                    <h4 class="text-3xl font-black text-white drop-shadow-sm">{{ number_format($stats['directorates_count']) }}</h4>
                    <span class="inline-flex items-center gap-1 text-[11px] mt-2 px-2.5 py-0.5 rounded-full" style="background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(255, 255, 255, 0.2); color: #f3e8ff;">
                        <i class="fas fa-building text-[9px]"></i> التقسيم الإداري
                    </span>
                </div>
                <div class="w-16 h-16 icon-box-glass rounded-2xl flex items-center justify-center text-2xl shrink-0 relative z-10">
                    <i class="fas fa-city"></i>
                </div>
            </div>

            {{-- كارد القرى --}}
            <div class="card-villages-gradient p-6 rounded-3xl flex items-center justify-between gap-4 card-hover-effect relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-xs font-extrabold uppercase tracking-wider mb-1" style="color: #bae6fd;">القرى والمحلات</p>
                    <h4 class="text-3xl font-black text-white drop-shadow-sm">{{ number_format($stats['villages_count']) }}</h4>
                    <span class="inline-flex items-center gap-1 text-[11px] mt-2 px-2.5 py-0.5 rounded-full" style="background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(255, 255, 255, 0.2); color: #e0f2fe;">
                        <i class="fas fa-tree text-[9px]"></i> التجمعات السكنية
                    </span>
                </div>
                <div class="w-16 h-16 icon-box-glass rounded-2xl flex items-center justify-center text-2xl shrink-0 relative z-10">
                    <i class="fas fa-home"></i>
                </div>
            </div>
        </div>

        {{-- فاصل خطي بين الصفوف --}}
        <hr class="my-8 border-t-2 border-slate-200/80 rounded-full">

        {{-- روابط سريعة وأدوات النظام (4 كاردات في السطر) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- كارد 1: إدخال أفراد سريع --}}
            <a href="{{ route('system.personal-data.fast-entry') }}" class="card-action-blue p-6 rounded-3xl flex flex-col justify-between group card-hover-effect relative overflow-hidden">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl mb-4 shadow-md transition-all shrink-0" style="background: #2563eb; color: #ffffff;">
                    <i class="fas fa-running"></i>
                </div>
                <div>
                    <h5 class="font-extrabold text-lg block mb-1">إدخال أفراد سريع</h5>
                    <p class="text-xs opacity-80 leading-relaxed">تعبئة بيانات أفراد متعددة في صفحة واحدة</p>
                </div>
            </a>

            {{-- كارد 2: إدخال مناطق سريع --}}
            <a href="{{ route('configuration.villages.fast-entry') }}" class="card-action-amber p-6 rounded-3xl flex flex-col justify-between group card-hover-effect relative overflow-hidden">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl mb-4 shadow-md transition-all shrink-0" style="background: #d97706; color: #ffffff;">
                    <i class="fas fa-map-pin"></i>
                </div>
                <div>
                    <h5 class="font-extrabold text-lg block mb-1">إدخال مناطق سريع</h5>
                    <p class="text-xs opacity-80 leading-relaxed">إضافة مديريات وعزل وقرى دفعة واحدة</p>
                </div>
            </a>

            {{-- كارد 3: حماية البيانات --}}
            <div class="card-system-green p-6 rounded-3xl flex flex-col justify-between card-hover-effect relative overflow-hidden shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 text-white rounded-2xl flex items-center justify-center shadow-md text-2xl shrink-0" style="background: linear-gradient(135deg, #059669 0%, #0d9488 100%);">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <span class="w-9 h-9 rounded-full flex items-center justify-center" style="background: #a7f3d0; color: #047857;">
                        <i class="fas fa-check-circle text-lg"></i>
                    </span>
                </div>
                <div>
                    <h5 class="text-lg font-extrabold mb-1" style="color: #064e3b;">حماية البيانات</h5>
                    <p class="text-xs font-semibold" style="color: #047857;">النظام مؤمن ومشفر بنسبة 100%</p>
                </div>
            </div>

            {{-- كارد 4: النسخ الاحتياطي --}}
            <div class="card-system-slate p-6 rounded-3xl flex flex-col justify-between card-hover-effect relative overflow-hidden shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 text-white rounded-2xl flex items-center justify-center shadow-md text-2xl shrink-0" style="background: linear-gradient(135deg, #334155 0%, #0f172a 100%);">
                        <i class="fas fa-clock"></i>
                    </div>
                    <button class="text-xs font-bold text-white px-3 py-1.5 rounded-xl shadow-md transition-all hover:scale-105" style="background: linear-gradient(135deg, #4f46e5 0%, #2563eb 100%);">تحديث</button>
                </div>
                <div>
                    <h5 class="text-lg font-extrabold mb-1" style="color: #1e293b;">آخر نسخة احتياطية</h5>
                    <p class="text-xs font-semibold" style="color: #64748b;">منذ ساعتين و 15 دقيقة</p>
                </div>
            </div>
        </div>
    </div>
@endsection
