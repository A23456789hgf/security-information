@extends('layouts.app')

@section('title', 'إضافة دولة جديدة')
@section('page_title', 'الترميزات الجغرافية')

@section('content')
    <div class="container-fluid mx-auto px-4 py-8" dir="rtl">
        {{-- رأس الصفحة --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-blue-600 rounded-3xl shadow-xl shadow-blue-100 flex items-center justify-center text-white">
                    <i class="fas fa-plus text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 header-font mb-1">إضافة دولة جديدة</h2>
                    <p class="text-slate-500 text-sm">إضافة تصنيف جديد للدول في النظام الجغرافي.</p>
                </div>
            </div>
            <a href="{{ route('configuration.countries.index') }}"
                class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
                <i class="fas fa-arrow-right"></i> عودة للقائمة
            </a>
        </div>

        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50">
                    <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                        <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                        بيانات الدولة
                    </h5>
                </div>
                <div class="p-8">
                    <form action="{{ route('configuration.countries.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-8">
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">اسم الدولة *</label>
                            <input type="text" name="name" required 
                                class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-bold text-slate-700 @error('name') ring-2 ring-rose-100 @enderror" 
                                placeholder="مثال: اليمن، السعودية، مصر..." value="{{ old('name') }}">
                            @error('name')
                                <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <button type="button" onclick="history.back()" class="px-8 py-3 text-slate-400 font-bold hover:text-slate-600 transition-colors">إلغاء</button>
                            <button type="submit" class="px-10 py-4 bg-blue-600 text-white rounded-[2rem] font-bold hover:bg-blue-700 transition-all shadow-xl shadow-blue-100 flex items-center gap-2">
                                <i class="fas fa-check-circle"></i> حفظ البيانات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
