@extends('layouts.app')

@section('title', 'لوحة التحكم')
@section('page_title', 'نظرة عامة')

@section('content')
    <div class="space-y-8">
        {{-- الترحيب والإحصائيات السريعة --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white rounded-3xl p-8 soft-shadow border border-slate-100 relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="text-2xl font-extrabold text-slate-800 header-font mb-2">مرحباً بك مجدداً، {{ auth()->user()->name ?? 'أحمد' }}! 👋</h2>
                    <p class="text-slate-500 max-w-md">نظام المعلومات الأمنية يعمل بشكل مثالي. لديك اليوم <span class="text-blue-600 font-bold">{{ $stats['total_today'] }} سجل جديد</span> تم إضافتها بنجاح.</p>
                    
                    <div class="mt-8 flex flex-wrap gap-4">
                        <a href="{{ route('system.personal-data.create') }}" class="px-6 py-3 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-200">
                            <i class="fas fa-plus-circle mr-1"></i> إضافة سجل جديد
                        </a>
                        <a href="{{ route('system.personal-data.index') }}" class="px-6 py-3 bg-slate-100 text-slate-700 rounded-2xl font-bold hover:bg-slate-200 transition-all">
                            <i class="fas fa-search mr-1"></i> استعلام سريع
                        </a>
                    </div>
                </div>
                {{-- ديكور خلفي --}}
                <div class="absolute -bottom-10 -left-10 w-64 h-64 bg-blue-50 rounded-full opacity-50"></div>
                <div class="absolute top-10 left-20 w-12 h-12 bg-blue-100 rounded-lg rotate-12 opacity-30"></div>
            </div>

            <div class="bg-blue-600 rounded-3xl p-8 text-white shadow-xl shadow-blue-200 relative overflow-hidden">
                <div class="relative z-10 flex flex-col h-full justify-between">
                    <div>
                        <p class="text-blue-100 font-bold uppercase tracking-widest text-[10px] mb-2">معدل الإنجاز اليومي</p>
                        <h3 class="text-4xl font-extrabold header-font">{{ round(($stats['total_today'] / 1000) * 100) }}%</h3>
                    </div>
                    <div>
                        <div class="w-full bg-blue-500/50 rounded-full h-2 mb-4">
                            <div class="bg-white h-2 rounded-full" style="width: {{ ($stats['total_today'] / 1000) * 100 }}%"></div>
                        </div>
                        <p class="text-sm text-blue-100 italic">تم إنجاز {{ $stats['total_today'] }} من أصل 1,000 سجل مستهدف اليوم.</p>
                    </div>
                </div>
                <i class="fas fa-bolt absolute -bottom-8 -left-8 text-white/10 text-9xl"></i>
            </div>
        </div>

        {{-- شبكة الإحصائيات التفصيلية --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-3xl soft-shadow border border-slate-100 flex items-center gap-4">
                <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-xl">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div>
                    <p class="text-slate-400 text-xs font-bold uppercase">البيانات الشخصية</p>
                    <h4 class="text-xl font-extrabold text-slate-800">{{ number_format($stats['personal_data_count']) }}</h4>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl soft-shadow border border-slate-100 flex items-center gap-4">
                <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center text-xl">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <div>
                    <p class="text-slate-400 text-xs font-bold uppercase">المحافظات</p>
                    <h4 class="text-xl font-extrabold text-slate-800">{{ number_format($stats['governorates_count']) }}</h4>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl soft-shadow border border-slate-100 flex items-center gap-4">
                <div class="w-14 h-14 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center text-xl">
                    <i class="fas fa-city"></i>
                </div>
                <div>
                    <p class="text-slate-400 text-xs font-bold uppercase">المديريات</p>
                    <h4 class="text-xl font-extrabold text-slate-800">{{ number_format($stats['directorates_count']) }}</h4>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl soft-shadow border border-slate-100 flex items-center gap-4">
                <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl">
                    <i class="fas fa-home"></i>
                </div>
                <div>
                    <p class="text-slate-400 text-xs font-bold uppercase">القرى</p>
                    <h4 class="text-xl font-extrabold text-slate-800">{{ number_format($stats['villages_count']) }}</h4>
                </div>
            </div>
        </div>

        {{-- روابط سريعة --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-3xl p-8 soft-shadow border border-slate-100">
                <h5 class="text-lg font-bold text-slate-800 header-font mb-6 flex items-center gap-2">
                    <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                    أدوات الإدخال السريع
                </h5>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <a href="{{ route('system.personal-data.fast-entry') }}" class="p-4 bg-slate-50 hover:bg-blue-50 border border-slate-100 hover:border-blue-200 rounded-2xl transition-all group">
                        <i class="fas fa-running text-blue-600 mb-3 block text-xl group-hover:scale-110 transition-transform"></i>
                        <span class="font-bold text-slate-700">إدخال أفراد سريع</span>
                        <p class="text-[10px] text-slate-400 mt-1">تعبئة بيانات متعددة في صفحة واحدة</p>
                    </a>
                    <a href="{{ route('configuration.villages.fast-entry') }}" class="p-4 bg-slate-50 hover:bg-amber-50 border border-slate-100 hover:border-amber-200 rounded-2xl transition-all group">
                        <i class="fas fa-map-pin text-amber-600 mb-3 block text-xl group-hover:scale-110 transition-transform"></i>
                        <span class="font-bold text-slate-700">إدخال مناطق سريع</span>
                        <p class="text-[10px] text-slate-400 mt-1">إضافة مديريات وعزل وقرى دفعة واحدة</p>
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-8 soft-shadow border border-slate-100">
                <h5 class="text-lg font-bold text-slate-800 header-font mb-6 flex items-center gap-2">
                    <span class="w-2 h-6 bg-emerald-600 rounded-full"></span>
                    إدارة النظام والنسخ الاحتياطي
                </h5>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-emerald-50/50 rounded-2xl border border-emerald-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-emerald-100 text-emerald-700 rounded-xl flex items-center justify-center">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">حماية البيانات</p>
                                <p class="text-[10px] text-emerald-600">النظام مؤمن بنسبة 100%</p>
                            </div>
                        </div>
                        <i class="fas fa-check-circle text-emerald-500"></i>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-slate-200 text-slate-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">آخر نسخة احتياطية</p>
                                <p class="text-[10px] text-slate-400">منذ ساعتين و 15 دقيقة</p>
                            </div>
                        </div>
                        <button class="text-[10px] bg-white px-3 py-1 rounded-lg shadow-sm border border-slate-200 hover:bg-slate-50 transition-colors">تحديث</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection