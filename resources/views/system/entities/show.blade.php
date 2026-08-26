@extends('layouts.app')

@section('title', 'تفاصيل الجهة')
@section('page_title', 'الهيكل التنظيمي')

@section('content')
<div class="container-fluid mx-auto px-4 py-8" dir="rtl">
    {{-- رأس الصفحة --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 max-w-6xl mx-auto">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-blue-600 rounded-3xl shadow-xl shadow-blue-100 flex items-center justify-center text-white">
                <i class="fas fa-building text-2xl"></i>
            </div>
            <div>
                <h2 class="text-3xl font-extrabold text-slate-800 header-font mb-1">تفاصيل الجهة</h2>
                <p class="text-slate-500 text-sm">عرض ملف الجهة: <span class="font-bold text-blue-600">{{ $entity->name }}</span></p>
            </div>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('system.entities.edit', $entity) }}"
                class="px-6 py-3 bg-amber-500 text-white rounded-2xl font-bold hover:bg-amber-600 transition-all shadow-xl shadow-amber-100 flex items-center gap-2 text-sm">
                <i class="fas fa-edit"></i> تعديل
            </a>
            <a href="{{ route('system.entities.index') }}"
                class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2 text-sm">
                <i class="fas fa-arrow-right"></i> عودة للقائمة
            </a>
        </div>
    </div>

    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8">
        {{-- تفاصيل الهيكل والجهات التابعة --}}
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50 flex items-center gap-3">
                    <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                    <h5 class="text-lg font-bold text-slate-800 header-font">هيكل الجهة</h5>
                </div>
                <div class="p-8 space-y-6">
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 text-center">
                        <span class="text-xs font-bold text-slate-400 block mb-1">الجهة الأعلى (الأب)</span>
                        <span class="font-bold text-slate-700">
                            @if($entity->parent)
                                <a href="{{ route('system.entities.show', $entity->parent) }}" class="text-blue-600 hover:underline">
                                    <i class="fas fa-level-up-alt text-xs me-1"></i> {{ $entity->parent->name }}
                                </a>
                            @else
                                <span class="px-3 py-1 bg-slate-200 text-slate-600 rounded-lg text-xs font-bold">جهة رئيسية (بدون أب)</span>
                            @endif
                        </span>
                    </div>

                    <div>
                        <span class="text-xs font-bold text-slate-400 mb-3 block px-2">الجهات التابعة (الأبناء):</span>
                        <div class="space-y-2">
                            @forelse($entity->children as $child)
                                <a href="{{ route('system.entities.show', $child) }}" class="flex items-center justify-between p-4 bg-slate-50 hover:bg-blue-50 border border-slate-100 rounded-2xl transition-all">
                                    <span class="font-bold text-slate-700 text-xs flex items-center gap-2">
                                        <i class="fas fa-building text-slate-400"></i> {{ $child->name }}
                                    </span>
                                    <i class="fas fa-chevron-left text-xs text-slate-300"></i>
                                </a>
                            @empty
                                <p class="text-slate-400 text-xs text-center py-4 bg-slate-50 rounded-2xl">لا توجد جهات تابعة</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- الموقع والمستخدمين --}}
        <div class="lg:col-span-7 space-y-6">
            <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50 flex items-center gap-3">
                    <span class="w-2 h-6 bg-emerald-500 rounded-full"></span>
                    <h5 class="text-lg font-bold text-slate-800 header-font">الموقع الجغرافي</h5>
                </div>
                <div class="p-8 grid grid-cols-2 gap-4">
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 flex flex-col gap-1">
                        <span class="text-xs font-bold text-slate-400">المحافظة</span>
                        <span class="font-bold text-slate-700">{{ $entity->governorate->name ?? 'غير محدد' }}</span>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 flex flex-col gap-1">
                        <span class="text-xs font-bold text-slate-400">المديرية</span>
                        <span class="font-bold text-slate-700">{{ $entity->directorate->name ?? 'غير محدد' }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50 flex items-center gap-3">
                    <span class="w-2 h-6 bg-purple-600 rounded-full"></span>
                    <h5 class="text-lg font-bold text-slate-800 header-font">المستخدمون المرتبطون</h5>
                </div>
                <div class="p-0">
                    <table class="w-full text-right border-collapse text-xs">
                        <thead class="bg-slate-50/80 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 text-slate-400 font-bold">الاسم</th>
                                <th class="px-6 py-4 text-slate-400 font-bold">اسم المستخدم</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($entity->users as $u)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-slate-700">{{ $u->name }}</td>
                                    <td class="px-6 py-4 font-mono text-slate-500">{{ $u->username }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-8 text-center text-slate-400 font-bold">لا يوجد مستخدمون مرتبطون</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
