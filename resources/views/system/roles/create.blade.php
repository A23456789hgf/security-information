@extends('layouts.app')

@section('title', 'إضافة دور جديد')
@section('page_title', 'إدارة النظام')

@section('content')
<div class="container-fluid mx-auto px-4 py-8" dir="rtl">
    {{-- رأس الصفحة --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 max-w-6xl mx-auto">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-blue-600 rounded-3xl shadow-xl shadow-blue-100 flex items-center justify-center text-white">
                <i class="fas fa-shield-alt text-2xl"></i>
            </div>
            <div>
                <h2 class="text-3xl font-extrabold text-slate-800 header-font mb-1">إضافة دور وصلاحيات</h2>
                <p class="text-slate-500 text-sm">تحديد اسم الدور ومصفوفة الصلاحيات الممنوحة له في النظام.</p>
            </div>
        </div>
        <a href="{{ route('system.roles.index') }}"
            class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2 text-sm">
            <i class="fas fa-arrow-right"></i> عودة للقائمة
        </a>
    </div>

    @if($errors->any())
        <div class="max-w-6xl mx-auto mb-8 p-6 bg-rose-50 border border-rose-200 rounded-3xl text-rose-700">
            <div class="flex items-center gap-3 mb-2 font-bold text-base">
                <i class="fas fa-exclamation-circle text-rose-500 text-lg"></i>
                يرجى تصحيح الأخطاء التالية:
            </div>
            <ul class="list-disc list-inside text-sm space-y-1 pr-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('system.roles.store') }}" method="POST">
        @csrf
        <div class="max-w-6xl mx-auto space-y-8">
            
            {{-- اسم الدور --}}
            <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50 flex items-center gap-3">
                    <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                    <h5 class="text-lg font-bold text-slate-800 header-font">البيانات الأساسية للدور</h5>
                </div>
                <div class="p-8">
                    <label class="text-xs font-bold text-slate-400 mb-2 block px-2">اسم الدور *</label>
                    <input type="text" name="name" required
                        class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-bold text-slate-700 @error('name') ring-2 ring-rose-100 @enderror"
                        placeholder="مثلاً: مشرف أمني، مدخل بيانات، مدير تقارير..." value="{{ old('name') }}">
                </div>
            </div>

            {{-- مصفوفة الصلاحيات --}}
            <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50 flex items-center gap-3">
                    <span class="w-2 h-6 bg-purple-600 rounded-full"></span>
                    <h5 class="text-lg font-bold text-slate-800 header-font">مصفوفة الصلاحيات والعمليات</h5>
                </div>
                <div class="p-8">
                    @include('system.roles.partials._permissions')
                </div>
            </div>

            {{-- أزرار الحفظ --}}
            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('system.roles.index') }}" class="px-8 py-3 text-slate-400 font-bold hover:text-slate-600 transition-colors">إلغاء</a>
                <button type="submit"
                    class="px-10 py-4 bg-blue-600 text-white rounded-[2rem] font-bold hover:bg-blue-700 transition-all shadow-xl shadow-blue-100 flex items-center gap-2">
                    <i class="fas fa-save"></i> حفظ الدور والصلاحيات
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
