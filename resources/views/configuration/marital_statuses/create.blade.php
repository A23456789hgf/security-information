@extends('layouts.app')

@section('title', 'إضافة حالة اجتماعية')
@section('page_title', 'الترميزات الأساسية')

@section('content')
    <div class="space-y-6" dir="rtl">
        {{-- رأس الصفحة --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-brand-600 rounded-3xl shadow-xl shadow-blue-100 flex items-center justify-center text-white">
                    <i class="fas fa-plus text-2xl"></i>
                </div>
                <div>
                    <h2 class="font-display font-bold text-2xl text-slate-900 dark:text-white m-0">إضافة حالة اجتماعية</h2>
                    <p class="text-slate-500 text-sm">إضافة تصنيف جديد للحالات الاجتماعية في النظام.</p>
                </div>
            </div>
            <a href="{{ route('configuration.marital-statuses.index') }}"
                class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
                <i class="fas fa-arrow-right"></i> عودة للقائمة
            </a>
        </div>

        <div class="max-w-2xl mx-auto">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden transition-colors">
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50">
                    <h5 class="text-lg font-bold text-slate-800 dark:text-slate-200 header-font flex items-center gap-3">
                        <span class="w-2 h-6 bg-brand-600 rounded-full"></span>
                        بيانات الحالة
                    </h5>
                </div>
                <div class="p-8">
                    <form action="{{ route('configuration.marital-statuses.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-8">
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">الحالة الاجتماعية *</label>
                            <input type="text" name="status" required 
                                class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-bold text-slate-700 @error('status') ring-2 ring-rose-100 @enderror" 
                                placeholder="مثال: أعزب، متزوج، مطلق، أرمل..." value="{{ old('status') }}">
                            @error('status')
                                <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <button type="button" onclick="history.back()" class="px-8 py-3 text-slate-400 font-bold hover:text-slate-600 transition-colors">إلغاء</button>
                            <button type="submit" class="px-10 py-4 bg-brand-600 text-white rounded-[2rem] font-bold hover:bg-brand-700 transition-all shadow-xl shadow-blue-100 flex items-center gap-2">
                                <i class="fas fa-check-circle"></i> حفظ البيانات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


